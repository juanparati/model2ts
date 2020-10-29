<?php


namespace Juanparati\Model2Ts\Tests\Mappers;


use Juanparati\Model2Ts\Mappers\LaravelCastMapper;
use Juanparati\Model2Ts\Tests\BaseTest;
use Juanparati\Model2Ts\Tests\TestModels\SecondModel;


/**
 * Class LaravelCastMapperTest.
 *
 * @package Juanparati\Sql2TS\Tests\Mappers
 */
class LaravelCastMapperTest extends BaseTest
{
    /**
     * Generic test.
     *
     * @throws \ReflectionException
     */
    public function testGeneric() {

        $schema = (new LaravelCastMapper(new SecondModel()))->mapSchema([]);

        $this->assertEquals(['type' => 'string' , 'may_struct' => false], $schema['string']);
        $this->assertEquals(['type' => 'any'    , 'may_struct' => true ], $schema['object']);
        $this->assertEquals(['type' => 'number' , 'may_struct' => false], $schema['integer']);
        $this->assertEquals(['type' => 'boolean', 'may_struct' => false] , $schema['boolean']);
        $this->assertEquals(['type' => 'any'    , 'may_struct' => true ] , $schema['other']);
    }
}