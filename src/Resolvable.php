<?php

namespace Pouch;

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
     * @param mixed $object
     *
     * @return void
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
     * @throws \Pouch\Exceptions\ResolvableException
     */
    public function make($object)
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
    public function getType($element = null)
    {
        $element = $element !== null ? $element : $this->getObject();

        return is_object($element) ? get_class($element) : gettype($element);
    }

    /**
     * Magic __call method to handle the automatic resolution of parameters.
     * 
     * @param string $method
     * @param array $args
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    public function __call($method, array $args)
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
     * @param \ReflectionParameter[] $param
     * @param array                  $args
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    protected function resolveDependencies($params, array $args = [])
    {
        $selfName = self::class;

        foreach ((array)$params as $param) {
            $pos = $param->getPosition();

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
                $content = $this->pouch->resolve($className);
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

    public function resolveInternalDependencies($anonymousClass)
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
     */
    protected function createClassDependency($rawClassName, $nullable)
    {
        $className = explode(' ', $rawClassName)[1];

        if (strpos($className, 'Pouch\\') !== false) {
            $className = str_replace('Pouch\\', '', $className);
        }

        if (!$this->pouch->has($className) && $nullable === false) {
            throw new ResolvableException("Cannot inject class {$className} as it does not appear to exist");
        }

        try {
            $content = $this->pouch->resolve($className);
        } catch (NotFoundException $e) {
            $content = null;
        }

        $anonymousClass = new class ($className, $content)
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
            public function __construct($name, $content)
            {
                $this->name = $name;
                $this->content = $content;
            }

            /**
             * Name getter.
             *
             * @return string
             */
            public function getName()
            {
                return $this->name;
            }

            /**
             * From createClassDependency's inner class
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
            public function isAnonymous()
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
