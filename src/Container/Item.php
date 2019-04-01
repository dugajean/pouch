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
     * @var bool
     */
    private $resolvedByName;

    /**
     * @var ContainerInterface
     */
    private $container;

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
    public function getResolvedByName()
    {
        return $this->resolvedByName;
    }

    /**
     * Returns the contents of the container.
     *
     * @return mixed
     */
    public function getContent()
    {
        if ($this->factory) {
            return ($this->raw)($this->container);
        }

        if (is_callable($this->content)) {
            return $this->content = ($this->content)($this->container);
        }

        return $this->content;
    }
}
