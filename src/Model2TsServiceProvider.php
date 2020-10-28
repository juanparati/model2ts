<?php
namespace Juanparati\Model2Ts;

use Illuminate\Support\ServiceProvider;
use Juanparati\Model2Ts\Console\GenerateCommand;


/**
 * Class Model2TsServiceProvider.
 *
 * @package Juanparati\Model2Ts
 */
class Model2TSServiceProvider extends ServiceProvider {

    public function boot() {
        if ($this->app->runningInConsole())
            $this->commands([GenerateCommand::class,]);
    }
}
