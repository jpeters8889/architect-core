<?php

namespace Jpeters8889\Architect\Modules\Blueprints;

use Illuminate\Support\Collection;
use Jpeters8889\Architect\Modules\Blueprints\Exceptions\BlueprintNotFoundException;
use Jpeters8889\Architect\Shared\Contracts\RegistrarContract;

/** @implements RegistrarContract<AbstractBlueprint> */
class Registrar implements RegistrarContract
{
    /** @var Collection<int, class-string<AbstractBlueprint>> */
    protected Collection $blueprints;

    public function __construct()
    {
        $this->blueprints = new Collection([]);
    }

    /** @param class-string<AbstractBlueprint> $item */
    public function register(string $item): void
    {
        $this->blueprints->push($item);
    }

    /** @return Collection<int, class-string<AbstractBlueprint>> */
    public function all(): Collection
    {
        return $this->blueprints;
    }

    public function resolveFromSlug(string $slug): AbstractBlueprint
    {
        $blueprint = $this->all()
            ->map(fn ($blueprint) => new $blueprint())
            ->filter(fn (AbstractBlueprint $blueprint) => $blueprint->slug() === $slug)
            ->first();

        throw_if(! $blueprint, BlueprintNotFoundException::fromSlug($slug));

        return $blueprint;
    }
}
