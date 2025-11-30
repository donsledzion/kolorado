<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GridController;

// Homepage = create page
Route::get('/', [GridController::class, 'create'])->name('home');

// Only create and show routes (no index/list)
Route::post('/generate', [GridController::class, 'store'])->name('grids.store');
Route::get('/grid/{grid}', [GridController::class, 'show'])->name('grids.show');
