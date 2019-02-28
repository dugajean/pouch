<?php

namespace Pouch\Tests\Data;

class Bar
{
    public function barFunc1()
    {
        return 'BarFunc1';
    }

    public function pouchDependency(\Pouch\FancyDateTime $dateTime)
    {
        return $dateTime->getContent()->getTimestamp();
    }
}
