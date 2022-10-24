<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jpeters8889\Architect\Modules\Blueprints\AbstractBlueprint;
use Jpeters8889\Architect\Modules\Blueprints\DTO\ListServiceLoader;
use Jpeters8889\Architect\Modules\Blueprints\Exceptions\BlueprintNotFoundException;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Processors\EditItemProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Registrar as BlueprintRegistrar;
use Jpeters8889\Architect\Modules\Blueprints\Services\CreationFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\Services\EditFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\ListService;

class BlueprintServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootServices();
        $this->bootProcessors();
    }

    protected function bootServices(): void
    {
        $this->app->scoped(CreationFormService::class, function (): CreationFormService {
            try {
                return $this->instantiateCreationFormService();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });

        $this->app->scoped(EditFormService::class, function (): EditFormService {
            try {
                return $this->instantiateEditFormService();
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

    protected function bootProcessors(): void
    {
        $this->app->scoped(CreateNewProcessor::class, function (): CreateNewProcessor {
            try {
                return $this->instantiateCreateNewProcessor();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });

        $this->app->scoped(EditItemProcessor::class, function (): EditItemProcessor {
            try {
                return $this->instantiateEditItemProcessor();
            } catch (BlueprintNotFoundException $exception) {
                abort(404);
            }
        });
    }

    protected function instantiateListService(): ListService
    {
        $listService = new ListService($this->resolveBlueprintFromSlug());

        $listService->load(ListServiceLoader::parseFromRequest(request()));

        return $listService;
    }

    protected function instantiateCreationFormService(): CreationFormService
    {
        $service = new CreationFormService($this->resolveBlueprintFromSlug());

        /** @var null | string | int $duplicateFrom */
        $duplicateFrom = app('request')->get('from');

        if ($duplicateFrom) {
            $service->duplicateFrom($duplicateFrom);
        }

        return $service;
    }

    protected function instantiateEditFormService(): EditFormService
    {
        $concreteBlueprint = $this->resolveBlueprintFromSlug();

        /** @var string | int $id */
        $id = Request::route('id');

        return (new EditFormService($concreteBlueprint))->loadFromId($id);
    }

    protected function instantiateDeletionService(): DeletionService
    {
        $concreteBlueprint = $this->resolveBlueprintFromSlug();
        $id = Request::route('id');

        return new DeletionService($concreteBlueprint, $concreteBlueprint->query()->findOrFail($id));
    }

    protected function instantiateCreateNewProcessor(): CreateNewProcessor
    {
        return new CreateNewProcessor($this->resolveBlueprintFromSlug());
    }

    protected function instantiateEditItemProcessor(): EditItemProcessor
    {
        $processor = new EditItemProcessor($this->resolveBlueprintFromSlug());

        /** @var string | int $id */
        $id = Route::current()?->parameter('id');

        $processor->resolveItem($id);

        return $processor;
    }

    protected function resolveBlueprintFromSlug(): AbstractBlueprint
    {
        $blueprint = Route::current()?->parameter('blueprint');

        throw_if(!$blueprint, new BindingResolutionException('Can only bind when using a Blueprint'));

        return resolve(BlueprintRegistrar::class)->resolveFromSlug((string)$blueprint);
    }
}
