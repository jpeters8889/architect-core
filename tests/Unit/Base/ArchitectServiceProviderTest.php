<?php

namespace Jpeters8889\Architect\Tests\Unit\Base;

use Illuminate\Contracts\Container\BindingResolutionException;
use Jpeters8889\Architect\ArchitectCore;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Processors\EditItemProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Blueprints\Services\CreationFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\Services\EditFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\ListService;
use Jpeters8889\Architect\Modules\Dashboards\Registrar as DashboardRegistrar;
use Jpeters8889\Architect\Tests\AppClasses\TestDashboard;
use Jpeters8889\Architect\Tests\AppClasses\UserBlueprint;
use Jpeters8889\Architect\Tests\TestCase;

class ArchitectServiceProviderTest extends TestCase
{
    /** @test */
    public function itRegistersTheDashboardRegistrarIntoTheServiceContainer(): void
    {
        $concrete = $this->app->make(DashboardRegistrar::class);

        $this->assertNotNull($concrete);
        $this->assertInstanceOf(DashboardRegistrar::class, $concrete);
    }

    /** @test */
    public function itRegistersTheBlueprintRegistrarIntoTheServiceContainer(): void
    {
        $concrete = $this->app->make(BlueprintRegistrar::class);

        $this->assertNotNull($concrete);
        $this->assertInstanceOf(BlueprintRegistrar::class, $concrete);
    }

    /** @test */
    public function itRegistersTheArchitectCoreAppIntoTheServiceContainer(): void
    {
        $concrete = $this->app->make(ArchitectCore::class);

        $this->assertNotNull($concrete);
        $this->assertInstanceOf(ArchitectCore::class, $concrete);
    }

    /** @test */
    public function itRegistersDashboardsIntoTheApp(): void
    {
        /** @var DashboardRegistrar $dashboardManager */
        $dashboardManager = $this->app->make(DashboardRegistrar::class);

        $this->assertCount(1, $dashboardManager->all());

        $this->assertEquals(TestDashboard::class, $dashboardManager->all()->first());
    }

    /** @test */
    public function itRegisterBlueprintsIntoTheApp(): void
    {
        /** @var BlueprintRegistrar $blueprintRegistrar */
        $blueprintRegistrar = $this->app->make(BlueprintRegistrar::class);

        $this->assertNotEmpty($blueprintRegistrar->all());

        $this->assertEquals(UserBlueprint::class, $blueprintRegistrar->all()->first());
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintListServiceWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(ListService::class);
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintCreateFormServiceWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(CreationFormService::class);
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintDeletionServiceWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(DeletionService::class);
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintCreateNewProcessorWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(CreateNewProcessor::class);
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintEditFormServiceWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(EditFormService::class);
    }

    /** @test */
    public function itErrorsWhenTryingToGetABlueprintEditItemProcessorWhenNotOnABlueprintUrl(): void
    {
        $this->expectException(BindingResolutionException::class);

        resolve(EditItemProcessor::class);
    }
}
