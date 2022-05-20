<?php

namespace Jpeters8889\Architect\Tests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Middleware\HandleInertiaRequests;

class ArchitectBootTest extends TestCase
{
    protected bool $bootGate = false;

    /** @test */
    public function itHasTheArchitectGate(): void
    {
        $this->assertTrue(Gate::has('accessArchitect'));
    }

    /** @test */
    public function itHasTheRouteConfigured(): void
    {
        $routes = collect(Route::getRoutes()->getRoutes());

        $this->assertCount(1, $routes->where('uri', 'architect'));
    }

    /** @test */
    public function itAppliesTheInertiaMiddleware(): void
    {
        $routes = collect(Route::getRoutes()->getRoutes());

        /** @var \Illuminate\Routing\Route $architectRoute */
        $architectRoute = $routes->firstWhere('uri', 'architect');

        $this->assertContains(HandleInertiaRequests::class, $architectRoute->middleware());
    }
}