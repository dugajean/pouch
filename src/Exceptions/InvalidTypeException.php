<?php

namespace Pouch\Exceptions;

class InvalidTypeException extends \Exception 
{
    public function __construct($message = '')
    {
        $message = $message ? : 'Invalid argument provided.';
        parent::__construct($message);
    }
}
