<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Dashboards;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Dashboards\AppDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Manager;
use Jpeters8889\Architect\Tests\TestCase;

class DashboardManagerTest extends TestCase
{
    protected Manager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->make(Manager::class);
    }

    /** @test */
    public function itCanReturnTheRegisteredDashboards(): void
    {
        $this->assertInstanceOf(Collection::class, $this->manager->getDashboards());
    }

    /** @test */
    public function itCanRegisterDashboards(): void
    {
        $this->assertCount(1, $this->manager->getDashboards());

        $this->manager->registerDashboard(AppDashboard::class);

        $this->assertCount(2, $this->manager->getDashboards());
        $this->assertContains(AppDashboard::class, $this->manager->getDashboards());
    }
}
