<?php


namespace Juanparati\Model2Ts\Helpers;


use Illuminate\Database\Eloquent\Model;


/**
 * Class ReflectorHelper.
 *
 * @package Juanparati\Model2Ts\Helpers
 */
class ReflectorHelper
{

    /**
     * Model.
     *
     * @var Model
     */
    protected $model;


    /**
     * Reflector object.
     *
     * @var \ReflectionClass
     */
    protected $reflector;


    /**
     * ReflectorHelper constructor.
     *
     * @param Model $model
     * @throws \ReflectionException
     */
    public function __construct(Model $model) {
        $this->model = $model;
        $this->reflector = new \ReflectionClass($this->model);
    }


    /**
     * Get values from protected properties.
     *
     * @param string $property
     * @return mixed
     * @throws \ReflectionException
     */
    public function getProtectedProperty(string $property) {
        if (!$this->reflector->hasProperty($property))
            return null;

        $prop = $this->reflector->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($this->model);
    }


    /**
     * Get the method type.
     *
     * @param string $method
     * @return \ReflectionType
     * @throws \ReflectionException
     */
    public function getMethodType(string $method) : ?\ReflectionType
    {
        if (!$this->reflector->hasMethod($method))
            return null;

        return $this->reflector->getMethod($method)->getReturnType();
    }

}
