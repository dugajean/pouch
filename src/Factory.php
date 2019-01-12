<?php

namespace Pouch;

final class Factory
{
    /**
     * The function to be invoked.
     *
     * @var Callable
     */
    private $callable;

    /**
     * The function's arguments.
     *
     * @var array
     */
    private $args;

    /**
     * Factory constructor.
     *
     * @param \Closure $callable
     * @param array    ...$args
     *
     * @return void
     */
    public function __construct(\Closure $callable, ...$args)
    {
        $this->callable = $callable;
        $this->args = $args;
    }

    /**
     * The function invoke magic method.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return ($this->callable)(...$this->args);
    }

    /**
     * Create a new factory instance.
     *
     * @param callable $callable
     * @param mixed    $newThis
     *
     * @return \Pouch\Factory
     */
    public static function make(Callable $callable, $newThis)
    {
        return new Factory((function ($callable) {
            return $callable($this);
        })->bindTo($newThis), $callable);
    }
}
