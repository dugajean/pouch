<?php

namespace Pouch\Tests;

use Pouch\Pouch;
use Pouch\ClassTree;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    protected function setUp()
    {
        parent::setUp();

        Pouch::bootstrap(__DIR__.'/..');
        ClassTree::loadDev(true);
    }
}
