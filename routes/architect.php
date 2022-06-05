<?php

use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Controllers\LandingPageController;
use Jpeters8889\Architect\Modules\Blueprints\Http\Controllers\BlueprintController;

Route::get('/', LandingPageController::class);

Route::get('blueprint/{blueprint}', [BlueprintController::class, 'list']);
Route::delete('blueprint/{blueprint}/{id}', [BlueprintController::class, 'delete']);
Route::put('blueprint/{blueprint}/{id}', [BlueprintController::class, 'restore']);
