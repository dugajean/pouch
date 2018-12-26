<?php

$vendor = __DIR__.'/../vendor/';

if (!file_exists($vendor)) {
    die('Please run composer install before triggering the tests.');
}

require_once $vendor.'autoload.php';

Pouch\Pouch::bootstrap(__DIR__.'/../');
Pouch\ClassTree::loadDev(true);
