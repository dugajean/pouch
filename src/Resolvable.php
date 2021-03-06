<?php

declare(strict_types=1);

namespace Pouch;

use Pouch\Container\ItemInterface;
use Pouch\Exceptions\NotFoundException;
use Pouch\Exceptions\ResolvableException;

class Resolvable
{
    /**
     * Contains the original object.
     * 
     * @var mixed
     */
    protected $object;

    /**
     * Holds the Pouch instance. Default to singleton version (pouch()).
     *
     * @var Pouch
     */
    protected $pouch;

    /**
     * Inject the decorated object.
     *
     * @param mixed             $object
     * @param \Pouch\Pouch|null $pouch
     *
     * @throws \Pouch\Exceptions\ResolvableException
     * @throws \ReflectionException
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function __construct($object = null, Pouch $pouch = null)
    {
        $this->pouch = $pouch ?? pouch();

        if ($object !== null) {
            $this->make($object);
        }
    }

    /**
     * Set the object for the resolvable. Also resolve constructor dependencies if needed.
     *
     * @param mixed $object Object or class name (which will be created) to be made resolavable.
     *
     * @return $this
     *
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\ResolvableException
     * @throws \ReflectionException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function make($object): self
    {
        $expMsg = 'Invalid type provided. Must be either an object or a string with a valid class name';

        if (!is_object($object) && !is_string($object)) {
            throw new ResolvableException($expMsg);
        }

        if (is_string($object) && !class_exists($object)) {
            throw new ResolvableException($expMsg);
        }

        if (is_object($object)) {
            $this->object = $object;
        } elseif (is_string($object)) {
            $construct = (new \ReflectionClass($object))->getConstructor();
            $constructParams = $construct ? $construct->getParameters() : [];
            $dependencies = $this->resolveDependencies($constructParams);

            $this->object = new $object(...$dependencies);
        }

        return $this;
    }

    /**
     * Return the current object of this instance.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get the type of an anything accurately. If it's an object, the exact class name will be returned.
     * 
     * @param mixed $element
     *
     * @return string
     */
    public function getType($element = null): string
    {
        $element = $element !== null ? $element : $this->getObject();

        return is_object($element) ? get_class($element) : gettype($element);
    }

    /**
     * Magic __call method to handle the automatic resolution of parameters.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\ResolvableException
     * @throws \ReflectionException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    public function __call(string $method, array $args)
    {
        try {
            $params = (new \ReflectionMethod(get_class($this->object), $method))->getParameters();
        } catch (\ReflectionException $e) {
            $currentClass = get_class($this->object);
            throw new ResolvableException("Cannot find method '{$method}' in {$currentClass}");
        }

        $dependencies = $this->resolveDependencies($params, $args);

        return $this->object->$method(...$dependencies);
    }

    /**
     * Resolve the dependencies for all parameters.
     *
     * When resolving internal pouch keys, it will register an anonymous class and handle the autowiring
     * for the first occurrence. But, when the same internal key is used twice in different classes, then
     * this method will also take care of properly re-instantiating the anonymous class.
     *
     * @param \ReflectionParameter[] $params
     * @param array                  $args
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\NotFoundException
     * @throws \Pouch\Exceptions\ResolvableException
     * @throws \ReflectionException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     */
    protected function resolveDependencies(array $params, array $args = [])
    {
        $selfName = self::class;

        foreach ((array)$params as $param) {
            $pos  = $param->getPosition();
            $name = $param->getName();

            // Before we get into any typehint resolving, we should check whether
            // an item was bound with the resolvedByName flag.
            if ($this->pouch->has($name) && $this->pouch->item($name)->isResolvedByName()) {
                $args[$pos] = $this->pouch->get($name);
                continue;
            }

            try {
                $class = $param->getClass();
            } catch (\ReflectionException $e) {
                $class = $this->createClassDependency($e->getMessage(), $param->allowsNull());
            }

            if (!is_object($class)) {
                continue;
            }

            $className = $class->getName();
            if ($this->pouch->has($className)) {
                $content = $this->pouch->get($className);
                $content = $content instanceof $selfName ? $content->getObject() : $content;
                $args[$pos] = $content;
            } elseif (!isset($args[$pos])) {
                if ($class->isAnonymous()) {
                    $aliasData = $this->resolveInternalDependencies($className);
                    $args[$pos] = new $aliasData['aliasQualified']($aliasData['alias'], $aliasData['content']);
                } else {
                    $args[$pos] = (new self($className, $this->pouch))->getObject();
                }
            } elseif (isset($args[$pos]) && !$args[$pos] instanceof $className) {
                throw new ResolvableException(
                    'Invalid argument provided. Expected an instance of '.$className.', '.$this->getType($args[$pos]).' provided'
                );
            }
        }

        return $args;
    }

    /**
     * Returns params (dependencies) for internal anonymous classes.
     *
     * @param string $anonymousClass
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\NotFoundException
     */
    public function resolveInternalDependencies(string $anonymousClass): array
    {
        if (!$this->pouch->has("anon-{$anonymousClass}")) {
            throw new NotFoundException("Anonymous class anon-{$anonymousClass} can't be found");
        }

        $aliasClass = $this->pouch->get("anon-{$anonymousClass}");
        $aliasContent = $this->pouch->get($aliasClass);

        return [
            'alias' => $aliasClass,
            'content' => $aliasContent->getContent(),
            'aliasQualified' => "\\Pouch\\$aliasClass",
        ];
    }

    /**
     * Creates missing class if it can be found in the container. Auto-injecting classes from within
     * the container require them to be prefixed with \Pouch\Key.
     *
     * @param string $rawClassName
     * @param bool   $nullable
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\ResolvableException
     * @throws \Pouch\Exceptions\InvalidArgumentException
     * @throws \Pouch\Exceptions\NotFoundException
     */
    protected function createClassDependency(string $rawClassName, bool $nullable): ItemInterface
    {
        $className = explode(' ', $rawClassName)[1];

        if (strpos($className, 'Pouch\\') !== false) {
            $className = str_replace('Pouch\\', '', $className);
        }

        if (!$this->pouch->has($className) && $nullable === false) {
            throw new ResolvableException("Cannot inject class {$className} as it does not appear to exist");
        }

        try {
            $content = $this->pouch->get($className);
        } catch (NotFoundException $e) {
            $content = null;
        }

        $anonymousClass = new class ($className, $content) implements ItemInterface
        {
            /**
             * From createClassDependency's inner class
             *
             * @var string
             */
            public $name;

            /**
             * From createClassDependency's inner class
             *
             * @var mixed
             */
            private $content;

            /**
             * From createClassDependency's inner class
             *
             * @param string $name
             * @param mixed $content
             *
             * @return void
             */
            public function __construct(string $name, $content)
            {
                $this->name = $name;
                $this->content = $content;
            }

            /**
             * Name getter.
             *
             * @return string
             */
            public function getName(): ?string
            {
                return $this->name;
            }

            /**
             * From  createClassDependency's inner class
             *
             * @return mixed
             */
            public function getContent()
            {
                return $this->content;
            }

            /**
             * Adapter to comply to ReflectionClass.
             *
             * @return bool
             */
            public function isAnonymous(): bool
            {
                return true;
            }
        };

        $originalClassName = get_class($anonymousClass);
        class_alias($originalClassName, "\\Pouch\\$className");

        $this->pouch->bind($className, function () use ($anonymousClass) {
            return $anonymousClass;
        });

        $this->pouch->bind("anon-{$originalClassName}", function () use ($className) {
            return $className;
        });

        return $anonymousClass;
    }
}
