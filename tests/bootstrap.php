<?php

$vendor = dirname(dirname(__FILE__)).'/vendor/';

if (!realpath($vendor)) {
    die('Please run `composer install` before triggering the tests.');
}

require_once $vendor.'autoload.php';

Pouch\Pouch::bootstrap(dirname(dirname(__FILE__)));
Pouch\ClassTree::loadDev(true);
