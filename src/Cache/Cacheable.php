<?php

namespace Pouch\Cache;

use Pouch\Pouch;
use Psr\SimpleCache\CacheInterface;

trait Cacheable
{
    /**
     * Key of the singleton holding the cache handler.
     *
     * @var string
     */
    public static $cacheKey = 'pouchCacheStore';

    /**
     * Store cache instance as a singleton.
     *
     * @param \Psr\SimpleCache\CacheInterface $cacheStore
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    protected static function initCache(CacheInterface $cacheStore = null)
    {
        Pouch::singleton(self::$cacheKey, function () use ($cacheStore) {
            return $cacheStore ?? new Apcu();
        });
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
    protected function cache($key, \Closure $value)
    {
        $cacheValue = $value();
        $cacheStore = Pouch::singleton(self::$cacheKey);

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
}
