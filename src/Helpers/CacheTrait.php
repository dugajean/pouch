<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Closure;
use Pouch\Pouch;
use Pouch\Cache\ApcuCache;

trait CacheTrait
{
    /**
     * Key of the singleton holding the cache handler.
     *
     * @var string
     */
    public static $cacheStoreKey = 'pouchCacheStore';

    /**
     * Helper to retrieve data from cache store.
     *
     * @param string  $key
     * @param Closure $value
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    protected function cache(string $key, Closure $value)
    {
        $cacheValue = $value();
        $cacheStore = Pouch::singleton(self::$cacheStoreKey);

        if ($cacheStore instanceof ApcuCache && !ApcuCache::enabled()) {
            return $cacheValue;
        }

        $key = md5(self::$cacheStoreKey.'_'.$key);

        if ($cacheStore->has($key)) {
            $cacheValue = $cacheStore->get($key, $cacheValue);
        } else {
            $cacheStore->set($key, $cacheValue);
        }

        return $cacheValue;
    }
}
