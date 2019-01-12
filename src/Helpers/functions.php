<?php

use Pouch\Pouch;
use Pouch\Factory;
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
