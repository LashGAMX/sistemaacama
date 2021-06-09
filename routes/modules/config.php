<?php

use App\Http\Controllers\Config\AnalisisController;
use App\Http\Controllers\Config\CampoController;
use App\Http\Controllers\Config\LaboratorioController;
use App\Http\Livewire\Counter;
use Illuminate\Support\Facades\Route;
 
Route::get('config/laboratorio', [LaboratorioController::class,'index']);
Route::get('config/analisis', [AnalisisController::class,'index']);
Route::get('config/campo', [CampoController::class,'index']);