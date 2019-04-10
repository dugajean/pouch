<?php

declare(strict_types=1);

namespace Pouch\Helpers;

use Closure;
use Pouch\Container\Item;

trait FactoryTrait
{
    /**
     * Whether a bind will be a factory.
     *
     * @var bool
     */
    protected $isFactory = false;

    /**
     * Holds the data to be passed as args to the factory.
     *
     * @var array
     */
    protected $factoryArgs;

    /**
     * Set factory for upcoming bind or create a factory callable.
     *
     * @param bool|Closure $isFactoryOrCallable
     *
     * @return $this|Item
     */
    public function factory($isFactoryOrCallable = true)
    {
        if ($isFactoryOrCallable instanceof Closure) {
            /** @var \Pouch\Pouch $this */
            return new Item(null, $isFactoryOrCallable, $this, true);
        }

        $this->isFactory = (bool)$isFactoryOrCallable;

        return $this;
    }

    /**
     * Set the args to construct the factory with.
     *
     * @param mixed ...$args
     *
     * @return $this
     */
    public function withArgs(...$args): self
    {
        $this->factoryArgs = $args;

        return $this;
    }
}
