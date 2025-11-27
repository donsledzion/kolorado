<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GridController;

Route::redirect('/', '/grids');

Route::resource('grids', GridController::class)->only([
    'index', 'create', 'store', 'show'
]);
