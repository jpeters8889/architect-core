<?php

namespace Jpeters8889\Architect\Tests\Unit\Modules\Navigation;

use Illuminate\Support\Collection;
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
            $dashboard = $dashboard->toArray();

            $this->assertArrayHasKey('label', $dashboard);
            $this->assertArrayHasKey('slug', $dashboard);
        }
    }

    /** @test */
    public function itReturnsTheBlueprintsOrderedByGroup(): void
    {
        $navigation = $this->navigationResolver->build();

        $group = $navigation['blueprints']->first();

        $this->assertArrayHasKey('label', $group);
        $this->assertNotNull($group['label']);
        $this->assertEquals('Blueprints', $group['label']);

        $this->assertArrayHasKey('blueprints', $group);
        $this->assertNotNull($group['blueprints']);
        $this->assertInstanceOf(Collection::class, $group['blueprints']);
    }

    /** @test */
    public function itFormatsTheBlueprints(): void
    {
        $navigation = $this->navigationResolver->build();

        $blueprints = $navigation['blueprints'];

        $blueprints->each(function ($group) {
            $group['blueprints']->each(function ($blueprint) {
                $blueprint = $blueprint->toArray();

                $this->assertArrayHasKey('label', $blueprint);
                $this->assertArrayHasKey('slug', $blueprint);
            });
        });
    }

    /** @test */
    public function itReturnsTheBlueprintInGroups(): void
    {
        $navigation = $this->navigationResolver->build();

        $firstGroup = $navigation['blueprints']->first();
        $secondGroup = $navigation['blueprints']->skip(1)->first();

        $this->assertEquals('Blueprints', $firstGroup['label']);
        $this->assertEquals('Blogs', $secondGroup['label']);
    }
}
