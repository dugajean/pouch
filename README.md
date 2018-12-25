# Pouch

[![Build Status](https://travis-ci.org/dugajean/pouch.svg?branch=master)](https://travis-ci.org/dugajean/pouch) 
[![Latest Stable Version](https://poser.pugx.org/dugajean/pouch/v/stable)](https://packagist.org/packages/dugajean/pouch) 
[![Total Downloads](https://poser.pugx.org/dugajean/pouch/downloads)](https://packagist.org/packages/dugajean/pouch) 
[![License](https://poser.pugx.org/dugajean/pouch/license)](https://packagist.org/packages/dugajean/pouch) 

Tiny IoC Container with automatic resolution for your PHP project.

## Requirements

- PHP 5.6
- `ext-json`
- The use of the PSR-4 standard with Composer

## Install

Via Composer

```bash
$ composer require dugajean/pouch
```

## Usage

You may register your whole `src/` folder with this package in order to enable automatic resolution everywhere within the namespace

```php
<?php

use Pouch\Pouch;

Pouch::bootstrap(__DIR__);
Pouch::registerNamespaces('Foo'); // Foo corresponds to src/
```

You may now just typehint to the objects your method requires and it will be automatically resolved for you.

```php
<?php

namespace Foo;

class Bar
{
    public function doSomething(Baz $baz)
    {
        $baz->doSomethingElse();
    }
}

class Baz
{
    public function doSomethingElse()
    {
        echo 'From Baz!';
    }
}
```

--------

If some classes within the namespace need parameters and further setup (such as a constructor), you should provide a second parameter to `Pouch::registerNamespaces`, which allows you to override anything that's about to be created automatically.

```php
<?php

use Foo\Baz;
use Pouch\Pouch;

Pouch::bootstrap(__DIR__);
Pouch::registerNamespaces('Foo', [
    Baz::class => function () {
        return Baz('Baz Name');
    }
]);
```

```php
<?php

namespace Foo;

class Bar
{
    public function doSomething(Baz $baz)
    {
        $baz->doSomethingElse();
        
        echo $baz->name;
    }
}

class Baz
{
    public $name;
    
    public function __construct($name) 
    {
        $this->name = $name;
    }
    
    public function doSomethingElse()
    {
        echo 'From Baz!';
    }
}
```

Now all `Baz` instances will be valid and any time it's seeked via a parameter the instance with the name _Baz Name_ will be provided.

--------

Constructor object arguments will also be automatically injected, unless manually overrided like above.

You can always manually bind data to the container using `Pouch::bind($key, $dataClosure)` and also resolve anything from the container using `Pouch::resolve($key)`.

## Testing

```bash
$ vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License
Pouch is released under [the MIT License](LICENSE).
