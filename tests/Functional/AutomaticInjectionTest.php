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
        pouch()->registerNamespaces('Pouch\Tests\Data');

        $this->assertTrue(pouch()->has(Foo::class));
        $this->assertTrue(pouch()->has(Bar::class));
        $this->assertTrue(pouch()->has(Baz::class));
    }

    public function test_automatic_injection_in_foo_class()
    {
        pouch()->registerNamespaces('Pouch\Tests\Data');

        $fooObject = new Foo;
        $fooResolvable = pouch()->resolve(Foo::class);

        $this->assertEquals($fooObject->fooFunc1(new Bar, new Baz), $fooResolvable->fooFunc1());
    }

    public function test_automatic_injection_in_foo_constructor()
    {
        pouch()->registerNamespaces('Pouch\Tests\Data');

        $fooObject = new Foo(new Bar);
        $fooResolvable = pouch()->resolve(Foo::class);

        $this->assertEquals($fooObject->testConstructor(), $fooResolvable->testConstructor());
    }

    public function test_pouch_container_value_injecting()
    {
        pouch()->bind('FancyDateTime', function ($pouch) {
            return new \DateTime;
        });

        pouch()->registerNamespaces('Pouch\Tests\Data');

        $fooResolvable = pouch()->resolve(Foo::class);

        $this->assertEquals(time(), $fooResolvable->pouchDependency());
    }

    public function test_container_injecting_from_new_pouch_instance()
    {
        $expected = 'Foo which is fancy!';

        $pouch = new Pouch;
        $pouch->bind('FancyFoo', function ($pouch) use ($expected) {
            return $expected;
        });

        $pouch->registerNamespaces('Pouch\Tests\Data');

        $fooResolvable = $pouch->resolve(Foo::class);

        $this->assertEquals($expected, $fooResolvable->fancyFooExample());
    }

    public function test_container_injecting_with_complex_namespace()
    {
        $this->markTestIncomplete('TODO');
    }
}
