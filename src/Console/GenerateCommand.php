<?php


namespace Juanparati\Model2Ts\Console;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Juanparati\Model2Ts\Assemblers\TypescriptAssembler;
use Juanparati\Model2Ts\Helpers\ReflectorHelper;
use Juanparati\Model2Ts\Mappers\LaravelAccessorMapper;
use Juanparati\Model2Ts\Mappers\LaravelCastMapper;
use Juanparati\Model2Ts\Mappers\SqlMapper;


/**
 * Class GenerateCommand.
 *
 * Generates TS interfaces.
 *
 * @package Juanparati\Model2Ts\Commands
 */
class GenerateCommand extends Command
{

    /**
     * Command signature.
     *
     * @var string
     */
    protected $signature = 'model2ts:generate
        {model              : Model namespace}
        {output}            : Output file}
        {--ignore-hidden    : Ignore hidden attributes from model}
        {--ignore-casts     : Ignore attributes casting}
        {--ignore-appends   : Ignore virtual attributes}
        {--ignore-accessors : Ignore accessors}
        {--name=            : Interface name}
    ';


    /**
     * Command handle.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function handle()
    {
        $model = $this->argument('model');

        if (!class_exists($model)) {
            $this->error('Model not found');
            return false;
        }

        /**
         * @var $model Model
         */
        $model = new $model;

        // @ToDo: Implements a way to check if model descent indirectly from Illuminate\Database\Eloquent\Model
        if (!($model instanceof Model)) {
            $this->error('Model is not a descent of Illuminate\Database\Eloquent\Model');
            return false;
        }

        $schema = (new SqlMapper($model))->mapSchema([]);
        $reflector = new ReflectorHelper($model);

        // Redefine types according to the attribute casting definitions.
        if (!$this->option('ignore-casts'))
            $schema = (new LaravelCastMapper($model))->mapSchema($schema);

        // Add virtual attributes
        if (!$this->option('ignore-accessors')) {
            $schema = (new LaravelAccessorMapper($model))
                ->ignoreAppends($this->option('ignore-appends'))
                ->mapSchema($schema);
        }

        // Discard hidden properties.
        if (!$this->option('ignore-hidden')) {
            $hidden_props = $reflector->getProtectedProperty('hidden') ?: [];
            $schema = Arr::except($schema, $hidden_props);
        }

        $name = $this->option('name') ?: class_basename($model);

        $code = (new TypescriptAssembler($schema))->assembleInterface($name);

        File::put($this->argument('output'), $code);

        $this->output->success('Typescript schema created!');

        return true;
    }

}
