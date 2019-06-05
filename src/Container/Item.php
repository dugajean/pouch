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
     * Holds the actual content of the item.
     *
     * @var mixed
     */
    private $content;

    /**
     * Will always hold the closure instead of the raw result (if applicable).
     *
     * @var mixed
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
     * @param mixed              $content
     * @param ContainerInterface $container
     * @param bool               $factory
     * @param bool               $resolvedByName
     */
    public function __construct(
        ?string $name,
        $content,
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
    public function getName(): ?string
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
     * Whether or not this item is a factory.
     *
     * @return bool
     */
    public function isFactory(): bool
    {
        return $this->factory;
    }

    /**
     * @param bool $factory
     *
     * @return $this
     */
    public function setFactory(bool $factory): self
    {
        $this->factory = $factory;

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
     * @param string|null $dottedPath If the item's content is a multidimensional array, dot notation can
     *                                be used to fetch a certain part of the array.
     *
     * @return mixed
     */
    public function getContent(string $dottedPath = '')
    {
        if ($this->factory) {
            return ($this->raw)($this->container, $this->factoryArgs);
        }

        if ($this->content instanceof Closure && !$this->initialized) {
            $this->initialized = true;
            return $this->content = ($this->content)($this->container);
        }

        if (is_array($this->content) && strpos($dottedPath, '.') !== false) {
            return $this->getWithDotNotation($dottedPath);
        }

        return $this->content;
    }

    /**
     * String representation of an item.
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode([
            'name' => $this->name,
            'factory' => $this->factory,
            'named' => $this->resolvedByName,
            'initialized' => $this->initialized,
        ]);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string $key
     * @return mixed
     */
    private function getWithDotNotation($key)
    {
        if (is_null($key)) {
            return $this->content;
        }

        if (isset($array[$key])) {
            return $this->content[$key];
        }

        $partialContent = $this->content;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($partialContent) || !array_key_exists($segment, $partialContent)) {
                return $this->content;
            }

            $partialContent = $partialContent[$segment];
        }

        return $partialContent;
    }
}
