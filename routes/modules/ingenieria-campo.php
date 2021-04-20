<?php

use App\Http\Controllers\IngenieriaCampo\IngenieriaCampoController;
use Illuminate\Support\Facades\Route;

Route::get('ingenieriaCampo/instrumentos', [IngenieriaCampoController::class,'controlInstrumentos']);
