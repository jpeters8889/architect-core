<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Navigation;

use Jpeters8889\Architect\Modules\Navigation\NavigationResolver;
use Jpeters8889\Architect\Tests\TestCase;

class NavigationResolverTest extends TestCase
{
    protected NavigationResolver $navigationResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->navigationResolver = $this->app->make(NavigationResolver::class);
    }

    /** @test */
    public function itReturnsADashboardAndBlueprintsKeyInTheNavigation(): void
    {
        $navigation = $this->navigationResolver->build();

        $this->assertArrayHasKey('dashboards', $navigation);
        $this->assertArrayHasKey('blueprints', $navigation);
    }

    /** @test */
    public function itFormatsTheDashboards(): void
    {
        $navigation = $this->navigationResolver->build();

        $dashboards = $navigation['dashboards'];

        foreach ($dashboards as $dashboard) {
            $this->assertArrayHasKey('label', $dashboard);
            $this->assertArrayHasKey('slug', $dashboard);
            $this->assertArrayHasKey('icon', $dashboard);
        }
    }
}
