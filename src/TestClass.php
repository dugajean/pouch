<?php

namespace Pouch;

use Dummies\DepClass;

class TestClass
{
    public function whatevs($someOther, DepClass $wow)
    {
        $wow->hi($someOther);
    }
}   
