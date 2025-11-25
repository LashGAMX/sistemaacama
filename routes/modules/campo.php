<?php

use App\Http\Controllers\Campo\CampoController;
use App\Http\Controllers\Beto\BetoController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'campo'], function () { 
    Route::get('asignar', [CampoController::class, 'asignar']);
    Route::get('capturar', [CampoController::class, 'listaMuestreo']);
    Route::get('captura/{id}', [CampoController::class, 'captura']);
    Route::post('captura/buscar', [CampoController::class, 'buscar']); 
    Route::post('asignar/generar', [CampoController::class, 'generar']); 
    Route::post('asignar/asignarMultiple',[CampoController::class, 'asignarMultiple']); 
    Route::post('asignar/setMuestreadorMultiple',[CampoController::class, 'setMuestreadorMultiple']); 
    Route::post('setObservacion',[CampoController::class,'setObservacion']);
    // Route::post('campo/asignar/generarUpdate', [CampoController::class,'generarUpdate']);

    Route::post('asignar/getFolio', [CampoController::class, 'getFolio']);
    Route::post('asignar/buesquedaFecha', [CampoController::class, 'buesquedaFecha']);
    Route::get('updatePhTrazableCaptura', [CampoController::class,'updatePhTrazableCaptura']);
    Route::group(['prefix' => 'captura'], function () {
        Route::post('getPhTrazable', [CampoController::class, 'getPhTrazable']);
        Route::post('getPhCalidad', [CampoController::class, 'getPhCalidad']);
        Route::post('getConTrazable', [CampoController::class, 'getConTrazable']);
        Route::post('getConCalidad', [CampoController::class, 'getConCalidad']);
        Route::post('setDataGeneral', [CampoController::class, 'setDataGeneral']);
        Route::post('getFactorCorreccion', [CampoController::class, 'getFactorCorreccion']);
        Route::post('generarVmsi', [CampoController::class, 'generarVmsi']);

        //guardado de tablas
        Route::post('GuardarPhMuestra', [CampoController::class, 'GuardarPhMuestra']);
        Route::post('GuardarTempAgua', [CampoController::class, 'GuardarTempAgua']);
        Route::post('GuardarTempAmb', [CampoController::class, 'GuardarTempAmb']);
        Route::post('GuardarPhControlCalidad', [CampoController::class, 'GuardarPhControlCalidad']);
        Route::post('GuardarConductividad', [CampoController::class, 'GuardarConductividad']);
        Route::post('GuardarGasto', [CampoController::class, 'GuardarGasto']);
        Route::post('GuardarVidrio', [CampoController::class, 'GuardarVidrio']);

        Route::get('Update_reviso', [CampoController::class, 'listaMuestreo']);

        Route::post('SetDatosCompuestos', [CampoController::class, 'SetDatosCompuestos']);
        Route::post('CancelarMuestra', [CampoController::class, 'CancelarMuestra']);
        Route::post('CancelarPunto', [CampoController::class, 'CancelarPunto']);

        Route::post('setEvidencia', [CampoController::class, 'setEvidencia']);
        Route::post('setEvidenciaFirma', [CampoController::class, 'setEvidenciaFirma']);
    });


    //todo Configuracion de bitacora de campo
    Route::get('configuracion/configPlan', [CampoController::class, 'configPlan']);
    Route::post('configuracion/getPaquetes', [CampoController::class, 'getPaquetes']);
    Route::post('configuracion/getEnvase', [CampoController::class, 'getEnvase']);

    Route::post('configuracion/getPlanMuestreo', [CampoController::class, 'getPlanMuestreo']);
    Route::post('configuracion/setPlanMuestreo', [CampoController::class, 'setPlanMuestreo']);

    Route::post('configuracion/getMaterial', [CampoController::class, 'getMaterial']);
    Route::post('configuracion/getEquipo', [CampoController::class, 'getEquipo']);
    Route::post('configuracion/getComplementoCamp', [CampoController::class, 'getComplementoCamp']);

    Route::post('configuracion/getComplemento', [CampoController::class, 'getComplemento']);
    Route::post('configuracion/setComplemento', [CampoController::class, 'setComplemento']);

    //pdf 
    Route::get('hojaCampo/{id}', [CampoController::class, 'hojaCampo']);
    Route::get('bitacoraCampo/{id}', [CampoController::class, 'bitacoraCampo']);
    Route::get('planMuestreo/{idSolicitud}', [CampoController::class, 'planMuestreo']);

    Route::get('EliminarCampo/{id}', [CampoController::class, 'EliminarCampo']);


});
