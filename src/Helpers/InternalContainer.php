<?php

namespace Pouch\Helpers;

interface InternalContainer
{
    /**
     * Returns the class name of the container.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the contents of the container.
     *
     * @return mixed
     */
    public function getContent();
}
