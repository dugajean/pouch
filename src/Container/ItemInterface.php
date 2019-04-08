<?php

declare(strict_types = 1);

namespace Pouch\Container;

interface ItemInterface
{
    /**
     * Returns the class name of the container.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the contents of the container.
     *
     * @return mixed
     */
    public function getContent();
}
