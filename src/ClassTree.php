<?php

namespace Pouch;

use RegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

final class ClassTree
{
    /**
     * Application's root.
     *
     * @var string
     */
    private static $root;

    /**
     * Set the application's root.
     *
     * @param $dir string
     */
    public static function setRoot($dir)
    {
        self::$root = $dir . '/';
    }

    /**
     * Get all namespaces recursively for a namespace.
     *
     * @param $namespace string
     *
     * @return array
     */
    public static function getClassesInNamespace($namespace)
    {
        $path = self::getNamespaceDirectory($namespace);
        $fqcns = [];

        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
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
    private static function getDefinedNamespaces()
    {
        $composerJsonPath = self::$root.'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        $psr4 = "psr-4";
        return (array) $composerConfig->autoload->$psr4;
    }

    /**
     * Prepare the paths.
     *
     * @param $namespace
     *
     * @return string
     */
    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments).'\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(self::$root.$composerNamespaces[$possibleNamespace].implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return null;
    }
}
