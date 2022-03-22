<?php

use App\Http\Controllers\Campo\CampoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'campo'], function () {
    Route::get('asignar', [CampoController::class,'asignar']);
    Route::get('capturar', [CampoController::class,'listaMuestreo']);
    Route::get('captura/{id}', [CampoController::class,'captura']);
    Route::post('asignar/generar', [CampoController::class,'generar']);
    // Route::post('campo/asignar/generarUpdate', [CampoController::class,'generarUpdate']);

    Route::post('asignar/getFolio', [CampoController::class,'getFolio']);
    Route::post('captura/getFactorCorreccion',[CampoController::class,'getFactorCorreccion']);
    Route::post('captura/getPhTrazable',[CampoController::class,'getPhTrazable']); 
    Route::post('captura/getPhCalidad',[CampoController::class,'getPhCalidad']);
    Route::post('captura/getConTrazable',[CampoController::class,'getConTrazable']);
    Route::post('captura/getConCalidad',[CampoController::class,'getConCalidad']);
    Route::post('captura/getFactorAplicado', [CampoController::class,'getFactorAplicado']);

    // Guardar datos
    Route::post('captura/setDataGeneral', [CampoController::class,'setDataGeneral']);
    Route::post('captura/setDataMuestreo', [CampoController::class,'setDataMuestreo']);
    Route::post('captura/setDataCompuesto', [CampoController::class, 'setDataCompuesto']);

    //todo Configuracion de bitacora de campo
    Route::get('configuracion/configPlan',[CampoController::class,'configPlan']);
    Route::post('configuracion/getPaquetes',[CampoController::class,'getPaquetes']);
    Route::post('configuracion/getEnvase',[CampoController::class,'getEnvase']);

        Route::post('configuracion/getPlanMuestreo',[CampoController::class,'getPlanMuestreo']);
        Route::post('configuracion/setPlanMuestreo',[CampoController::class,'setPlanMuestreo']);

        Route::post('configuracion/getMaterial',[CampoController::class,'getMaterial']);
        Route::post('configuracion/getEquipo',[CampoController::class,'getEquipo']);
        Route::post('configuracion/getComplementoCamp',[CampoController::class,'getComplementoCamp']);

        Route::post('configuracion/getComplemento',[CampoController::class,'getComplemento']);
        Route::post('configuracion/setComplemento',[CampoController::class,'setComplemento']);
        
    //pdf 
    Route::get('hojaCampo/{id}', [CampoController::class,'hojaCampo']);
    Route::get('bitacoraCampo/{id}', [CampoController::class,'bitacoraCampo']);
    Route::get('planMuestreo/{idSolicitud}', [CampoController::class,'planMuestreo']);
});