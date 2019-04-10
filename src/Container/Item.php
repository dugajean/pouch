<?php

declare(strict_types=1);

namespace Pouch\Container;

use Closure;
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
     * @var Closure
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
     * @param Closure            $content
     * @param ContainerInterface $container
     * @param bool               $factory
     * @param bool               $resolvedByName
     */
    public function __construct(
        ?string $name,
        Closure $content,
        ContainerInterface $container,
        bool $factory = false,
        bool $resolvedByName = false
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
    public function getName(): string
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
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the raw closure, non-executed.
     *
     * @return Closure
     */
    public function getRaw(): Closure
    {
        return $this->raw;
    }

    /**
     * Returns whether this item can be resolved without typehint and
     * instead uses its name for being resolved.
     *
     * @return bool
     */
    public function isResolvedByName(): bool
    {
        return $this->resolvedByName;
    }

    /**
     * @param bool $resolvableByName
     *
     * @return $this
     */
    public function setResolvedByName(bool $resolvableByName): self
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
    public function setFactoryArgs(...$args): self
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
