<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Closure;
use Pouch\Container\Item;
use Pouch\Exceptions\NotFoundException;
use Pouch\Exceptions\InvalidArgumentException;

/**
 * @method self addBeforeGet(string $key, Closure $callback)
 * @method self addAfterGet(string $key, Closure $callback)
 * @method self addBeforeSet(string $key, Closure $callback)
 * @method self addAfterSet(string $key, Closure $callback)
 * @method self addBeforeEachGet(Closure $callback)
 * @method self addAfterEachGet(Closure $callback)
 * @method self addBeforeEachSet(Closure $callback)
 * @method self addAfterEachSet(Closure $callback)
 * @method self runBeforeGet(string $currentKey)
 * @method self runAfterGet(string $currentKey, Item $item)
 * @method self runBeforeSet(string $currentKey)
 * @method self runAfterSet(string $currentKey, Item $item)
 */
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
     * All calls to be routed via this method. See docs on top of class.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return \Pouch\Helpers\HookManager
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function __call(string $name, array $arguments)
    {
        $addOrRun = substr($name, 0, 3);

        if ($addOrRun === 'add' || $addOrRun === 'run') {
            $when = $this->determineWhen($addOrRun, $name);
            $getOrSet = $this->determineGetSet($name);

            if ($addOrRun === 'add') {
                return $this->handleAdd($when, $getOrSet, $arguments);
            } else {
                return $this->handleRun($when, $getOrSet, $arguments);
            }
        }
    }

    /**
     * Determine whether we're dealing with "before" or "after".
     *
     * @param string $addOrRun
     * @param string $methodName
     *
     * @return string
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private function determineWhen(string $addOrRun, string $methodName): string
    {
        if (preg_match("/{$addOrRun}(.*?)/", $methodName, $match) === 1) {
            return strtolower($match[1]);
        }

        $this->throwException($methodName);
    }

    /**
     * Determine whether we are using "get" or "set".
     *
     * @param string $methodName
     *
     * @return string
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private function determineGetSet(string $methodName)
    {
        $substring = strtolower(substr($methodName, -3));

        if ($substring === 'get' || $substring === 'set') {
            return $substring;
        }

        $this->throwException($methodName);
    }

    /**
     * Throw an exception if the method isn't found.
     *
     * @param string $methodName
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    private function throwException(string $methodName): void
    {
        throw new NotFoundException("The method '$methodName' cannot be found in the HookManager class");
    }

    /**
     * Handle the adding/registering part of the hooks.
     *
     * @param string $when
     * @param string $getOrSet
     * @param array  $arguments
     *
     * @return \Pouch\Helpers\HookManager
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    private function handleAdd(string $when, string $getOrSet, array $arguments): self
    {
        if (count($arguments) === 0 || !$arguments[0] instanceof Closure) {
            throw new InvalidArgumentException('When setting a hook the first argument must be a Closure');
        }

        if (isset($arguments[1]) && is_string($arguments[1])) {
            $this->hooks[$when][$getOrSet][$arguments[1]] = $arguments[0];
        } else {
            $this->hooks[$when][$getOrSet][] = $arguments[0];
        }

        return $this;
    }

    /**
     * Handle the execution part of the hooks.
     *
     * @param string $when
     * @param string $getOrSet
     * @param array  $arguments
     *
     * @return \Pouch\Helpers\HookManager
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    private function handleRun(string $when, string $getOrSet, array $arguments): self
    {
        if (count($arguments) === 0) {
            throw new InvalidArgumentException;
        }

        foreach ($this->hooks[$when][$getOrSet] as $whenHook => $hook) {
            if (is_string($whenHook) && $arguments[0] !== $whenHook) {
                continue;
            }

            $hook($this, ...$arguments);
        }

        return $this;
    }
}
