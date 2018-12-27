<?php

namespace Pouch\Tests\Unit;

use Pouch\Exceptions\NotFoundException;
use Pouch\Pouch;
use Pouch\Tests\TestCase;
use Pouch\Exceptions\PouchException;

class PouchTest extends TestCase
{
    public function test_storing_data_in_container()
    {
        pouch()->bind('foo', 'FooString');

        $this->assertTrue(pouch()->has('foo'));
    }

    public function test_resolving_data_from_container()
    {
        pouch()->bind('foo', 'FooString');

        $this->assertEquals('FooString', pouch()->resolve('foo'));
    }

    public function test_resolving_data_with_callback()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

        $this->assertEquals('FooString', pouch()->resolve('foo'));
    }

    public function test_resolving_inexistent_key()
    {
        $this->expectException(NotFoundException::class);

        pouch()->resolve('bar');
    }

    public function test_resolving_with_non_string_key()
    {
        $this->expectException(PouchException::class);

        pouch()->bind(5, 'FooString');

        pouch()->resolve(5);

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve('5'));
    }

    public function test_resolving_with_non_string_key_converted()
    {
        pouch()->bind(5, 'FooString');

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve('5'));
    }
}
