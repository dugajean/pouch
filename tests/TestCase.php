<?php

namespace Pouch\Tests;

use Pouch\Pouch;
use Pouch\ClassTree;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $containerJson = json_decode((string)pouch(), true);

        foreach ($containerJson['replaceables'] as $key => $value) {
            pouch()->remove($key);
        }

        parent::tearDown();
    }
}
