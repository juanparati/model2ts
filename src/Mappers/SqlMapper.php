<?php


namespace Juanparati\Model2Ts\Mappers;


use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\BigIntType;
use Doctrine\DBAL\Types\BlobType;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\SmallIntType;
use Doctrine\DBAL\Types\TextType;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Model;


/**
 * Class SchemaMapperHelper.
 *
 * @package Juanparati\Model2Ts\Helpers
 */
class SqlMapper implements Mapper
{

    /**
     * SQL to typescript types mapping.
     */
    const INTEGER_TYPE  = 'number';
    const FLOAT_TYPE    = 'number';
    const BOOLEAN_TYPE  = 'boolean';
    const DATE_TYPE     = 'string';
    const DATETIME_TYPE = 'string';
    const STRING_TYPE   = 'string';
    const BLOB_TYPE     = 'any';


    /**
     * Database connection.
     *
     * @var ConnectionInterface|null
     */
    protected $schema_manager = null;


    /**
     * Model.
     *
     * @var Model
     */
    protected $model;


    /**
     * Column list to mock.
     *
     * @var Column[]
     */
    protected $columnList = [];


    /**
     * SQLMapper constructor.
     *
     * @param Model $model
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(Model $model) {
        $this->model      = $model;
        $this->connection = $model->getConnection();

        $this->schema_manager = $this->connection->getDoctrineSchemaManager();

        // Register some unknown mapping types
        $this->schema_manager->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');

    }


    /**
     * Map schema from SQL.
     *
     * @param array $schema
     * @return array
     */
    public function mapSchema(array $schema) : array {

        $columns = $this->columnList ?: $this->schema_manager->listTableColumns($this->model->getTable());

        foreach ($columns as $name => $props) {
            $type = $props->getType();

            [$tsType, $mayStruct] = static::mapType($type);

            $schema[$name] = [
                'type'       => $tsType,
                'may_struct' => $mayStruct,
                'nullable'   => !$props->getNotnull() && $props->getDefault() === null,
            ];
        }

        return $schema;
    }


    /**
     * Set a list of columns.
     *
     * @param Column[] $columns
     */
    public function injectTableColumns(array $columns) {
        $this->columnList = $columns;

        return $this;
    }


    /**
     * Map SQL types into BQ types.
     *
     * @param $type
     * @return array
     */
    protected static function mapType($type) : array {

        switch (get_class($type)) {
            case BigIntType::class:
            case SmallIntType::class:
            case IntegerType::class:
                return [static::INTEGER_TYPE, false];

            case FloatType::class:
                return [static::FLOAT_TYPE, false];

            case BooleanType::class:
                return [static::BOOLEAN_TYPE, false];

            case DateType::class:
                return [static::DATE_TYPE, false];

            case DateTimeType::class:
                return [static::DATETIME_TYPE, false];

            case TextType::class:
                return [static::STRING_TYPE, true];

            case BlobType::class:
                return [static::BLOB_TYPE, true];
        }

        return [static::STRING_TYPE, false];
    }


}
