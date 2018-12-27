<?php

namespace Pouch;

use Pouch\Helpers\ClassTree;
use Pouch\Exceptions\PouchException;
use Psr\Container\ContainerInterface;
use Pouch\Exceptions\NotFoundException;

class Pouch implements ContainerInterface
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
     * @param string $dir Path to the app's root (Where composer.json is).
     */
    public static function bootstrap($dir)
    {
        ClassTree::setRoot($dir);

        require __DIR__.'/../src/Helpers/functions.php';
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
        $this->replaceables[(string)$key] = is_callable($data) ? $data($this) : $data;

        return $this;
    }

    /**
     * Register one or more namespaces for automatic resolution.
     *
     * @param array|string $namespaces List of namespaces to be made resolvable.
     *                                 Will go recursively through the namespace.
     * @param array $overriders
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    protected function registerNamespaces($namespaces, array $overriders = [])
    {
        foreach ((array)$namespaces as $namespace) {
            $classes = ClassTree::getClassesInNamespace($namespace);

            foreach ($classes as $class) {
                $newContent = null;
                $resolvable = new Resolvable;

                if (in_array($class, array_keys($overriders))) {
                    $overrider = is_callable($overriders[$class]) ? $overriders[$class]($this) : $overriders[$class];
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
     * @param  string  $key
     * 
     * @return boolean
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
     * @param  string  $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->contains($key);
    }

    /**
     * To allows for calling the methods of this class statically (via singleton),
     * this class's methods have to be set to protected. Then we use __call
     * in order to call the protected methods normally from the singleton
     * instance and everything ends up wired up perfectly.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\PouchException
     */
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return pouch()->$method(...$args);
        }

        throw new PouchException("Method Pouch::{$method} does not exist");
    }

    /**
     * Insert or return a singleton instance from our container.
     *
     * @param  string     $key
     * @param  mixed|null $data
     *
     * @return mixed
     */
    public static function singleton($key, $data = null)
    {
        if (array_key_exists($key, self::$singletons) || $data === null) {
            return self::$singletons[$key];
        }

        return self::$singletons[$key] = is_callable($data) ? $data() : $data;
    }

    /**
     * Allow calling all the methods of this class statically.
     *
     * @param $method
     * @param $args
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
