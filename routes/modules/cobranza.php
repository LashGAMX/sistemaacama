<?php

use App\Http\Controllers\Cobranza\CobranzaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'cobranza'], function () {
     Route::get('/servicios', [CobranzaController::class,'servicios']);
     Route::post('/setPago', [CobranzaController::class,'setPago']);
     Route::post('/setCredito', [CobranzaController::class, 'setCredito']);
     Route::post('/setRetenido', [CobranzaController::class, 'setRetenido']);

     Route::get('/getDescargar/{id}', [CobranzaController::class, 'getDescargar']);
});