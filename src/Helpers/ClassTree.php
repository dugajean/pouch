<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Phar;
use Symfony\Component\Finder\Finder;
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
     * The path to be scanned.
     *
     * @var string|null
     */
    public static $startPath;

    /**
     * Include autoload-dev in results.
     *
     * @var bool
     */
    public static $loadDev = false;

    /**
     * @param string      $root
     * @param string|null $startPath
     * @param bool        $loadDev
     */
    public static function bootstrap(string $root, ?string $startPath, bool $loadDev = false)
    {
        self::$root = $root;

        if (substr(self::$root, -strlen('/')) !== '/') {
            self::$root .= '/';
        }

        self::$startPath = $startPath;
        self::$loadDev = $loadDev;
    }

    /**
     * Get all sub-namespaces recursively for a namespace.
     *
     * @param string $baseNamespace
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function unfold(string $baseNamespace)
    {
        $fqcns = [];
        $path = self::scanDirectory($baseNamespace);

        if ($path === null) {
            throw new NotFoundException('This namespace cannot be found or is not registered in composer.json');
        }

        $finderPath = (new Finder)->in($path)->files()->name('*.php');

        /** @var Finder $finderPath */
        foreach ($finderPath as $phpFile) {
            $content = $phpFile->getContents();
            $phpToken = token_get_all($content);

            $namespace = '';
            for ($index = 0; isset($phpToken[$index]); $index++) {
                if (!isset($phpToken[$index][0])) {
                    continue;
                }

                if (T_NAMESPACE === $phpToken[$index][0]) {
                    $index += 2;
                    while (isset($phpToken[$index]) && is_array($phpToken[$index])) {
                        $namespace .= $phpToken[$index++][1];
                    }
                }

                if (
                    T_CLASS === $phpToken[$index][0]
                    && T_WHITESPACE === $phpToken[$index + 1][0]
                    && T_STRING === $phpToken[$index + 2][0]
                ) {
                    $index += 2;
                    $fqcns[] = $namespace . '\\' . $phpToken[$index][1];
                    break;
                }
            }
        }

        return $fqcns;
    }

    /**
     * Prepare the paths.
     *
     * @param string $baseNamespace
     *
     * @return string
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private static function scanDirectory(string $baseNamespace): ?string
    {
        $namespaceMap = self::namespaceMap($baseNamespace);
        $namespaceParts = explode('\\', $baseNamespace);

        if (class_exists($baseNamespace)) {
            array_pop($namespaceParts);
        }

        $pharPath = Phar::running();
        $undefinedNamespaceParts = [];
        
        while ($namespaceParts) {
            $possibleNamespace = implode('\\', $namespaceParts) . '\\';

            if (array_key_exists($possibleNamespace, $namespaceMap)) {
                if (!$pharPath) {
                    return realpath(
                        self::normalizePath(
                            self::$root .
                            $namespaceMap[$possibleNamespace] .
                            implode('/', $undefinedNamespaceParts
                        )
                    )) ?: null;
                } else {
                    $pharRoot = $pharPath . '/';
                    return $pharRoot . $namespaceMap[$possibleNamespace] . implode('/', $undefinedNamespaceParts);
                }
            }

            array_unshift($undefinedNamespaceParts, array_pop($namespaceParts));
        }

        return null;
    }

    /**
     * Get the namespaces declared in the PSR-4 section of composer.json.
     *
     * @param string $baseNamespace
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private static function namespaceMap(string $baseNamespace): array
    {
        if (self::$startPath !== null) {
            $rawPath = self::normalizePath(self::$startPath);
            
            if (file_exists($rawPath)) {
                return [$baseNamespace . '\\' => $rawPath];
            }

            throw new NotFoundException('The specified path could not be found: ' . $rawPath);
        }
        
        $composerContents = @file_get_contents(self::$root . 'composer.json', true);

        if ($composerContents === false) {
            throw new NotFoundException("Could not find composer.json at the provided path: " . self::$root);
        }

        $composerConfig = json_decode($composerContents);

        $psr4 = 'psr-4';
        $autoload = (array)$composerConfig->autoload->$psr4;

        if (self::$loadDev) {
            $composerKey = 'autoload-dev';
            $autoloadDev = (array)$composerConfig->$composerKey->$psr4;
            $autoload = array_merge($autoload, $autoloadDev);
        }

        return $autoload;
    }

    /**
     * Normalizes the slashes in a path.
     *
     * @param string $path
     *
     * @return string
     */
    private static function normalizePath(string $path): string
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}
