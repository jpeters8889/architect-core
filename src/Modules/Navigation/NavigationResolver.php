<?php

namespace Jpeters8889\Architect\Modules\Navigation;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Manager as DashboardManager;

class NavigationResolver
{
    public function __construct(protected DashboardManager $dashboardManager)
    {
        //
    }

    /**
     * @return array{dashboards: Collection<int, array{label: string, slug: string, icon: string}>, blueprints: array}
     */
    public function build(): array
    {
        return [
            'dashboards' => $this->processDashboards(),
            'blueprints' => [],
        ];
    }

    /**
     * @return Collection<int, array{label: string, slug: string, icon: string}>
     */
    protected function processDashboards(): Collection
    {
        return $this->dashboardManager
            ->getDashboards()
            ->map(function ($dashboardClass) {
                /** @var AbstractDashboard $dashboard */
                $dashboard = new $dashboardClass();

                return [
                    'label' => $dashboard->label(),
                    'slug' => $dashboard->slug(),
                    'icon' => 'chart',
                ];
            });
    }
}
