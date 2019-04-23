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
     * @param bool|Closure|Item $isFactoryOrCallableOrItem
     *
     * @return $this|Item
     */
    public function factory($isFactoryOrCallableOrItem = true)
    {
        if ($isFactoryOrCallableOrItem instanceof Closure) {
            /** @var \Pouch\Pouch $this */
            return new Item(null, $isFactoryOrCallableOrItem, $this, true);
        } elseif ($isFactoryOrCallableOrItem instanceof Item) {
            return $isFactoryOrCallableOrItem->setFactory(true);
        }

        $this->isFactory = (bool)$isFactoryOrCallableOrItem;

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

    /**
     * Set the arguments during fetch-time.
     *
     * @param string $key
     *
     * @return $this
     */
    protected function setFactoryArgs(string $key): self
    {
        if ($this->factoryArgs) {
            /** @var Item $item */
            $item = $this->item($key);
            $item->setFactoryArgs(...$this->factoryArgs);

            $this->factoryArgs = null;
        }

        return $this;
    }
}
