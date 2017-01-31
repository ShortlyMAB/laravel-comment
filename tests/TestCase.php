<?php

namespace Actuallymab\LaravelComment\Tests;

use Actuallymab\LaravelComment\LaravelCommentServiceProvider;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/** actuallymab | 12.06.2016 - 14:21 */
abstract class TestCase extends Orchestra
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/resources/database/migrations'),
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $application
     * @return array
     */
    public function getPackageProviders($application)
    {
        return [
            LaravelCommentServiceProvider::class,
            ConsoleServiceProvider::class
        ];
    }
}
