<?php

use App\Http\Controllers\Katerin\KaterinController;
use Illuminate\Support\Facades\Route;

//Route::get('Katerin',[KaterinController::class,'index']); 
Route::get('katerin',[KaterinController::class,'index']);
Route::get('katerin2',[KaterinController::class,'index2']);