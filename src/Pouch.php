<?php

namespace Pouch;

use Pouch\Container\Item;
use Pouch\Cache\Cacheable;
use Pouch\Helpers\ClassTree;
use Pouch\Container\ItemInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Container\ContainerInterface;
use Pouch\Exceptions\NotFoundException;
use Pouch\Exceptions\InvalidArgumentException;

class Pouch implements ContainerInterface
{
    use Cacheable;

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
     * Whether a bind will be a factory.
     *
     * @var bool
     */
    protected $isFactory = false;

    /**
     * Bootstrap pouch.
     *
     * @param string              $rootDir     Path to the app's root (Where composer.json is).
     * @param CacheInterface|null $cacheStore  PSR-16 compatible cache store instance. Will be used to speed up
     *                                         Pouch's performance by caching some heavy-ish tasks.
     *
     * @return void
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function bootstrap($rootDir, CacheInterface $cacheStore = null)
    {
        ClassTree::setRoot($rootDir);
        
        self::initCache($cacheStore);

        require __DIR__.'/../src/Helpers/functions.php';
    }

    /**
     * Insert or return a singleton instance from our container.
     *
     * @param string   $key
     * @param callable $data
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public static function singleton($key, Callable $data = null)
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
     * @param bool                $named     Whether this item should be retrievable by only its name and without a typehint.
     *                                       If set to true, you will be able to fetch the value of this item (eg. foo)
     *                                       by simply adding $foo as a constructor/method parameter, with no typehint.
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function bind($keyOrData, $data = null, $named = false)
    {
        if (is_array($keyOrData)) {
            foreach ($keyOrData as $key => $callable) {
                $this->bind($key, $callable, $named);
            }
        } else {
            $key = (string)$keyOrData;

            if ($data instanceof ItemInterface) {
                $this->replaceables[$key] = $data->setName($key)->setResolvedByName($named);
            } else {
                $this->replaceables[$key] = new Item((string)$keyOrData, $data, $this, $this->isFactory, $named);
            }

            $this->factory(false);
        }

        return $this;
    }

    /**
     * Alias for bind.
     *
     * @param string|array  $keyOrData
     * @param callable|null $data
     * @param bool          $resolveByName
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function register($keyOrData, $data = null, $resolveByName = false)
    {
        return $this->bind($keyOrData, $data, $resolveByName);
    }

    /**
     * Register one or more namespaces for automatic resolution.
     *
     * @param string|string[] $namespaces List of namespaces to be made resolvable.
     *                                    Will go recursively through the namespace.
     * @param array           $overriders Overriders can be used to hook into the process of fetching
     *                                    namespace paths and allow you to replace the minimal instantiation
     *                                    process (which is a simple 'new Class' call to something specific
     *                                    for those classes (e.g. provide constructor parameters etc.).
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function registerNamespaces($namespaces, array $overriders = [])
    {
        foreach ((array)$namespaces as $namespace) {
            $classes = $this->cache($namespace, function () use ($namespace) {
                return ClassTree::getClassesInNamespace($namespace);
            });

            foreach ($classes as $class) {
                $newContent = $class;

                if (in_array($class, array_keys($overriders))) {
                    $this->validateData($overriders[$class]);
                    $newContent = $overriders[$class]($this);
                }

                $this->bind($class, function () use ($newContent) {
                    return new Resolvable($newContent, $this);
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
        if (!array_key_exists($key, $this->replaceables)) {
            throw new NotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key]->getContent();
    }

    /**
     * Fetches from container with getContent.
     * 
     * @param string $key
     * 
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function resolve($key)
    {
        return $this->get($key);
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
    public function raw($key)
    {
        if (!array_key_exists($key, $this->replaceables)) {
            throw new NotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key]->getRaw();
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
    public function item($key)
    {
        if (!array_key_exists($key, $this->replaceables)) {
            throw new NotFoundException("The {$key} key could not be found in the container");
        }

        return $this->replaceables[$key];
    }

    /**
     * See if specific key exists in our replaceables.
     *
     * @param string  $key
     *
     * @return bool
     */
    public function contains($key)
    {
        return array_key_exists($key, $this->replaceables);
    }

    /**
     * Alias for contains.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->contains($key);
    }

    /**
     * Remove key from the container.
     *
     * @param $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->replaceables[$key]);
        }

        return $this;
    }

    /**
     * Set factory for upcoming bind or create a factory callable.
     *
     * @param bool|callable $isFactoryOrCallable
     *
     * @return $this|Item
     */
    public function factory($isFactoryOrCallable = true)
    {
        if (is_callable($isFactoryOrCallable)) {
            return new Item(null, $isFactoryOrCallable, $this, true);
        }

        $this->isFactory = (bool)$isFactoryOrCallable;

        return $this;
    }

    /**
     * Allow retrieving container values via magic properties.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function __get($key)
    {
        return $this->resolve($key);
    }

    /**
     * Allows the use of isset() to determine if something exists in the container.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Allows the use of unset() to remove key a key from the container.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * Bind a new key or fetch an existing one if no argument is provided..
     * If an argument is provided: Only the first one will be considered and it must be a callable.
     *
     * @param string $key
     * @param array  $data
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function __call($key, array $data)
    {
        if (!$data) {
            return $this->resolve($key);
        }

        return $this->bind($key, $data[0]);
    }

    /**
     * String representation of a pouch instance.
     *
     * @return string
     */
    public function __toString()
    {
        $containers = [
            'singletons' => self::$singletons,
            'replaceables' => $this->replaceables,
        ];

        return json_encode($containers);
    }

    /**
     * Throws an exception if the callable argument is not a callable.
     *
     * @param mixed $data
     *
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    protected function validateData($data)
    {
        if (!method_exists($data, '__invoke') && !$data instanceof Item) {
            throw new InvalidArgumentException('The provided argument must be a callable or an instance of Item');
        }
    }
}
