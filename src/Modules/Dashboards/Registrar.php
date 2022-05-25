<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Dashboards\Exceptions\DashboardNotFoundException;
use Jpeters8889\Architect\Shared\Contracts\Registerable;
use Jpeters8889\Architect\Shared\Contracts\RegistrarContract;

/** @implements RegistrarContract<AbstractDashboard> */
class Registrar implements RegistrarContract
{
    /** @var Collection<int, class-string<Registerable<AbstractDashboard>>> */
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

    /** @return Collection<int, class-string<Registerable<AbstractDashboard>>> */
    public function all(): Collection
    {
        return $this->dashboards;
    }

    public function resolveFromSlug(string $slug): AbstractDashboard
    {
        $blueprint = $this->all()
            ->map(fn ($dashboard) => new $dashboard())
            ->map(fn (AbstractDashboard $dashboard) => $dashboard->slug())
            ->filter(fn ($dashboardSlug) => $dashboardSlug === $slug)
            ->first();

        throw_if(! $blueprint, DashboardNotFoundException::fromSlug($slug));

        return $blueprint;
    }
}
