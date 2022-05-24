<?php

namespace Jpeters8889\Architect\Modules\Dashboards;

use Illuminate\Support\Collection;
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
}
