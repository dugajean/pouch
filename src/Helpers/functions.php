<?php

use Pouch\Pouch;
use Pouch\Cache\Apcu;

/**
 * Return pouch singleton instance.
 *
 * @return \Pouch\Pouch
 */
function pouch()
{
    return Pouch::singleton('pouch', function () { return new Pouch; });
}

/**
 * Helper to retrieve data from cache store.
 *
 * @param string   $key
 * @param \Closure $value
 *
 * @return mixed
 *
 * @throws \Pouch\Exceptions\NotFoundException
 * @throws \Pouch\Exceptions\InvalidArgumentException
 */
function pouchCache($key, Closure $value)
{
    $cacheValue = $value();
    $cacheStore = Pouch::singleton(Pouch::CACHE_KEY);

    if ($cacheStore instanceof Apcu && !Apcu::enabled()) {
        return $cacheValue;
    }

    $key = Pouch::CACHE_KEY.'_'.$key;

    if ($cacheStore->has($key)) {
        $item = $cacheStore->get($key, $cacheValue);
    } else {
        $item = $cacheValue;
        $cacheStore->set($key, $item);
    }

    return $item;
}

if (!function_exists('resolve')) {
    /**
     * Resolve a key within the container.
     *
     * @param string $key
     *
     * @return mixed
     */
    function resolve($key)
    {
        return pouch()->resolve($key);
    }
}
