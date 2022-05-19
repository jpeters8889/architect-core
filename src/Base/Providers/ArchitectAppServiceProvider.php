<?php

namespace Jpeters8889\Architect\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Jpeters8889\Architect\ArchitectCore;
use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Manager as DashboardManager;

abstract class ArchitectAppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootstrapArchitect();
    }

    protected function bootstrapArchitect(): void
    {
        $dashboardManager = new DashboardManager();

        collect($this->dashboards())->each(fn ($dashboard) => $dashboardManager->registerDashboard($dashboard));

        $architect = new ArchitectCore($dashboardManager);

        $this->app->singleton(DashboardManager::class, fn () => $dashboardManager);
        $this->app->singleton(ArchitectCore::class, fn () => $architect);
    }

    /**
     * @return class-string<AbstractDashboard>[]
     */
    abstract protected function dashboards(): array;
}
