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
    public static $cacheStoreKey = 'pouchCacheStore';

    /**
     * Store cache instance as a singleton.
     *
     * @param \Psr\SimpleCache\CacheInterface $cacheStore
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    protected static function initCache(CacheInterface $cacheStore = null)
    {
        Pouch::singleton(self::$cacheStoreKey, function () use ($cacheStore) {
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
        $cacheStore = Pouch::singleton(self::$cacheStoreKey);

        if ($cacheStore instanceof Apcu && !Apcu::enabled()) {
            return $cacheValue;
        }

        $key = self::$cacheStoreKey.'_'.$key;

        if ($cacheStore->has($key)) {
            $cacheValue = $cacheStore->get($key, $cacheValue);
        } else {
            $cacheStore->set($key, $cacheValue);
        }

        return $cacheValue;
    }
}
