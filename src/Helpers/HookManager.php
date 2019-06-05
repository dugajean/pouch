<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Closure;
use Pouch\Pouch;
use Pouch\Container\Item;

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
     * @var \Pouch\Pouch
     */
    private $pouch;

    /**
     * Returns an instance of self.
     *
     * @param \Pouch\Pouch $pouch
     *
     * @return self
     */
    public static function factory(Pouch $pouch): self
    {
        return new self($pouch);
    }

    /**
     * HookManager constructor - Singleton.
     *
     * @param \Pouch\Pouch $pouch
     */
    private function __construct(Pouch $pouch)
    {
        $this->pouch = $pouch;
    }

    /**
     * @param string[] $whenKeys
     * @param Closure  $callback
     *
     * @return $this
     */
    public function addBeforeGet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('before', 'get', $callback, $whenKeys);
    }

    /**
     * @param string[] $whenKeys
     * @param Closure  $callback
     *
     * @return $this
     */
    public function addAfterGet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('after', 'get', $callback, $whenKeys);
    }

    /**
     * @param string[] $whenKeys
     * @param Closure  $callback
     *
     * @return $this
     */
    public function addBeforeSet(array $whenKeys, Closure $callback): self
    {
        return $this->addHook('before', 'set', $callback, $whenKeys);
    }

    /**
     * @param string[] $whenKeys
     * @param Closure  $callback
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
     * @param string $currentKey
     *
     * @return $this
     */
    public function runBeforeGet(string $currentKey)
    {
        return $this->runHooks('before', 'get', $currentKey);
    }

    /**
     * @param string $currentKey
     * @param Item   $item
     *
     * @return $this
     */
    public function runAfterGet(string $currentKey, Item $item)
    {
        return $this->runHooks('after', 'get', $currentKey, $item);
    }

    /**
     * @param string $currentKey
     *
     * @return $this
     */
    public function runBeforeSet(string $currentKey)
    {
        return $this->runHooks('before', 'set', $currentKey);
    }

    /**
     * @param string $currentKey
     * @param Item   $item
     *
     * @return $this
     */
    public function runAfterSet(string $currentKey, Item $item)
    {
        return $this->runHooks('after', 'set', $currentKey, $item);
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
        $this->hooks[$beforeOrAfter][$getOrSet][] = $callback;

        if (count($whenKeys) !== 0) {
            $this->hooks[$beforeOrAfter][$getOrSet]['__trigger_keys'] = $whenKeys;
        }

        return $this;
    }

    /**
     * Run the chosen hooks.
     *
     * @param string    $beforeOrAfter
     * @param string    $getOrSet
     * @param string    $currentKey
     * @param Item|null $item
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private function runHooks(
        string $beforeOrAfter,
        string $getOrSet,
        string $currentKey,
        ?Item $item = null
    ): self {
        foreach ($this->hooks[$beforeOrAfter][$getOrSet] as $hook) {
            if (
                array_key_exists('__trigger_keys', $hook)
                && !in_array($currentKey, $hook['__trigger_keys'])
            ) {
                continue;
            }

            if ($hook instanceof Closure) {
                $hook($currentKey, $item);
                $this->pouch->hooks();
            }
        }

        return $this;
    }
}
