<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Dashboards\Exceptions\DashboardNotFoundException;
use Jpeters8889\Architect\Shared\Contracts\RegistrarContract;

/** @implements RegistrarContract<AbstractDashboard> */
class Registrar implements RegistrarContract
{
    /** @var Collection<int, class-string<AbstractDashboard>> */
    protected Collection $dashboards;

    public function __construct()
    {
        $this->dashboards = new Collection([]);
    }

    /** @param class-string<AbstractDashboard> $item */
    public function register(string $item): void
    {
        $this->dashboards->push($item);
    }

    /** @return Collection<int, class-string<AbstractDashboard>> */
    public function all(): Collection
    {
        return $this->dashboards;
    }

    public function resolveFromSlug(string $slug): AbstractDashboard
    {
        $dashboard = $this->all()
            ->map(fn ($dashboard): AbstractDashboard => new $dashboard())
            ->filter(fn (AbstractDashboard $dashboard): bool => $dashboard->slug() === $slug)
            ->first();

        throw_if(! $dashboard, DashboardNotFoundException::fromSlug($slug));

        return $dashboard;
    }
}
