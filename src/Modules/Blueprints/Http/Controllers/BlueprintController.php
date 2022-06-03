<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
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
}
