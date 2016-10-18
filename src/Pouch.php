<?php

namespace Pouch;

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
    protected static $repeatables = [];

    /**
     * Bind a new element to the repeatables.
     * 
     * @param  string   $key
     * @param  Callable $callback
     * @return void
     */
    public static function bind($key, Callable $callback)
    {
        static::$repeatables[$key] = $callback();
    }

    /**
     * Resolve specific key from our repeatables.
     * 
     * @param  string $key
     * @return mixed
     */
    public static function resolve($key)
    {
        if (!array_key_exists($key, static::$repeatables)) {
            throw new KeyNotFoundException("The {$key} key could not be found in the container.");
        }

        return static::$repeatables[$key];
    }

    /**
     * See if specific key exists in our repeatables.
     * 
     * @param  string  $key
     * @return boolean
     */
    public static function has($key)
    {
        return array_key_exists($key, static::$repeatables);
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
