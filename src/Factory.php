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
     * @param callable $callable
     * @param array    ...$args
     *
     * @return void
     */
    public function __construct(Callable $callable, ...$args)
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
}
