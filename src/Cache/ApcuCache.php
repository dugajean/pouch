<?php

declare(strict_types=1);

namespace Pouch\Cache;

use DateTime;
use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Pouch\Exceptions\InvalidArgumentException;

class ApcuCache implements CacheInterface
{
    /**
     * Characters that should not be part of the cache key.
     */
    const RESERVED_CHARACTERS = ['{','}','(',')','/','@',':'];

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $this->checkReservedCharacters($key);
        $value = apcu_fetch($key);

        return $value ?: $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function set($key, $value, $ttl = null)
    {
        $this->checkReservedCharacters($key);

        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }

        return apcu_store($key, $value, (int)$ttl);
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function delete($key)
    {
        $this->checkReservedCharacters($key);

        return (bool)apcu_delete($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        return apcu_clear_cache();
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function getMultiple($keys, $default = null)
    {
        $defaults = array_fill(0, count($keys), $default);

        foreach ($keys as $key) {
            $this->checkReservedCharacters($key);
        }

        return array_merge(apcu_fetch($keys), $defaults);
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->checkReservedCharacters($key);
        }

        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }

        $result =  apcu_store($values, null, $ttl);

        return empty($result);
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return array True if the items were successfully removed. False if there was an error.
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function deleteMultiple($keys)
    {
        $ret = [];
        foreach ($keys as $key) {
            $this->checkReservedCharacters($key);
            $ret[$key] = apcu_delete($key);
        }

        return $ret;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function has($key)
    {
        $this->checkReservedCharacters($key);

        return apcu_exists($key);
    }

    /**
     * Determines whether the cache key is valid or not.
     *
     * @param string $key
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    private function checkReservedCharacters(string $key)
    {
        if (!is_string($key)) {
            $type = gettype($key);
            throw new InvalidArgumentException("The key must be a string. {$type} provided");
        }

        foreach (self::RESERVED_CHARACTERS as $needle) {
            if (strpos($key, $needle) !== false) {
                $badChars = implode('', self::RESERVED_CHARACTERS);
                throw new InvalidArgumentException("$key is not a legal value. The key cannot contain {$badChars}");
            }
        }
    }

    /**
     * Verify APCu is installed and available.
     *
     * @return bool
     */
    public static function enabled(): bool
    {
        return extension_loaded('apcu') && ini_get('apc.enabled');
    }
}
