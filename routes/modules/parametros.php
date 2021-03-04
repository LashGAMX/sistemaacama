<?php

use App\Http\Controllers\Parametros\LaboratorioController;
use Illuminate\Support\Facades\Route;

Route::get('laboratorio', [LaboratorioController::class,'index']);
