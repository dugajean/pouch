<?php

namespace Pouch\Container;

use Psr\Container\ContainerInterface;

final class Item implements ItemInterface
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * Holds the actual content of the item, initially a Closure.
     *
     * @var mixed
     */
    private $content;

    /**
     * Will always hold the callable instead of the raw result.
     *
     * @var Callable
     */
    private $raw;

    /**
     * @var bool
     */
    private $factory;

    /**
     * List of optional args for the factory instantiation.
     *
     * @var array|null
     */
    private $factoryArgs;

    /**
     * @var bool
     */
    private $resolvedByName;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var  bool
     */
    private $initialized = false;

    /**
     * Item constructor.
     *
     * @param string|null        $name
     * @param Callable           $content
     * @param ContainerInterface $container
     * @param bool               $factory
     * @param bool               $resolvedByName
     */
    public function __construct(
        $name,
        Callable $content,
        ContainerInterface $container,
        $factory = false,
        $resolvedByName = false
    )
    {
        $this->name = $name;
        $this->content = $this->raw = $content;
        $this->factory = $factory;
        $this->container = $container;
        $this->resolvedByName = $resolvedByName;
    }

    /**
     * Returns the key of this item.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this item.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Callable
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Returns whether this item can be resolved without typehint and
     * instead uses its name for being resolved.
     *
     * @return bool
     */
    public function isResolvedByName()
    {
        return $this->resolvedByName;
    }

    /**
     * @param bool $resolvableByName
     *
     * @return $this
     */
    public function setResolvedByName($resolvableByName)
    {
        $this->resolvedByName = $resolvableByName;

        return $this;
    }

    /**
     * Set the arguments to instantiate the factory with.
     *
     * @param mixed ...$args
     *
     * @return \Pouch\Container\Item
     */
    public function setFactoryArgs(...$args)
    {
        $this->factoryArgs = $args;

        return $this;
    }

    /**
     * Returns the contents of the container.
     *
     * @return mixed
     */
    public function getContent()
    {
        if ($this->factory) {
            return ($this->raw)($this->container, $this->factoryArgs);
        }

        if (is_callable($this->content) && !$this->initialized) {
            $this->initialized = true;
            return $this->content = ($this->content)($this->container);
        }

        return $this->content;
    }
}
