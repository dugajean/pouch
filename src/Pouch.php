<?php

namespace Pouch;

use Pouch\Exceptions\InvalidTypeException;
use Pouch\Exceptions\KeyNotFoundException;

class Pouch 
{
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
     * @param $dir string
     */
    public static function bootstrap($dir)
    {
        ClassTree::setRoot($dir);
    }

    /**
     * Bind a new element to the replaceables.
     * 
     * @param  string $key
     * @param  mixed  $data
     * 
     * @return $this
     */
    public function bind($key, $data)
    {
        $this->replaceables[(string)$key] = is_callable($data) ? $data() : $data;

        return $this;
    }

    /**
     * Register a namespace for automatic resolution.
     *
     * @param array|string $namespaces
     * @param array $overriders
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidTypeException
     */
    public function registerNamespaces($namespaces, array $overriders = [])
    {
        foreach ((array)$namespaces as $namespace) {
            $classes = ClassTree::getClassesInNamespace($namespace);

            foreach ($classes as $class) {
                $newContent = null;
                $resolvable = new Resolvable;

                if (in_array($class, array_keys($overriders))) {
                    $overrider = is_callable($overriders[$class]) ? $overriders[$class]() : $overriders[$class];
                    $newContent = $resolvable->make($overrider);
                }

                $this->bind($class, function () use ($class, $resolvable, $newContent) {
                    return $newContent !== null ? $newContent : $resolvable->make($class);
                });
            };
        }

        return $this;
    }

    /**
     * Resolve specific key from our replaceables.
     * 
     * @param  string $key
     * 
     * @return mixed
     */
    public function resolve($key)
    {
        if (!is_string($key)) {
            throw new InvalidTypeException('The key must be a string');
        }

        if (!array_key_exists($key, static::$replaceables)) {
            throw new KeyNotFoundException("The {$key} key could not be found in the container");
        }

        return $this->eplaceables[$key];
    }

    /**
     * See if specific key exists in our replaceables.
     * 
     * @param  string  $key
     * 
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, static::$replaceables);
    }

    /**
     * Insert or return a singleton instance from our container.
     * 
     * @param  string     $key
     * @param  mixed|null $data
     * 
     * @return mixed|void
     */
    public static function singleton($key, Callable $data = null)
    {
        if (array_key_exists($key, static::$singletons) || $data === null) {
            return static::$singletons[$key];
        }

        static::$singleton[$key] = is_callable($data) ? $data() : $data;
    }

    /**
     * Allow calling all the methods of this class statically.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (method_exists(pouch(), $method)) {
            return pouch()->$method(...$args);
        }
    }
}
