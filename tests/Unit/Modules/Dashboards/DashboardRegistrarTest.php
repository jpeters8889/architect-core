<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Dashboards;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Dashboards\AppDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Registrar;
use Jpeters8889\Architect\Tests\TestCase;

class DashboardRegistrarTest extends TestCase
{
    protected Registrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrar = $this->app->make(Registrar::class);
    }

    /** @test */
    public function itCanReturnTheRegisteredDashboards(): void
    {
        $this->assertInstanceOf(Collection::class, $this->registrar->all());
    }

    /** @test */
    public function itCanRegisterDashboards(): void
    {
        $this->assertCount(1, $this->registrar->all());

        $this->registrar->register(AppDashboard::class);

        $this->assertCount(2, $this->registrar->all());
        $this->assertContains(AppDashboard::class, $this->registrar->all());
    }
}
