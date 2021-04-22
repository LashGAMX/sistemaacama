<?php

use App\Http\Controllers\Historial\HistorialController;
use Illuminate\Support\Facades\Route;

Route::get('historial', [HistorialController::class,'index']);