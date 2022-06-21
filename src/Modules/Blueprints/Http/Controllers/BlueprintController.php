<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\CreateItemRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Services\CreationFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\Services\ListService;

class BlueprintController
{
    public function index(ListService $listService, Request $request): Response
    {
        return Inertia::render('Blueprint/Index', [
            'metas' => $listService->metas(),
            'data' => $listService->data(),
            'currentSort' => [
                'column' => $request->get('sortItem', $listService->blueprint()->orderBy()[0]),
                'direction' => $request->get('sortDirection', $listService->blueprint()->orderBy()[1]),
            ],
        ]);
    }

    public function create(CreationFormService $creationService): Response
    {
        return Inertia::render('Blueprint/Create', [
            'metas' => $creationService->metas(),
            'fields' => $creationService->formFields(),
        ]);
    }

    public function store(CreateItemRequest $request, CreateNewProcessor $processor): void
    {
        //
    }

    public function show(): void
    {
        //
    }

    public function update(): void
    {
        //
    }

    public function destroy(DeletionService $deletionService): RedirectResponse
    {
        $deletionService->handleDelete();

        return back()->with('flash', ['type' => 'success', 'message' => 'Record deleted!', 'id' => Str::uuid()]);
    }

    public function restore(DeletionService $deletionService): RedirectResponse
    {
        $deletionService->handleRestore();

        return back()->with('flash', ['type' => 'success', 'message' => 'Record restored!', 'id' => Str::uuid()]);
    }
}
