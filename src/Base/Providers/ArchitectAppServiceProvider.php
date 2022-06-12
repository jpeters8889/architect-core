<?php

namespace Jpeters8889\Architect\Base\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jpeters8889\Architect\ArchitectCore;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Blueprints\CreationService;
use Jpeters8889\Architect\Modules\Blueprints\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\Exceptions\BlueprintNotFoundException;
use Jpeters8889\Architect\Modules\Blueprints\ListService;
use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Dashboards\AbstractDashboard;
use Jpeters8889\Architect\Modules\Dashboards\Registrar as DashboardRegistrar;

abstract class ArchitectAppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootstrapArchitect();
        $this->prepareInjectableDependencies();
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

    protected function prepareInjectableDependencies(): void
    {
        $this->app->scoped(CreationService::class, function (): CreationService {
            try {
                return $this->instantiateCreationService();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });

        $this->app->scoped(ListService::class, function (): ListService {
            try {
                return $this->instantiateListService();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });

        $this->app->scoped(DeletionService::class, function (): DeletionService {
            try {
                return $this->instantiateDeletionService();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });
    }

    protected function instantiateListService(): ListService
    {
        $concreteBlueprint = $this->resolveBlueprintFromSlug();

        $listService = new ListService($concreteBlueprint);

        /** @var int $page */
        $page = Request::get('page');

        /** @var array{string, 'asc' | 'desc'} | null $sorting */
        $sorting = Request::has('sortItem') ? [Request::get('sortItem'), Request::get('sortDirection', 'asc')] : null;

        $listService->load($page ?: null, $sorting);

        return $listService;
    }

    protected function instantiateCreationService(): CreationService
    {
        $concreteBlueprint = $this->resolveBlueprintFromSlug();

        return new CreationService($concreteBlueprint);
    }

    protected function instantiateDeletionService(): DeletionService
    {
        $concreteBlueprint = $this->resolveBlueprintFromSlug();
        $id = Request::route('id');

        return new DeletionService($concreteBlueprint, $concreteBlueprint->query()->findOrFail($id));
    }

    /**
     * @return class-string<AbstractDashboard>[]
     */
    abstract protected function dashboards(): array;

    /**
     * @return class-string<AbstractBlueprint>[]
     */
    abstract protected function blueprints(): array;

    protected function resolveBlueprintFromSlug(): AbstractBlueprint
    {
        $blueprint = Route::current()?->parameter('blueprint');

        throw_if(! $blueprint, new BindingResolutionException('Can only bind when using a Blueprint'));

        return resolve(BlueprintRegistrar::class)->resolveFromSlug((string)$blueprint);
    }
}
