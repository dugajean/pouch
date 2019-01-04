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

    $cKey = Pouch::CACHE_KEY.'_'.$key;

    if ($cacheStore->has($cKey)) {
        $item = $cacheStore->get($cKey, $cacheValue);
    } else {
        $item = $cacheValue;
        $cacheStore->set($cKey, $cacheValue);
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
