# Pouch

[![Build Status](https://travis-ci.org/dugajean/pouch.svg?branch=master)](https://travis-ci.org/dugajean/pouch) 
[![Latest Stable Version](https://poser.pugx.org/dugajean/pouch/v/stable)](https://packagist.org/packages/dugajean/pouch) 
[![Total Downloads](https://poser.pugx.org/dugajean/pouch/downloads)](https://packagist.org/packages/dugajean/pouch) 
[![License](https://poser.pugx.org/dugajean/pouch/license)](https://packagist.org/packages/dugajean/pouch) 

Tiny IoC container with awesome autowiring & more - for your PHP project.

## Requirements

- PHP 7.1+
- [PSR-4 standard with Composer](https://getcomposer.org/doc/04-schema.md#psr-4)

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

// ...

pouch()->registerNamespaces('Foo'); // Foo corresponds to src/
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

Constructor object arguments will also be automatically injected.

You can always manually bind data to the container using `pouch()->bind($key, $dataClosure)` and also resolve anything from the container using `pouch()->get($key)`.

**Read the [wiki](https://github.com/dugajean/pouch/wiki) and the [API docs](https://dugajean.github.io/pouch/) for further information.**

## Testing

```bash
$ vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License
Pouch is released under [the MIT License](LICENSE).

## Support on Beerpay
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/dugajean/pouch/badge.svg?style=beer-square)](https://beerpay.io/dugajean/pouch)  [![Beerpay](https://beerpay.io/dugajean/pouch/make-wish.svg?style=flat-square)](https://beerpay.io/dugajean/pouch?focus=wish)
