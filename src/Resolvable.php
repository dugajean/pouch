<?php

namespace Pouch;

use Pouch\Exceptions\ResolvableException;

class Resolvable
{
    /**
     * Store the object that we're "decorating".
     * 
     * @var mixed
     */
    protected $object;

    /**
     * Inject the decorated object.
     * 
     * @param mixed $object
     */
    public function __construct($object = null)
    {
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
     * Get the type of an "element" accurately. If it's an object, the exact class name will be returned.
     * 
     * @param  mixed $element
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
     * @param  string $method
     * @param  array $args
     *
     * @return void
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
     * Resolve the dependency for all parameters.
     *
     * @param array $param
     * @param array $args
     *
     * @return array
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    protected function resolveDependencies($params, array $args = [])
    {
        foreach ((array)$params as $param) {
            $pos = $param->getPosition();

            try {
                $class = $param->getClass();
            } catch (\ReflectionException $e) {
                $class = $this->createClassDependency($e->getMessage());
            }

            if (is_object($class)) {
                $className = $class->name;

                if (pouch()->has($className)) {
                    $selfName = self::class;
                    $content = pouch()->resolve($className);
                    $content = $content instanceof $selfName ? $content->getObject() : $content;
                    $args[$pos] = $content;
                } elseif (!isset($args[$pos])) {
                    $args[$pos] = new $className;
                } elseif (isset($args[$pos]) && !$args[$pos] instanceof $className) {
                    throw new ResolvableException(
                        'Invalid argument provided. Expected an instance of '.$className.', '.$this->getType($args[$pos]).' provided'
                    );
                }
            }
        }

        return $args;
    }

    /**
     * Creates missing class if it can be found in the container. Auto-injecting classes from within
     * the container require them to be prefixed with \Pouch\Key.
     *
     * @param $rawClassName
     *
     * @return mixed
     *
     * @throws \Pouch\Exceptions\ResolvableException
     */
    protected function createClassDependency($rawClassName)
    {
        $className = explode(' ', $rawClassName)[1];

        if (strpos($className, 'Pouch\\') !== false) {
            $className = str_replace('Pouch\\', '', $className);
        }

        if (!pouch()->has($className)) {
            throw new ResolvableException("Cannot inject class {$className} as it does not appear to exist");
        }

        $content = pouch()->resolve($className);
        $anonymousClass = new class ($className, $content) {
            public $name, $content;
            public function __construct($name, $content) {
                $this->name = $name;
                $this->content = $content;
            }
            public function getContent(){
                return $this->content;
            }
        };

        class_alias(get_class($anonymousClass), "\\Pouch\\$className");
        pouch()->bind($className, $anonymousClass);

        return $anonymousClass;
    }
}
