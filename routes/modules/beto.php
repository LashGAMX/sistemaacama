<?php

use App\Http\Controllers\Beto\BetoController;
use Illuminate\Support\Facades\Route;

Route::get('beto/formula', [BetoController::class,'formula']);   
    