<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Illuminate\Support\Collection;

class Manager
{
    /**
     * @var Collection<int, class-string<AbstractDashboard>>
     */
    protected Collection $dashboards;

    public function __construct()
    {
        $this->dashboards = new Collection([]);
    }

    /**
     * @param class-string<AbstractDashboard> $dashboard
     */
    public function registerDashboard(string $dashboard): void
    {
        $this->dashboards->push($dashboard);
    }

    /** @return Collection<int, class-string<AbstractDashboard>> */
    public function getDashboards(): Collection
    {
        return $this->dashboards;
    }
}
