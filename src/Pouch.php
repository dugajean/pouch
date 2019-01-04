<?php

namespace Pouch;

use Pouch\Cache\Apcu;
use Pouch\Helpers\ClassTree;
use Psr\SimpleCache\CacheInterface;
use Psr\Container\ContainerInterface;
use Pouch\Exceptions\PouchException;
use Pouch\Exceptions\NotFoundException;

class Pouch implements ContainerInterface
{
    /**
     * Key of the singleton holding the cache handler.
     */
    const CACHE_KEY = 'pouchCacheStore';

    /**
     * Store all singletons.
     * 
     * @var array
     */
    protected static $singletons = [];

    /**
     * Store all the data that can be replaced.
     * 
     * @var array
     */
    protected $replaceables = [];

    /**
     * Bootstrap pouch.
     *
     * @param string              $dir Path to the app's root (Where composer.json is).
     * @param CacheInterface|null $cacheStore PSR-16 compatible cache store instance. Will be used to speed up
     *                                        Pouch's performance by caching some heavy-ish tasks.
     *
     * @return void
     */
    public static function bootstrap($rootDir, CacheInterface $cacheStore = null)
    {
        ClassTree::setRoot($rootDir);

        self::singleton(self::CACHE_KEY, function () use ($cacheStore) {
            return $cacheStore ?? new Apcu;
        });

        require __DIR__.'/../src/Helpers/functions.php';
    }

    /**
     * Bind a new element to the replaceables.
     * 
     * @param  string   $key
     * @param  Callable $data
     * 
     * @return $this
     */
    protected function bind($key, Callable $data)
    {
        $this->replaceables[(string)$key] = $data($this);

        return $this;
    }

    /**
     * Remove something from the container.
     *
     * @param $key
     *
     * @return $this
     */
    protected function remove($key)
    {
        if ($this->has($key)) {
            unset($this->replaceables[$key]);
        }

        return $this;
    }

    /**
     * Register one or more namespaces for automatic resolution.
     *
     * @param string|string[] $namespaces List of namespaces to be made resolvable.
     *                                    Will go recursively through the namespace.
     * @param array           $overriders Overriders can be used to hook into the process of fetching
     *                                    namespace paths and allow you to replace the minimal instantiation
     *                                    process (which is a simple 'new Class' call to something specific
     *                                    for those classes (e.g. provide constructor parameters etc.).
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    protected function registerNamespaces($namespaces, array $overriders = [])
    {
        foreach ((array)$namespaces as $namespace) {
            $classes = pouchCache($namespace, function () use ($namespace) {
                return ClassTree::getClassesInNamespace($namespace);
            });

            foreach ($classes as $class) {
                $newContent = $class;

                if (in_array($class, array_keys($overriders))) {
                    if (!is_callable($overriders[$class])) {
                        throw new PouchException('Overrider value must be a function.');
                    }

                    $newContent = $overriders[$class]($this);
                }

                $this->bind($class, function () use ($newContent) {
                    return new Resolvable($newContent);
                });
            }
        }

        return $this;
    }

    /**
     * Resolve specific key from our replaceables.
     * 
     * @param string $key
     * 
     * @return mixed
     *
     * @throws \Pouch\Exceptions\PouchException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    protected function resolve($key)
    {
        if (!is_string($key)) {
            throw new PouchException('The key must be a string');
        }

        if (!array_key_exists($key, $this->replaceables)) {
            throw new NotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key];
    }

    /**
     * See if specific key exists in our replaceables.
     * 
     * @param string  $key
     * 
     * @return bool
     */
    protected function contains($key)
    {
        return array_key_exists($key, $this->replaceables);
    }

    /**
     * Alias for resolve.
     *
     * @param  string $key
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\PouchException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function get($key)
    {
        return $this->resolve($key);
    }

    /**
     * Alias for contains.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->contains($key);
    }

    /**
     * To allow calls of the methods of this class "statically" (via singleton),
     * this class's methods have to be set to protected. Then we use __call
     * in order to call the protected methods normally and __callStatic
     * to simulate static calls of all methods from the singleton
     * instance and everything ends up wired up perfectly.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\PouchException
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$args);
        }

        throw new PouchException("Method Pouch::{$method} does not exist");
    }

    /**
     * Allow retrieving container values via magic properties.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\PouchException
     */
    public function __get($key)
    {
        return $this->resolve($key);
    }

    /**
     * Allows the use of isset() to determine if something exists in the container.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Allows the use of unset() to remove key a key from the container.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * Insert or return a singleton instance from our container.
     *
     * @param  string   $key
     * @param  Callable $data
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function singleton($key, Callable $data = null)
    {
        if (array_key_exists($key, self::$singletons)) {
            return self::$singletons[$key];
        }

        if ($data === null) {
            throw new NotFoundException("The {$key} key could not be found in the singleton container");
        }

        return self::$singletons[$key] = $data();
    }

    /**
     * Allow calling all the methods of this class statically.
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\PouchException
     */
    public static function __callStatic($method, $args)
    {
        if (method_exists(pouch(), $method)) {
            return pouch()->$method(...$args);
        }

        throw new PouchException("Method Pouch::{$method} does not exist");
    }
}
