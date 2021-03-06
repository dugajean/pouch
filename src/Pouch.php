<?php

declare(strict_types=1);

namespace Pouch;

use Phar;
use Closure;
use Countable;
use Pouch\Container\Item;
use Pouch\Cache\ApcuCache;
use Pouch\Helpers\ClassTree;
use Pouch\Helpers\AliasTrait;
use Pouch\Helpers\CacheTrait;
use Pouch\Helpers\FactoryTrait;
use Pouch\Container\ItemInterface;
use Pouch\Helpers\HookManager;
use Psr\SimpleCache\CacheInterface;
use Psr\Container\ContainerInterface;
use Pouch\Exceptions\NotFoundException;
use Pouch\Exceptions\InvalidArgumentException;

class Pouch implements ContainerInterface, Countable
{
    use AliasTrait, CacheTrait, FactoryTrait;

    /**
     * Whether this item should be retrievable by only its name and without a typehint.
     * If set to true, you will be able to fetch the value of this item (eg. foo)
     * by simply adding $foo as a constructor/method parameter, with no typehint.
     *
     * @var bool
     */
    protected $named = false;

    /**
     * Store all singletons.
     * 
     * @var array
     */
    protected static $singletons = [];

    /**
     * Store all the data that can be replaced.
     * 
     * @var Item[]
     */
    protected $replaceables = [];

    /**
     * @var ClassTree
     */
    private $hooksActive = false;

    /**
     * Bootstrap pouch.
     *
     * @param string              $rootDir    Path to the app's root (Where composer.json is).
     * @param CacheInterface|null $cacheStore PSR-16 compatible cache store instance. Will be used to speed up
     *                                        Pouch's performance by caching some heavy-ish tasks.
     * @param string|null         $startPath

     *
     * @return void
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function bootstrap(string $rootDir, ?CacheInterface $cacheStore = null, ?string $startPath = null): void
    {
        if (Phar::running()) {
            Phar::interceptFileFuncs();
        }

        ClassTree::bootstrap($rootDir, $startPath, false);

        require_once __DIR__ . '/../helpers.php';

        Pouch::singleton(self::$cacheStoreKey, function () use ($cacheStore) {
            return $cacheStore ?? ApcuCache::factory();
        });

        self::singleton('pouchHookManager', function () {
            return HookManager::factory(pouch());
        });
    }

    /**
     * Insert or return a singleton instance from our container.
     *
     * @param string  $key
     * @param Closure $data
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function singleton(string $key, Closure $data = null)
    {
        if (array_key_exists($key, self::$singletons)) {
            return self::$singletons[$key];
        }

        if ($data === null) {
            throw new NotFoundException("The {$key} key could not be found in the singleton container");
        }

        return self::$singletons[$key] = $data();
    }

    /**
     * Bind a new element to the replaceables.
     *
     * @param  string|array       $keyOrData Can be a string for the key when binding a single thing, but can also
     *                                       be an array with $key => $callable format if providing multiple things to bind.
     * @param  callable|Item|null $data      The data to be bound. Must be provided if $key is a string.
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function bind($keyOrData, $data = null): self
    {
        if (is_array($keyOrData)) {
            foreach ($keyOrData as $key => $callable) {
                $this->bind($key, $callable);
            }
        } else {
            $this->validateData($data);
            $key = (string)$keyOrData;

            if ($this->hooksActive) {
                $this->hooks()->runBeforeSet($key);
            }

            if ($data instanceof ItemInterface) {
                $this->replaceables[$key] = $data->setName($key);
            } else {
                /** @var Closure $data */
                $this->replaceables[$key] = new Item($key, $data, $this, $this->isFactory, $this->named);
            }

            if ($this->hooksActive) {
                $this->hooks()->runAfterSet($key, $this->replaceables[$key]);
            }

            $this->factory(false);
            $this->named(false);
        }

        return $this;
    }

    /**
     * Create an alias key for an existing key.
     *
     * @param string $key
     * @param string $referenceKey
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function alias(string $key, string $referenceKey): self
    {
        $this->replaceables[$key] = $this->item($referenceKey);

        return $this;
    }

    /**
     * Register one or more namespaces for automatic resolution.
     *
     * @param string|string[] $namespaces List of namespaces to be made resolvable.
     *                                    Will go recursively through the namespace.

     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function registerNamespaces($namespaces): self
    {
        foreach ((array)$namespaces as $namespace) {
            $classes = $this->cache($namespace, function () use ($namespace) {
                return ClassTree::unfold($namespace);
            });

            foreach ($classes as $class) {
                $this->bind($class, function () use ($class) {
                    return new Resolvable($class, $this);
                });
            }
        }

        return $this;
    }

    /**
     * Resolve specific key from the replaceables array.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function get($key)
    {
        $this->setFactoryArgs($key);

        if ($this->hooksActive) {
            $this->hooks()->runBeforeGet($key);
        }

        $dot = '';
        if (strpos($key, '.') !== false) {
            [$key, $dot] = explode('.', $key, 2);
        }

        $item = $this->item($key);

        if ($this->hooksActive) {
            $this->hooks()->runAfterGet($key, $item);
        }

        return $item->getContent($dot);
    }

    /**
     * Resolve a key without invoking it if it happens to be a factory.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function raw(string $key): Closure
    {
        if ($this->hooksActive) {
            $this->hooks()->runBeforeGet($key);
        }

        $item = $this->item($key);

        if ($this->hooksActive) {
            $this->hooks()->runAfterGet($key, $item);
        }

        return $item->getRaw();
    }

    /**
     * Returns the item instance for the key.
     *
     * @param string $key
     *
     * @return \Pouch\Container\Item
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function item(string $key): Item
    {
        if (!array_key_exists($key, $this->replaceables)) {
            throw new NotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key];
    }

    /**
     * @param bool|Closure|Item $isNamedOrCallableOrItem
     *
     * @return $this|Item
     */
    public function named($isNamedOrCallableOrItem = true)
    {
        if ($isNamedOrCallableOrItem instanceof Closure) {
            return new Item(null, $isNamedOrCallableOrItem, $this, false, true);
        } elseif ($isNamedOrCallableOrItem instanceof Item) {
            return $isNamedOrCallableOrItem->setResolvedByName(true);
        }

        $this->named = (bool)$isNamedOrCallableOrItem;

        return $this;
    }

    /**
     * See if specific key exists in our replaceables.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->replaceables);
    }

    /**
     * Remove key from the container.
     *
     * @param $key
     *
     * @return $this
     */
    public function remove(string $key): self
    {
        if ($this->has($key)) {
            unset($this->replaceables[$key]);
        }

        return $this;
    }

    /**
     * Returns the hook manager.
     *
     * @param bool $active
     *
     * @return \Pouch\Helpers\HookManager
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function hooks(bool $active = true): HookManager
    {
        $this->hooksActive = $active;

        return self::singleton('pouchHookManager');
    }

    /**
     * String representation of a pouch instance.
     *
     * @return string
     */
    public function __toString(): string
    {
        $containers = [
            'singletons' => self::$singletons,
            'replaceables' => $this->replaceables,
        ];

        return json_encode($containers);
    }

    /**
     * Count elements of the container.
     *
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        return count($this->replaceables);
    }

    /**
     * Throws an exception if the callable argument is not a callable.
     *
     * @param mixed $data
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    protected function validateData($data): void
    {
        $primitives = ['integer', 'float', 'boolean', 'string', 'array'];

        if (!$data instanceof Closure && !$data instanceof Item && !in_array(gettype($data), $primitives)) {
            throw new InvalidArgumentException('The provided argument must be a Closure, Item or a primitive (incl. array)');
        }
    }
}
