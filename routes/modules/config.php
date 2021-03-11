<?php

use App\Http\Controllers\Config\LaboratorioController;
use App\Http\Livewire\Counter;
use Illuminate\Support\Facades\Route;
 
Route::get('config/laboratorio', [LaboratorioController::class,'index']);
