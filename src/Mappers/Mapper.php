<?php


namespace Juanparati\Model2Ts\Mappers;


use Illuminate\Database\Eloquent\Model;


/**
 * Interface Mapper.
 *
 * @package Juanparati\Model2Ts\Mappers
 */
interface Mapper
{

    /**
     * Mapper constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model);


    /**
     * Redefine schema according the model attributes.
     *
     * @param array $schema
     * @return array
     */
    public function mapSchema(array $schema) : array;
}
