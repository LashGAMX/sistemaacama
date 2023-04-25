<?php

use App\Http\Controllers\Isaac\IsaacController;
use Illuminate\Support\Facades\Route;

Route::get('isaac', [IsaacController::class, 'index']);
Route::post('isaac/formula',[IsaacController::class, 'agregar']);