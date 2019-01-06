<?php

namespace Pouch\Tests\Unit;

use Pouch\Pouch;
use Pouch\Tests\TestCase;
use Pouch\Exceptions\PouchException;
use Pouch\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class PouchTest extends TestCase
{
    public function test_storing_data_in_container()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

        $this->assertTrue(pouch()->has('foo'));
    }

    public function test_resolving_data_from_container()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

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

        pouch()->bind(5, function () {
            return 'FooString';
        });

        pouch()->resolve(5);

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve('5'));
    }

    public function test_resolving_with_non_string_key_converted()
    {
        pouch()->bind(5, function () {
            return 'FooString';
        });

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve('5'));
    }

    public function test_pouch_callback_argument()
    {
        $expected = 'FooBar';

        pouch()->bind('foo', function () {
            return 'Foo';
        });

        pouch()->bind('foobar', function (ContainerInterface $pouch) {
            return $pouch->get('foo').'Bar';
        });

        $this->assertEquals($expected, pouch()->get('foobar'));
    }

    public function test_pouch_methods_with_static_calls()
    {
        $this->markTestSkipped('Does not pass until PHP 7.3.1');

        Pouch::bind('foo', function () {
            return 'Foo';
        });

        $this->assertEquals('Foo', Pouch::resolve('foo'));
    }

    public function test_using_magic_props_to_get_container_value()
    {
        pouch()->bind('foo', function () {
           return 'Foo';
        });

        $this->assertEquals('Foo', pouch()->foo);
        $this->assertTrue(isset(pouch()->foo));
    }

    public function test_removing_key_from_container()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        $this->assertTrue(pouch()->has('foo'));

        pouch()->remove('foo');

        $this->assertFalse(pouch()->has('foo'));
    }

    public function test_binding_multiple_values()
    {
        pouch()->bind([
            'foo' => function () {
                return 'Foo';
            },
            'bar' => function ($pouch) {
                return $pouch->resolve('foo').'Bar';
            },
            'baz' => function ($pouch) {
                return $pouch->resolve('bar').'Baz';
            }
        ]);

        $this->assertTrue(pouch()->has('foo'));
        $this->assertTrue(pouch()->has('bar'));
        $this->assertTrue(pouch()->has('baz'));
        $this->assertEquals('Foo', pouch()->resolve('foo'));
        $this->assertEquals('FooBar', pouch()->resolve('bar'));
        $this->assertEquals('FooBarBaz', pouch()->resolve('baz'));
    }

    public function test_multibind_with_invalid_value()
    {
        $this->expectException(PouchException::class);

        pouch()->bind(['foo' => 'Foo']);
    }

    public function test_single_bind_with_no_data_argument()
    {
        $this->expectException(PouchException::class);

        pouch()->bind('foo');
    }
}
