<?php /** @noinspection ALL */

use Pouch\Pouch;

if (!function_exists('pouch')) {
    /**
     * Return pouch singleton instance.
     *
     * @return \Pouch\Pouch
     */
    function pouch()
    {
        return Pouch::singleton('pouch', function () {
            return new Pouch;
        });
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve a key within the container.
     *
     * @param string $key
     *
     * @return mixed
     * @throws \Pouch\Exceptions\NotFoundException
     */
    function resolve($key)
    {
        return pouch()->get($key);
    }
}
