<?php

namespace Pouch\Tests\Data;

use Pouch\Tests\Data\Sub\Baz;

class Foo
{
    private $bar;

    public function __construct(Bar $bar = null)
    {
        $this->bar = $bar;
    }

    public function testConstructor()
    {
        return 'FromFoo:'.$this->bar->barFunc1();
    }

    public function fooFunc1(Bar $bar, Baz $baz)
    {
        $barStr = $bar->barFunc1();
        $bazStr = $baz->bazFunc1();

        return "FooFunc1{$barStr}{$bazStr}";
    }

    public function simpleMethod()
    {
        return 'simpleMethod';
    }

    public function pouchDependency(\Pouch\FancyDateTime $dateTime)
    {
        return $dateTime->getContent()->getTimestamp();
    }

    public function fancyFooExample(\Pouch\FancyFoo $foo)
    {
        return $foo->getContent();
    }

    public function fancyFooExampleLong(\Pouch\FancyFoo\TheFoo\BarBaz $foo)
    {
        return $foo->getContent();
    }

    public function namedParamNoTypeHint($foo)
    {
        return $foo;
    }
}
