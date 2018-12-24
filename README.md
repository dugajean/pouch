# Pouch
Tiny IoC Container with automatic resolution for your PHP project.

## Requirements

- PHP 7.2
- `ext-json`
- The use of the PSR-4 standard with Composer

## Install

Via Composer

```bash
$ composer require dugajean/pouch
```

## Usage

You may register your whole `src/` folder with this package in order to enable automatic resolution anywhere:

```php
<?php

use Pouch\Pouch;

Pouch::bootstrap(__DIR__);
Pouch::registerNamespace('Foo'); // Foo corresponds to src/
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

You may also manually bind data to the container using `Pouch::bind()` and also resolve anything from the container using `Pouch::resolve($key)`.

## Testing

```bash
$ vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License
Pouch is released under [the MIT License](LICENSE).
