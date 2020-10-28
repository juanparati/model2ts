<?php


namespace Juanparati\Model2Ts\Assemblers;


/**
 * Class TypescriptAssembler.
 *
 * @package Juanparati\Model2Ts\Assemblers
 */
class TypescriptAssembler
{

    /**
     * Raw schema.
     *
     * @var array
     */
    protected $schema;


    /**
     * TypescriptAssembler constructor.
     *
     * @param array $schema
     */
    public function __construct(array $schema) {
       $this->schema = $schema;
    }


    /**
     * Assembler the interface string.
     *
     * @param string $name
     * @return string
     */
    public function assembleInterface(string $name) : string {
        $con = "export default interface $name {" . PHP_EOL;

        foreach ($this->schema as $key => $column) {
            $con .= "    $key: {$column['type']}";

            if ($column['nullable'])
                $con .= ' | null';

            $con .= ',';

            if ($column['may_struct'])
                $con .= ' // Is object or array?';

            $con .= PHP_EOL;
        }

        $con .= "}";

        return $con;
    }
}
