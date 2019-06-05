<?php

namespace Pouch\Tests\Unit;

use Pouch\Tests\TestCase;
use Pouch\Tests\Data\Bar;
use Pouch\Tests\Data\Foo;
use Pouch\Helpers\ClassTree;
use Pouch\Tests\Data\Sub\Baz;
use Pouch\Exceptions\NotFoundException;

class ClassTreeTest extends TestCase
{
    public function test_fetching_namespace_list_with_composer()
    {
        $classes = ClassTree::unfold('Pouch\Tests\Data');

        $this->assertTrue(in_array(Foo::class, $classes));
        $this->assertTrue(in_array(Bar::class, $classes));
        $this->assertTrue(in_array(Baz::class, $classes));
    }

    public function test_fetching_with_invalid_namespace_with_composer()
    {
        $this->expectException(NotFoundException::class);

        ClassTree::unfold('Foo\Bar');
    }

    public function test_fetching_namespace_list_without_composer()
    {
        ClassTree::$startPath = 'tests/Data';
        
        $classes = ClassTree::unfold('Pouch\Tests\Data');

        $this->assertTrue(in_array(Foo::class, $classes));
        $this->assertTrue(in_array(Bar::class, $classes));
        $this->assertTrue(in_array(Baz::class, $classes));
    }
}
