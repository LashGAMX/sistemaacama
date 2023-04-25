<?php

use App\Http\Controllers\reporteador\ReporteadorController;
use Illuminate\Support\Facades\Route;

Route::get('reporteador',[ReporteadorController::class,'index']);
