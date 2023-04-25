<?php

use App\Http\Controllers\IngenieriaCampo\IngenieriaCampoController;
use Illuminate\Support\Facades\Route;

Route::get('ingCampo/ingCampo', [IngenieriaCampoController::class, 'index']);
Route::get('ingenieriaCampo/instrumentos', [IngenieriaCampoController::class,'controlInstrumentos']);
