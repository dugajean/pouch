<?php

namespace Pouch;

use Pouch\Exceptions\InvalidTypeException;

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
    public function __construct($object)
    {
        if (!is_object($object)) {
            new InvalidTypeException('The provided value is not an object.');
        }

        $this->object = $object;
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
     * @return string
     */
    public function getType($element)
    {
        if (!is_object($element)) {
            return gettype($element);
        } else {
            return get_class($element);
        }
    }

    /**
     * Magic __call method to handle the automatic resolution of parameters.
     * 
     * @param  string $method
     * @param  array $args
     * @return void
     */
    public function __call($method, array $args)
    {
        $r = new \ReflectionMethod(get_class($this->object), $method);
        $params = $r->getParameters();

        foreach ($params as $param) {
            $pos = $param->getPosition();
            if (is_object($param->getClass())) {
                $className = $param->getClass()->name;
                if (Pouch::has($className)) {
                    $content = Pouch::resolve($className);
                    $content = is_a($content, self::class) ? $content->getObject() : $content;
                    $args[$pos] = $content;
                } elseif (!isset($args[$pos])) {
                    $args[$pos] = new $className;
                } elseif (isset($args[$pos]) && !is_a($args[$pos], $className)) {
                    throw new InvalidTypeException(
                        'Invalid argument provided. Expected an instance of '.$className.', '.$this->getType($args[$pos]).' provided.'
                    );
                }
            }
        }

        $this->object->$method(...$args);
    }
}
