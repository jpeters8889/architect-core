<?php

use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Controllers\LandingPageController;
use Jpeters8889\Architect\Modules\Blueprints\Http\Controllers\BlueprintController;

Route::get('/', LandingPageController::class);

Route::prefix('blueprint/{blueprint}')->group(function () {
    Route::get('/', [BlueprintController::class, 'list']);
    Route::get('create', [BlueprintController::class, 'create']);
    Route::delete('{id}', [BlueprintController::class, 'delete']);
    Route::put('{id}', [BlueprintController::class, 'restore']);
});
