<?php

namespace Pouch\Tests\Unit;

use Pouch\ClassTree;
use Pouch\Exceptions\NamespaceNotFoundException;
use Pouch\Tests\TestCase;
use Pouch\Tests\Data\Bar;
use Pouch\Tests\Data\Foo;
use Pouch\Tests\Data\Sub\Baz;

class ClassTreeTest extends TestCase
{
    public function test_fetching_namespace_list()
    {
        $classes = ClassTree::getClassesInNamespace('Pouch\Tests\Data');

        $this->assertTrue(in_array(Foo::class, $classes));
        $this->assertTrue(in_array(Bar::class, $classes));
        $this->assertTrue(in_array(Baz::class, $classes));
    }

    public function test_fetching_with_invalid_namespace()
    {
        $this->expectException(NamespaceNotFoundException::class);

        ClassTree::getClassesInNamespace('Foo\Bar');
    }
}