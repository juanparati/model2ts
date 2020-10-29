<?php


namespace Juanparati\Model2Ts\Mappers;


use Illuminate\Database\Eloquent\Model;
use Juanparati\Model2Ts\Helpers\ReflectorHelper;

/**
 * Class LaravelCastMapperHelper.
 *
 * @package Juanparati\Model2Ts\Helpers
 */
class LaravelCastMapper implements Mapper
{

    /**
     * Laravel casting mappings.
     */
    const LARAVEL_CAST_MAP = [
        'array'           => 'any',
        'bool'            => 'boolean',
        'boolean'         => 'boolean',
        'collection'      => 'any',
        'custom_datetime' => 'string',
        'date'            => 'string',
        'datetime'        => 'string',
        'decimal'         => 'number',
        'double'          => 'number',
        'float'           => 'number',
        'int'             => 'number',
        'integer'         => 'number',
        'json'            => 'any',
        'object'          => 'any',
        'real'            => 'number',
        'string'          => 'string',
        'timestamp'       => 'string',
    ];


    /**
     * Model.
     *
     * @var Model
     */
    protected $model;


    /**
     * LaravelCastMapper constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * Redefine schema according the model attributes casting.
     *
     * @param array $schema
     * @return array
     * @throws \ReflectionException
     */
    public function mapSchema(array $schema) : array {

        foreach ($this->getCastsValues() as $key => $type) {

            $schema[$key]['may_struct'] = !is_string($type) || !isset(static::LARAVEL_CAST_MAP[$type]);
            $schema[$key]['type'] = static::LARAVEL_CAST_MAP[$type] ?? ($schema[$key]['type'] ?? 'any');

            if ($schema[$key]['type'] === 'any')
                $schema[$key]['may_struct'] = true;
            else
                $schema[$key]['may_struct'] = false;
        }

        return $schema;
    }


    /**
     * Obtain the cast values from the model.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getCastsValues() : array {
        return (new ReflectorHelper($this->model))->getProtectedProperty('casts') ?: [];
    }


}
