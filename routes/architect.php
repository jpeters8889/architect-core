<?php

use Illuminate\Support\Facades\Route;
use Jpeters8889\Architect\Base\Http\Controllers\LandingPageController;

Route::get('/', LandingPageController::class);
