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

        $fooObject = new Foo;
        $fooResolvable = Pouch::resolve(Foo::class);

        $this->assertEquals($fooObject->fooFunc1(new Bar, new Baz), $fooResolvable->fooFunc1());
    }

    public function test_automatic_injection_in_foo_constructor()
    {
        Pouch::registerNamespaces('Pouch\Tests\Data');

        $fooObject = new Foo(new Bar);
        $fooResolvable = Pouch::resolve(Foo::class);

        $this->assertEquals($fooObject->testConstructor(), $fooResolvable->testConstructor());
    }
}
