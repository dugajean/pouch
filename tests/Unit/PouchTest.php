<?php

namespace Pouch\Tests\Unit;

use Pouch\Pouch;
use Pouch\Tests\TestCase;
use Pouch\Exceptions\InvalidTypeException;
use Pouch\Exceptions\KeyNotFoundException;

class PouchTest extends TestCase
{
    public function test_storing_data_in_container()
    {
        Pouch::bind('foo', 'FooString');

        $this->assertTrue(Pouch::has('foo'));
    }

    public function test_resolving_data_from_container()
    {
        Pouch::bind('foo', 'FooString');

        $this->assertEquals('FooString', Pouch::resolve('foo'));
    }

    public function test_resolving_data_with_callback()
    {
        Pouch::bind('foo', function () {
            return 'FooString';
        });

        $this->assertEquals('FooString', Pouch::resolve('foo'));
    }

    public function test_resolving_inexistent_key()
    {
        $this->expectException(KeyNotFoundException::class);

        Pouch::resolve('bar');
    }

    public function test_resolving_with_non_string_key()
    {
        $this->expectException(InvalidTypeException::class);

        Pouch::bind(5, 'FooString');

        Pouch::resolve(5);

        $this->assertTrue(Pouch::has('5'));
        $this->assertEquals('FooString', Pouch::resolve('5'));
    }

    public function test_resolving_with_non_string_key_converted()
    {
        Pouch::bind(5, 'FooString');

        $this->assertTrue(Pouch::has('5'));
        $this->assertEquals('FooString', Pouch::resolve('5'));
    }
}
