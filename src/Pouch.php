<?php

namespace Pouch;

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
    protected static $replaceables = [];

    /**
     * Bind a new element to the replaceables.
     * 
     * @param  string   $key
     * @param  Callable $callback
     * @return void
     */
    public static function bind($key, Callable $callback)
    {
        static::$replaceables[$key] = $callback();
    }

    /**
     * Resolve specific key from our replaceables.
     * 
     * @param  string $key
     * @return mixed
     */
    public static function resolve($key)
    {
        if (!array_key_exists($key, static::$replaceables)) {
            throw new KeyNotFoundException("The {$key} key could not be found in the container.");
        }

        return static::$replaceables[$key];
    }

    /**
     * See if specific key exists in our replaceables.
     * 
     * @param  string  $key
     * @return boolean
     */
    public static function has($key)
    {
        return array_key_exists($key, static::$replaceables);
    }

    /**
     * Insert or return a singleton instance from our container.
     * 
     * @param  string $key
     * @param  Callable|null $callback
     * @return mixed|void
     */
    public static function singleton($key, Callable $callback = null)
    {
        if (array_key_exists($key, static::$singletons) || $callback === null) {
            return static::$singletons[$key];
        }

        $this->singleton[$key] = $callback();
    }
}
