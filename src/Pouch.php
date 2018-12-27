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

        require __DIR__."/../src/Helpers/functions.php";
    }

    /**
     * Bind a new element to the replaceables.
     * 
     * @param  string $key
     * @param  mixed  $data
     * 
     * @return $this
     */
    protected function bind($key, $data)
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
    protected function registerNamespaces($namespaces, array $overriders = [])
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
    protected function resolve($key)
    {
        if (!is_string($key)) {
            throw new InvalidTypeException('The key must be a string');
        }

        if (!array_key_exists($key, $this->replaceables)) {
            throw new KeyNotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key];
    }

    /**
     * See if specific key exists in our replaceables.
     * 
     * @param  string  $key
     * 
     * @return boolean
     */
    protected function has($key)
    {
        return array_key_exists($key, $this->replaceables);
    }

    /**
     * Insert or return a singleton instance from our container.
     * 
     * @param  string     $key
     * @param  mixed|null $data
     * 
     * @return mixed|void
     */
    public static function singleton($key, $data = null)
    {
        if (array_key_exists($key, static::$singletons) || $data === null) {
            return static::$singletons[$key];
        }

        return static::$singletons[$key] = is_callable($data) ? $data() : $data;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return pouch()->$method(...$args);
        }
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
