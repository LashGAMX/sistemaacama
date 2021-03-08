<?php

use App\Http\Controllers\Config\LaboratorioController;
use Illuminate\Support\Facades\Route;

Route::get('config/laboratorio', [LaboratorioController::class,'index']);
