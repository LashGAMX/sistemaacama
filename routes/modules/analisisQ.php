<?php

use App\Http\Controllers\AnalisisQ\ParametroController;
use Illuminate\Support\Facades\Route;

Route::get('analisisQ/parametros', [ParametroController::class,'index']);
 