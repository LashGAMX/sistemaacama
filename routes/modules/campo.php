<?php

use App\Http\Controllers\Campo\CampoController;
use Illuminate\Support\Facades\Route;

Route::get('campo/asignar', [CampoController::class,'asignar']);