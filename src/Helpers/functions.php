<?php

use Pouch\Pouch;

/**
 * Return pouch singleton instance.
 *
 * @return Pouch
 */
function pouch()
{
    return Pouch::singleton('pouch', function () { return new Pouch; });
}

if (!function_exists('resolve')) {
    /**
     * Resolve a key within the container.
     *
     * @param $key
     *
     * @return mixed
     */
    function resolve($key)
    {
        return pouch()->resolve($key);
    }
}
