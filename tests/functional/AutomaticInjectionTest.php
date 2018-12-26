<?php

namespace Pouch\Tests\Functional;

use Pouch\Pouch;
use Pouch\Tests\TestCase;
use Pouch\Tests\Data\Bar;
use Pouch\Tests\Data\Foo;
use Pouch\Tests\Data\Sub\Baz;

class AutomaticInjectionTest extends TestCase
{
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

    public function test_automatic_injection_in_foo_constructor()
    {

    }

    public function test_providing_parameters_to_autoinject_class()
    {

    }
}
