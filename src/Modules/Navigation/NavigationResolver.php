<?php

namespace Jpeters8889\Architect\Modules\Navigation;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Registrar as DashboardRegistrar;
use Jpeters8889\Architect\Modules\Navigation\DTO\NavigationItem;

class NavigationResolver
{
    public function __construct(
        protected DashboardRegistrar $dashboardRegistrar,
        protected BlueprintRegistrar $blueprintRegistrar,
    ) {
        //
    }

    /**
     * @return array{
     *     dashboards: Collection<int, NavigationItem>,
     *     blueprints: Collection<(int|string), array{label: string, blueprints: Collection<(int|string), NavigationItem>}>
     * }
     */
    public function build(): array
    {
        return [
            'dashboards' => $this->processDashboards(),
            'blueprints' => $this->processBlueprints(),
        ];
    }

    /**
     * @return Collection<int, NavigationItem>
     */
    protected function processDashboards(): Collection
    {
        return $this->dashboardRegistrar
            ->all()
            ->map(function ($dashboardClass) {
                /** @var AbstractDashboard $dashboard */
                $dashboard = new $dashboardClass();

                return $this->navigationItem($dashboard->label(), $dashboard->slug());
            });
    }

    /**
     * @return Collection<(int|string), array{label: string, blueprints: Collection<(int|string), NavigationItem>}>
     */
    protected function processBlueprints(): Collection
    {
        return $this->blueprintRegistrar
            ->all()
            ->map(fn (string $blueprintClass): AbstractBlueprint => new $blueprintClass())
            ->groupBy(fn (AbstractBlueprint $blueprint) => $blueprint->group())
            ->map(fn (Collection $blueprints, string $label) => [
                'label' => $label,
                'blueprints' => $blueprints->map(fn (AbstractBlueprint $blueprint) => $this->navigationItem(
                    $blueprint->label(),
                    $blueprint->slug()
                )),
            ]);
    }

    protected function navigationItem(string $label, string $slug): NavigationItem
    {
        return new NavigationItem(label: $label, slug: $slug);
    }
}
