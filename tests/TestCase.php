<?php

namespace Jpeters8889\Architect\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jpeters8889\Architect\ArchitectCoreServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Jpeters8889\\Architect\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ArchitectCoreServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_architect-core_table.php.stub';
        $migration->up();
        */
    }
}
