<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Jpeters8889\Architect\Modules\Blueprints\DeletionService;
use Jpeters8889\Architect\Modules\Blueprints\ListService;

class BlueprintController
{
    public function list(ListService $listService, Request $request): Response
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

    public function delete(DeletionService $deletionService): RedirectResponse
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
