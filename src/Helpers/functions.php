<?php /** @noinspection ALL */

declare(strict_types = 1);

use Pouch\Pouch;

if (!function_exists('pouch')) {
    /**
     * Return pouch singleton instance.
     *
     * @return \Pouch\Pouch
     */
    function pouch(): Pouch
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
