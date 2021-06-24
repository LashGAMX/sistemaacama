<?php

use App\Http\Controllers\Christian\ChristianController;
use Illuminate\Support\Facades\Route;

Route::get('christian/inicio', [ChristianController::class, 'index']);