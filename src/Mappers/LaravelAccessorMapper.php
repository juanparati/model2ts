<?php


namespace Juanparati\Model2Ts\Mappers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juanparati\Model2Ts\Helpers\ReflectorHelper;


/**
 * Class LaravelAccessorMapper.
 *
 * @package Juanparati\Model2Ts\Mappers
 */
class LaravelAccessorMapper implements Mapper
{

    /**
     * Scalar and compound types map.
     */
    const PHP_MAP = [
        'boolean' => 'boolean',
        'integer' => 'number',
        'int'     => 'number',
        'float'   => 'number',
        'string'  => 'string',
        'array'   => 'any',
        'object'  => 'any',
    ];

    /**
     * Model.
     *
     * @var Model
     */
    protected $model;


    /**
     * Ignore appends attributes.
     *
     * @var bool
     */
    protected $ignoreAppends = false;


    /**
     * ReflectorHelper instance.
     *
     * @var ReflectorHelper
     */
    protected $reflector;


    /**
     * LaravelAccessorMapper constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model) {
        $this->model     = $model;
        $this->reflector = new ReflectorHelper($this->model);
    }


    /**
     * Redefine schema according the model attributes casting.
     *
     * @param array $schema
     * @return array
     */
    public function mapSchema(array $schema) : array {

        $mutatedAttr = $this->model->getMutatedAttributes();

        if ($this->ignoreAppends)
            $mutatedAttr = array_diff($mutatedAttr, $this->getAppends());

        foreach ($mutatedAttr as $key) {

            if (($type = $this->reflector->getMethodType('get' . Str::studly($key) . 'Attribute')) === null)
                continue;

            $tsType = static::PHP_MAP[$type->getName()] ?: 'any';

            $schema[$key] = [
                'type'       => $tsType,
                'nullable'   => $type->allowsNull(),
                'may_struct' => $tsType === 'any'
            ];

        }

        return $schema;
    }


    /**
     * Set the flag that ignores the appends attributes.
     *
     * @param bool $ignoreAppends
     * @return $this
     */
    public function ignoreAppends(bool $ignoreAppends) {
        $this->ignoreAppends = $ignoreAppends;

        return $this;
    }


    /**
     * Obtain the appends attribute.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getAppends() : array {
        return $this->reflector->getProtectedProperty('appends') ?: [];
    }
}
