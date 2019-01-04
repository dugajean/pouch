<?php

namespace Pouch\Cache;

use DateTime;
use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Pouch\Exceptions\InvalidArgumentException;

class Apcu implements CacheInterface
{
    const RESERVED_CHARACTERS = ['{','}','(',')','/','@',':'];

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $this->checkReservedCharacters($key);
        $value = apcu_fetch($key);

        return $value ?: $default;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $this->checkReservedCharacters($key);

        return (bool)apcu_delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        return apcu_clear_cache();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function has($key)
    {
        $this->checkReservedCharacters($key);

        return apcu_exists($key);
    }

    /**
     * Determines whether the cache key is valid or not.
     *
     * @param $key
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    private function checkReservedCharacters($key)
    {
        if (!is_string($key)) {
            $message = sprintf('key %s is not a string.', $key);
            throw new InvalidArgumentException($message);
        }

        foreach (self::RESERVED_CHARACTERS as $needle) {
            if (strpos($key, $needle) !== false) {
                $message = sprintf('%s string is not a legal value.', $key);
                throw new InvalidArgumentException($message);
            }
        }
    }

    /**
     * Verify APCu is installed and available.
     *
     * @return bool
     */
    public static function enabled()
    {
        return extension_loaded('apcu') && ini_get('apc.enabled');
    }
}
