<?php


namespace Juanparati\Model2Ts\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;

/**
 * Class SecondModel.
 *
 * @package TestModels
 */
class SecondModel extends Model
{

    /**
     * Cast attributes.
     *
     * @var string[]
     */
    protected $casts = [
        'string'  => 'string',
        'object'  => 'array',
        'integer' => 'int',
        'boolean' => 'boolean',
        'other'   => SecondModel::class
    ];

}