<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Dashboards;

use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Tests\AppClasses\TestDashboard;
use Jpeters8889\Architect\Tests\TestCase;

class DashboardTest extends TestCase
{
    protected AbstractDashboard $dashboard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dashboard = new TestDashboard();
    }

    /** @test */
    public function itResolvesTheLabel(): void
    {
        $this->assertEquals('Test Dashboard', $this->dashboard->label());
    }

    /** @test */
    public function itResolvesTheSlug(): void
    {
        $this->assertEquals('test-dashboard', $this->dashboard->slug());
    }
}
