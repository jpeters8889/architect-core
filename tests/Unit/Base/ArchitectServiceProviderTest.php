<?php

namespace Jpeters8889\Architect\Tests\Unit\Base;

use Jpeters8889\Architect\ArchitectCore;
use Jpeters8889\Architect\Modules\Dashboards\Manager as DashboardManager;
use Jpeters8889\Architect\Tests\AppClasses\TestDashboard;
use Jpeters8889\Architect\Tests\TestCase;

class ArchitectServiceProviderTest extends TestCase
{
    /** @test */
    public function itRegistersTheDashboardManagerIntoTheServiceContainer(): void
    {
        $concrete = $this->app->make(DashboardManager::class);

        $this->assertNotNull($concrete);
        $this->assertInstanceOf(DashboardManager::class, $concrete);
    }

    /** @test */
    public function itRegistersTheArchitectCoreAppIntoTheServiceContainer(): void
    {
        $concrete = $this->app->make(ArchitectCore::class);

        $this->assertNotNull($concrete);
        $this->assertInstanceOf(ArchitectCore::class, $concrete);
    }

    /** @test */
    public function itRegistersTheDashboardsIntoTheApp(): void
    {
        /** @var DashboardManager $dashboardManager */
        $dashboardManager = $this->app->make(DashboardManager::class);

        $this->assertCount(1, $dashboardManager->getDashboards());

        $this->assertEquals(TestDashboard::class, $dashboardManager->getDashboards()->first());
    }
}
