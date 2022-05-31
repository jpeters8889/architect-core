<?php

namespace Jpeters8889\Architect\Tests;

use Inertia\ServiceProvider;

class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('vendor:publish', ['--tag' => 'architect-core-assets', '--force' => true]);
    }

    protected function getPackageProviders($app)
    {
        config(['inertia.testing.page_paths' => [__DIR__ . '/../resources/js/Pages']]);
        config(['inertia.testing.page_extensions' => ['vue']]);

        return array_merge([ServiceProvider::class], parent::getPackageProviders($app));
    }
}
