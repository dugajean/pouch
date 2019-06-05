<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Closure;
use Pouch\Container\Item;
use Pouch\Pouch;

final class HookManager
{
    /**
     * @var array
     */
    private $hooks = [
        'before' => ['get' => [], 'set' => []],
        'after' => ['get' => [], 'set' => []],
    ];

    /**
     * Returns an instance of self.
     *
     * @return \Pouch\Helpers\HookManager
     */
    public static function factory()
    {
        return new self;
    }

    /**
     * @param array   $whenKeys
     * @param Closure $callback
     *
     * @return $this
     */
    public function addBeforeGet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('before', 'get', $callback, $whenKeys);
    }

    /**
     * @param array   $whenKeys
     * @param Closure $callback
     *
     * @return $this
     */
    public function addAfterGet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('after', 'get', $callback, $whenKeys);
    }

    /**
     * @param array   $whenKeys
     * @param Closure $callback
     *
     * @return $this
     */
    public function addBeforeSet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('before', 'set', $callback, $whenKeys);
    }

    /**
     * @param array   $whenKeys
     * @param Closure $callback
     *
     * @return $this
     */
    public function addAfterSet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('after', 'set', $callback, $whenKeys);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function addBeforeEachGet(Closure $callback)
    {
        return $this->addHook('before', 'get', $callback);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function addAfterEachGet(Closure $callback)
    {
        return $this->addHook('after', 'get', $callback);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function addBeforeEachSet(Closure $callback)
    {
        return $this->addHook('before', 'set', $callback);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function addAfterEachSet(Closure $callback)
    {
        return $this->addHook('after', 'set', $callback);
    }

    /**
     * @param Pouch  $pouch
     * @param string $currentKey
     *
     * @return $this
     */
    public function runBeforeGet(Pouch $pouch, string $currentKey)
    {
        return $this->runHooks('before', 'get', $pouch, $currentKey);
    }

    /**
     * @param Pouch  $pouch
     * @param string $currentKey
     * @param Item   $item
     *
     * @return $this
     */
    public function runAfterGet(Pouch $pouch, string $currentKey, Item $item)
    {
        return $this->runHooks('after', 'get', $pouch, $currentKey, $item);
    }

    /**
     * @param Pouch  $pouch
     * @param string $currentKey
     *
     * @return $this
     */
    public function runBeforeSet(Pouch $pouch, string $currentKey)
    {
        return $this->runHooks('before', 'set', $pouch, $currentKey);
    }

    /**
     * @param Pouch  $pouch
     * @param string $currentKey
     * @param Item   $item
     *
     * @return $this
     */
    public function runAfterSet(Pouch $pouch, string $currentKey, Item $item)
    {
        return $this->runHooks('after', 'set', $pouch, $currentKey, $item);
    }

    /**
     * Register any hooks to the manager.
     *
     * @param string  $beforeOrAfter
     * @param string  $getOrSet
     * @param Closure $callback
     * @param array   $whenKeys Array of keys that can be found in the container.
     *                          The callback will only be executed if this key is being set or retrieved.
     *
     * @return $this
     */
    private function addHook(string $beforeOrAfter, string $getOrSet, Closure $callback, array $whenKeys = []): self
    {
        $this->hooks[$beforeOrAfter][$getOrSet] = $callback;

        if (count($whenKeys) === 0) {
            $this->hooks[$beforeOrAfter][$getOrSet]['__trigger_keys'] = $whenKeys;
        }

        return $this;
    }

    /**
     * Run the chosen hooks.
     *
     * @param string    $beforeOrAfter
     * @param string    $getOrSet
     * @param Pouch     $pouch
     * @param string    $currentKey
     * @param Item|null $item
     *
     * @return $this
     */
    private function runHooks(
        string $beforeOrAfter,
        string $getOrSet,
        Pouch $pouch,
        string $currentKey,
        ?Item $item = null
    ): self {
        foreach ($this->hooks[$beforeOrAfter][$getOrSet] as $hook) {
            if (isset($hook['__trigger_keys']) && !in_array($currentKey, $hook['__trigger_keys'])) {
                continue;
            }

            $hook($pouch, $currentKey, $item);
        }

        return $this;
    }
}
