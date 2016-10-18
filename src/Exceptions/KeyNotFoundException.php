<?php

namespace Pouch\Exceptions;

class KeyNotFoundException extends \Exception 
{
    public function __construct($message = '')
    {
        $message = $message ? : 'We could not find this key.';
        parent::__construct($message);
    }
}
