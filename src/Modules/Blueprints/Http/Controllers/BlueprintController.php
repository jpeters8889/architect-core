<?php

namespace Jpeters8889\Architect\Modules\Blueprints\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Jpeters8889\Architect\Modules\Blueprints\ListService;

class BlueprintController
{
    public function list(ListService $listService): Response
    {
        return Inertia::render('Blueprint/Index', [
            'metas' => $listService->metas(),
            'data' => $listService->data(),
        ]);
    }
}
