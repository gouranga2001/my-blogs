<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::apiResource('blog', BlogController::class);
