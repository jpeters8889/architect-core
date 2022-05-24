<?php

namespace Jpeters8889\Architect\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Jpeters8889\Architect\ArchitectCore;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Registrar as DashboardRegistrar;

abstract class ArchitectAppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootstrapArchitect();
    }

    protected function bootstrapArchitect(): void
    {
        $dashboardRegistrar = new DashboardRegistrar();
        $blueprintRegistrar = new BlueprintRegistrar();

        collect($this->dashboards())->each(fn ($dashboard) => $dashboardRegistrar->register($dashboard));
        collect($this->blueprints())->each(fn ($blueprint) => $blueprintRegistrar->register($blueprint));

        $architect = new ArchitectCore($dashboardRegistrar, $blueprintRegistrar);

        $this->app->singleton(DashboardRegistrar::class, fn () => $dashboardRegistrar);
        $this->app->singleton(BlueprintRegistrar::class, fn () => $blueprintRegistrar);
        $this->app->singleton(ArchitectCore::class, fn () => $architect);
    }

    /**
     * @return class-string<AbstractDashboard>[]
     */
    abstract protected function dashboards(): array;

    /**
     * @return class-string<AbstractBlueprint>[]
     */
    abstract protected function blueprints(): array;
}
