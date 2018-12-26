<?php

namespace Pouch\Tests\Unit;

use Pouch\Pouch;
use Pouch\Tests\Data\Bar;
use Pouch\Tests\Data\Foo;
use Pouch\Tests\TestCase;
use Pouch\Tests\Data\Sub\Baz;
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

    public function test_registering_namespace()
    {
        Pouch::registerNamespaces('Pouch\Tests\Data');

        $this->assertTrue(Pouch::has(Foo::class));
        $this->assertTrue(Pouch::has(Bar::class));
        $this->assertTrue(Pouch::has(Baz::class));
    }
    
    public function test_automatic_injection_in_foo_class()
    {
        Pouch::registerNamespaces('Pouch\Tests\Data');

        $foo = Pouch::resolve(Foo::class);

        $this->assertEquals('FooFunc1BarFunc1BazFunc1', $foo->fooFunc1());
    }
}
