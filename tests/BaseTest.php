<?php

namespace Juanparati\Model2Ts\Tests;

use Illuminate\Database\Capsule\Manager;

/**
 * Class BaseTest
 */
abstract class BaseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Initialize Eloquent that may be required for some tests
     */
    public static function setUpBeforeClass(): void
    {
        $capsule = new Manager();
        $capsule->addConnection( [
            'driver'    => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}