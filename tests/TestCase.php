<?php

namespace Jpeters8889\Architect\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\ArchitectCoreServiceProvider;
use Jpeters8889\Architect\Tests\AppClasses\ArchitectAppServiceProvider;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected bool $bootGate = true;

    protected function setUp(): void
    {
        parent::setUp();

        Route::architect();

        $this->withoutExceptionHandling();

        if ($this->bootGate) {
            Gate::define('accessArchitect', fn () => true);
        }

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Jpeters8889\\Architect\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ArchitectCoreServiceProvider::class,
            ArchitectAppServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    public function authenticate(string $guard = null)
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user, $guard);
    }
}
