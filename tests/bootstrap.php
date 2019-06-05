<?php

use Pouch\Exceptions\NotFoundException;

$vendor = __DIR__.'/../vendor/';

if (!file_exists($vendor)) {
    die('Please run composer install before triggering the tests.');
}

require_once $vendor.'autoload.php';

try {
    Pouch\Pouch::bootstrap(__DIR__ . '/../');
    Pouch\Helpers\ClassTree::$loadDev = true;
} catch (NotFoundException $e) {
    die('Could not bootstrap Pouch properly: ' . $e->getMessage());
}
