<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\CreateItemRequest;
use Jpeters8889\Architect\Modules\Blueprints\Http\Requests\UpdateItemRequest;
use Jpeters8889\Architect\Modules\Blueprints\Processors\CreateNewProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Processors\EditItemProcessor;
use Jpeters8889\Architect\Modules\Blueprints\Services\CreationFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\Services\EditFormService;
use Jpeters8889\Architect\Modules\Blueprints\Services\ListService;
use Jpeters8889\Architect\Shared\Flash;

class BlueprintController
{
    public function index(ListService $listService, Request $request): Response
    {
        return Inertia::render('Blueprint/Index', [
            'metas' => $listService->metas(),
            'data' => $listService->data(),
            'currentValues' => $listService->currentRequestValues($request),
        ]);
    }

    public function create(CreationFormService $creationService): Response
    {
        return Inertia::render('Blueprint/Create', [
            'metas' => $creationService->metas(),
            'fields' => $creationService->formFields(),
        ]);
    }

    public function store(CreateItemRequest $request, CreateNewProcessor $processor): RedirectResponse
    {
        $processor->createFromRequest($request);

        $route = $processor->blueprint()->listRoute();

        if ($request->get('redirectBack') === 'true') {
            $route = $processor->blueprint()->createRoute();
        }

        return redirect()->to($route)->with(...Flash::message("{$processor->blueprint()->shortName()} Created!"));
    }

    public function show(EditFormService $editFormService): Response
    {
        return Inertia::render('Blueprint/Edit', [
            'metas' => $editFormService->metas(),
            'fields' => $editFormService->formFields(),
        ]);
    }

    public function update(UpdateItemRequest $request, EditItemProcessor $processor): RedirectResponse
    {
        $processor->updateItemFromRequest($request);

        return redirect()
            ->to($processor->blueprint()->listRoute())
            ->with(...Flash::message("{$processor->blueprint()->shortName()} Updated!"));
    }

    public function destroy(DeletionService $deletionService): RedirectResponse
    {
        $deletionService->handleDelete();

        return back()->with(...Flash::message('Record Deleted!'));
    }

    public function restore(DeletionService $deletionService): RedirectResponse
    {
        $deletionService->handleRestore();

        return back()->with(...Flash::message('Record Restored!'));
    }
}
