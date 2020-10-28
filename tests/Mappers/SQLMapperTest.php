<?php


namespace Juanparati\Sql2Ts\Tests\Mappers;


use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\BlobType;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Juanparati\Model2Ts\Mappers\SqlMapper;
use Juanparati\Model2Ts\Tests\BaseTest;
use Juanparati\Model2Ts\Tests\TestModels\FirstModel;




/**
 * Class SQLMapperTest.
 *
 * @package Juanparati\Sql2TS\Tests\Mappers
 */
class SQLMapperTest extends BaseTest
{

    public function testGeneric() {

        $columns = [
            'stringN'  => new Column('stringN' , new StringType() , ['notNull' => true]),
            'string'   => new Column('string'  , new StringType() , ['notNull' => false]),
            'stringD'  => new Column('stringD' , new StringType() , ['notNull' => false, 'default' => true]),
            'integerN' => new Column('integerN', new IntegerType(), ['notNull' => true]),
            'boolean'  => new Column('boolean' , new BooleanType(), ['notNull' => false]),
            'text'     => new Column('text'    , new TextType()   , ['notNull' => false]),
        ];

        $schema = (new SqlMapper(new FirstModel()))
            ->injectTableColumns($columns)
            ->mapSchema([]);

        $this->assertEquals(['type' => 'string' , 'may_struct' => false, 'nullable' => false], $schema['stringN']);
        $this->assertEquals(['type' => 'string' , 'may_struct' => false, 'nullable' => true ], $schema['string']);
        $this->assertEquals(['type' => 'string' , 'may_struct' => false, 'nullable' => false], $schema['stringD']);
        $this->assertEquals(['type' => 'number' , 'may_struct' => false, 'nullable' => false], $schema['integerN']);
        $this->assertEquals(['type' => 'boolean', 'may_struct' => false, 'nullable' => true] , $schema['boolean']);
        $this->assertEquals(['type' => 'string' , 'may_struct' => true , 'nullable' => true] , $schema['text']);
    }
}