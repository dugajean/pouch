<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Pouch\Exceptions\NotFoundException;

final class ClassTree
{
    /**
     * Application's root.
     *
     * @var string
     */
    private static $root;

    /**
     * Include autoload-dev in results.
     *
     * @var bool
     */
    private static $includeDev = false;

    /**
     * Set the application's root.
     *
     * @param $dir string
     */
    public static function setRoot(string $dir)
    {
        self::$root = $dir;

        if (substr($dir, -strlen('/')) !== '/') {
            self::$root .= '/';
        }
    }

    /**
     * Choose whether to include autoload-dev in the results.
     *
     * @param bool $load
     */
    public static function loadDev(bool $load = false)
    {
        self::$includeDev = $load;
    }

    /**
     * Get all sub-namespaces recursively for a namespace.
     *
     * @param $namespace string
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function getClassesInNamespace(string $namespace): array
    {
        $path  = self::getNamespaceDirectory($namespace);
        $fqcns = [];

        if ($path === null) {
            throw new NotFoundException('This namespace cannot be found or is not registered in composer.json');
        }

        $realPath = '';
        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');

        $pharPath = \Phar::running();
        foreach ($phpFiles as $phpFile) {
            $realPath = $pharPath 
                ? $phpFile->getPath() . '/' . $phpFile->getFilename()
                : $phpFile->getRealPath();

            $content = file_get_contents($realPath);
            $tokens = token_get_all($content);

            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }

                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }

                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2;
                    $fqcns[] = $namespace.'\\'.$tokens[$index][1];
                    break;
                }
            }
        }

        return $fqcns;
    }

    /**
     * Get the namespaces declared in the PSR-4 section of composer.json.
     *
     * @return array
     */
    private static function getDefinedNamespaces(): array
    {
        $composerJsonPath = self::$root.'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        $psr4 = "psr-4";
        $autoload = (array)$composerConfig->autoload->$psr4;

        if (self::$includeDev) {
            $composerKey = 'autoload-dev';
            $autoloadDev = (array)$composerConfig->$composerKey->$psr4;
            $autoload = array_merge($autoload, $autoloadDev);
        }

        return $autoload;
    }

    /**
     * Prepare the paths.
     *
     * @param string $namespace
     *
     * @return string
     */
    private static function getNamespaceDirectory(string $namespace): ?string
    {
        $composerNamespaces = self::getDefinedNamespaces();
        $namespaceFragments = explode('\\', $namespace);

        if (class_exists($namespace)) {
            array_pop($namespaceFragments);
        }

        $pharPath = \Phar::running();
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments).'\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                if (!$pharPath) {
                    return realpath(
                        self::$root . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments)
                    );
                } else {
                    $pharRoot = $pharPath . '/';
                    return $pharRoot . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments);
                }
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return null;
    }
}
