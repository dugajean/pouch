<?php

namespace Pouch\Tests\Data;

use Pouch\Tests\Data\Sub\Baz;

class Foo
{
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
}
