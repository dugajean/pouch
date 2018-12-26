<?php

namespace Pouch\Tests\Unit;

use Pouch\Exceptions\InvalidTypeException;
use Pouch\Exceptions\MethodNotFoundException;
use Pouch\Resolvable;
use Pouch\Tests\TestCase;
use Pouch\Tests\Data\Foo;

class ResolvableTest extends TestCase
{
    private $resolvable;

    protected function setUp()
    {
        parent::setUp();

        $this->resolvable = new Resolvable;
    }

    public function test_making_new_resolvable_with_object()
    {
        $fooObject = new Foo;
        $fooResolvable = $this->resolvable->make(new Foo);

        $this->assertEquals(Foo::class, $fooResolvable->getType());
        $this->assertEquals($fooObject->simpleMethod(), $fooResolvable->simpleMethod());
    }

    public function test_making_new_resolvable_from_class_name_string()
    {
        $fooObject = new Foo;
        $fooResolvable = $this->resolvable->make(Foo::class);

        $this->assertEquals(Foo::class, $fooResolvable->getType());
        $this->assertEquals($fooObject->simpleMethod(), $fooResolvable->simpleMethod());
    }

    public function test_calling_inexistent_method_in_resolvable()
    {
        $this->expectException(MethodNotFoundException::class);

        $fooResolvable = $this->resolvable->make(new Foo);

        $fooResolvable->badFoo();
    }

    public function test_using_inexistent_class_name()
    {
        $this->expectException(InvalidTypeException::class);

        $this->resolvable->make('FooBar\Foo');
    }
}
