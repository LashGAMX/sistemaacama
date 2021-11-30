<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Http\Controllers\Laboratorio\MetalesController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {

    //! Grupo de metales
    Route::group(['prefix' => 'metales'], function () {
        //? Modulo Analisis -  Solo visualizar analisis pendientes
        Route::get('analisis', [MetalesController::class, 'analisis']);

        //? Modulo Observacion - Agregar observaciones a analisis
        Route::get('observacion', [MetalesController::class, 'observacion']);
        Route::post('getObservacionanalisis', [MetalesController::class, 'getObservacionanalisis']);
        Route::post('aplicarObservacion', [MetalesController::class, 'aplicarObservacion']);

        Route::get('tipoAnalisis', [MetalesController::class, 'tipoAnalisis']);
        Route::get('captura', [MetalesController::class, 'captura']);
        Route::post('getDataCaptura', [MetalesController::class, 'getDataCaptura']);
        Route::post('setControlCalidad', [MetalesController::class, 'setControlCalidad']);
        Route::post('liberarMuestraMetal', [MetalesController::class, 'liberarMuestraMetal']);

        //? Modulo Lote - Creación  de lotes
        Route::get('lote', [MetalesController::class, 'lote']);
        Route::post('createLote', [MetalesController::class, 'createLote']);
        Route::post('buscarLote', [MetalesController::class, 'buscarLote']);
        Route::post('getDatalote', [MetalesController::class, 'getDatalote']);
        Route::get('asgnarMuestraLote/{id}', [MetalesController::class, 'asgnarMuestraLote']);
        Route::post('muestraSinAsignar', [MetalesController::class, 'muestraSinAsignar']);
        Route::post('asignarMuestraLote', [MetalesController::class, 'asignarMuestraLote']);
        Route::post('getMuestraAsignada', [MetalesController::class, 'getMuestraAsignada']);
        Route::post('delMuestraLote', [MetalesController::class, 'getMuestraAsignada']); 
        
        //Route::get('analisis/datos', [MetalesController::class, 'analisisDatos']);
        Route::post('operacion', [MetalesController::class, 'operacion']);
        Route::post('lote/equipo/guardarDatosGenerales', [MetalesController::class, 'guardarDatosGenerales']);
        Route::post('getDataLote/plantillaPredeterminada', [MetalesController::class, 'getPlantillaPred']);
        Route::post('lote/procedimiento/busquedaFiltros', [MetalesController::class, 'busquedaFiltros']);  
        //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
        Route::post('lote/procedimiento', [MetalesController::class, 'guardarTexto']);
    });

    Route::group(['prefix' => 'fq'], function () {
        
    });

    //? Módulo curva - Creación de curva
    Route::get('asignar', [MetalesController::class, 'asignar']); 
    Route::get('curva', [CurvaController::class, 'index']);
    Route::post('promedio', [CurvaController::class, 'promedio']);
    Route::post('guardar', [CurvaController::class, 'guardar']);
    Route::post('formula', [CurvaController::class, 'formula']);
    Route::post('buscar', [CurvaController::class, 'buscar']);
    Route::post('createStd', [CurvaController::class, 'createStd']);
    Route::post('getParametro', [CurvaController::class, 'getParametro']);
    Route::post('setCalcular', [CurvaController::class, 'setCalcular']);
    Route::post('setConstantes', [CurvaController::class, 'setConstantes']);


    //? PDF
    Route::get('captura/exportPdfCaptura/{idLote}', [LaboratorioController::class, 'exportPdfCaptura']);
    /*Route::get('captura/exportPdfCaptura/{formulaTipo}/{numeroMuestra}/{idLote}', [LaboratorioController::class, 'exportPdfCaptura'])->where('numeroMuestra', '(.*)');*/
});
