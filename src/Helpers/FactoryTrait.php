<?php

declare(strict_types = 1);

namespace Pouch\Helpers;

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

    /**
     * Set factory for upcoming bind or create a factory callable.
     *
     * @param bool|callable $isFactoryOrCallable
     *
     * @return $this|Item
     */
    public function factory($isFactoryOrCallable = true)
    {
        if (is_callable($isFactoryOrCallable)) {
            return new Item(null, $isFactoryOrCallable, $this, true);
        }

        $this->isFactory = (bool)$isFactoryOrCallable;

        return $this;
    }
}
