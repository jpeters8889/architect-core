<?php

use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Controllers\LandingPageController;
use Jpeters8889\Architect\Modules\Blueprints\Http\Controllers\BlueprintController;

Route::get('/', LandingPageController::class);

Route::prefix('blueprint/{blueprint}')->group(function () {
    Route::get('/', [BlueprintController::class, 'index']);
    Route::get('create', [BlueprintController::class, 'create']);
    Route::post('/', [BlueprintController::class, 'store']);
    Route::get('{id}', [BlueprintController::class, 'show']);
    Route::patch('{id}', [BlueprintController::class, 'update']);
    Route::delete('{id}', [BlueprintController::class, 'destroy']);
    Route::put('{id}', [BlueprintController::class, 'restore']);
});
