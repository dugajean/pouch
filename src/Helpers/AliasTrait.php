<?php

declare(strict_types=1);

namespace Pouch\Helpers;

trait AliasTrait
{
    /**
     * Alias for bind.
     *
     * @param string|array  $keyOrData
     * @param callable|null $data
     *
     * @return $this
     */
    public function register($keyOrData, $data = null): self
    {
        return $this->bind($keyOrData, $data);
    }

    /**
     * Alias for bind.
     *
     * @param string|array  $keyOrData
     * @param callable|null $data
     *
     * @return $this
     */
    public function set($keyOrData, $data = null): self
    {
        return $this->bind($keyOrData, $data);
    }

    /**
     * Fetches from container with getContent.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function resolve(string $key)
    {
        return $this->get($key);
    }

    /**
     * Alias for has.
     *
     * @param string  $key
     *
     * @return bool
     */
    public function contains(string $key): bool
    {
        return $this->contains($key);
    }

    /**
     * Allow retrieving container values via magic properties.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Allows the use of isset() to determine if something exists in the container.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * Allows the use of unset() to remove key a key from the container.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset(string $key): void
    {
        $this->remove($key);
    }

    /**
     * Bind a new key or fetch an existing one if no argument is provided..
     * If an argument is provided: Only the first one will be considered and it must be a callable.
     *
     * @param string $key
     * @param array  $data
     *
     * @return mixed
     */
    public function __call(string $key, array $data)
    {
        if (!$data) {
            return $this->get($key);
        }

        return $this->bind($key, $data[0]);
    }
}
