<?php

namespace Jpeters8889\Architect\Tests;

use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;
use Inertia\ServiceProvider;

class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->artisan('vendor:publish', ['--tag' => 'architect-core-assets', '--force' => true]);

        TestResponse::macro('assertFlashMessage', function ($message, $type = 'success') {
            $this->assertSessionHas('flash');

            $flash = $this->session()->get('flash');

            PHPUnit::assertEquals($type, $flash['type'], "Expected flash message to be of type {{$type}} but received {$flash['type']}");
            PHPUnit::assertEquals($message, $flash['message']);
        });
    }

    protected function getPackageProviders($app)
    {
        config(['inertia.testing.page_paths' => [__DIR__ . '/../resources/js/Pages']]);
        config(['inertia.testing.page_extensions' => ['vue']]);

        return array_merge([ServiceProvider::class], parent::getPackageProviders($app));
    }
}
