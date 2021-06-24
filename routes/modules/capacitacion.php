<?php

use App\Http\Controllers\Capacitacion\CapacitacionController;
use Illuminate\Support\Facades\Route;

Route::get('capacitacion/inicio',[CapacitacionController::class,'index']); 