<?php

namespace Jpeters8889\Architect\Tests\Unit\Base\Http\Middleware;

use Jpeters8889\Architect\Base\Http\Middleware\HandleInertiaRequests;
use Jpeters8889\Architect\Modules\Navigation\NavigationResolver;
use Jpeters8889\Architect\Tests\TestCase;

class HandleIntertiaRequestsTest extends TestCase
{
    /** @test */
    public function itHasTheNavigationDataInTheSharedObject(): void
    {
        $middleware = resolve(HandleInertiaRequests::class);

        $sharedData = $middleware->share(request());

        $this->assertArrayHasKey('navigation', $sharedData);
        $this->assertEquals(resolve(NavigationResolver::class)->build(), $sharedData['navigation']);
    }

    /** @test */
    public function itHasTheLinkRootInTheSharedObject(): void
    {
        $middleware = resolve(HandleInertiaRequests::class);

        $sharedData = $middleware->share(request());

        $this->assertArrayHasKey('basePath', $sharedData);
        $this->assertEquals('architect', $sharedData['basePath']);
    }

    /** @test */
    public function itHasTheFlashObjectInTheSharedObject(): void
    {
        $middleware = resolve(HandleInertiaRequests::class);

        $sharedData = $middleware->share(request());

        $this->assertArrayHasKey('flash', $sharedData);
    }
}
