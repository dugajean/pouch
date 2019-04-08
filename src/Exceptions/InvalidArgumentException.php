<?php

namespace Pouch\Exceptions;

use \Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;

class InvalidArgumentException extends \Exception implements PsrInvalidArgumentException {}
