<?php

use App\Http\Controllers\formula\FormulaController;
use Illuminate\Support\Facades\Route;

Route::get('formula',[FormulaController::class,'index']); 