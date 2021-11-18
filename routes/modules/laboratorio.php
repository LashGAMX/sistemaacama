<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {
    Route::get('analisis',[LaboratorioController::class,'analisis'] );
    
    //*************************************OBSERVACIÓN*********************************** */
    Route::get('observacion',[LaboratorioController::class,'observacion']);
    Route::post('getObservacionanalisis', [LaboratorioController::class,'getObservacionanalisis']);
    Route::post('aplicarObservacion', [LaboratorioController::class,'aplicarObservacion']);

    Route::get('tipoAnalisis',[LaboratorioController::class,'tipoAnalisis']);
    Route::get('captura',[LaboratorioController::class,'captura']);
    Route::post('getDataCaptura', [LaboratorioController::class,'getDataCaptura']);
    Route::post('setControlCalidad', [LaboratorioController::class,'setControlCalidad']);
    Route::post('liberarMuestraMetal', [LaboratorioController::class,'liberarMuestraMetal']);
    
    
    
    Route::get('lote',[LaboratorioController::class,'lote']);
    Route::post('createLote', [LaboratorioController::class,'createLote']);
    Route::post('buscarLote', [LaboratorioController::class,'buscarLote']);
    Route::post('getDatalote', [LaboratorioController::class,'getDatalote']);
    Route::get('asgnarMuestraLote/{id}', [LaboratorioController::class,'asgnarMuestraLote']);
    Route::post('muestraSinAsignar',[LaboratorioController::class,'muestraSinAsignar']);
    Route::post('asignarMuestraLote',[LaboratorioController::class,'asignarMuestraLote']);
    Route::post('getMuestraAsignada',[LaboratorioController::class,'getMuestraAsignada']);
    Route::post('delMuestraLote',[LaboratorioController::class,'delMuestraLote']);

    

    Route::get('asignar',[LaboratorioController::class,'asignar']);

    Route::get('curva',[CurvaController::class,'index']);
    Route::post('promedio',[CurvaController::class, 'promedio']);
    Route::post('guardar',[CurvaController::class, 'guardar']);
    Route::post('formula',[CurvaController::class, 'formula']);
    Route::post('buscar',[CurvaController::class, 'buscar']);
    Route::post('createStd',[CurvaController::class, 'createStd']);
    Route::post('getParametro',[CurvaController::class, 'getParametro']);
    Route::post('setCalcular',[CurvaController::class, 'setCalcular']);
    Route::post('setConstantes',[CurvaController::class, 'setConstantes']);
    //---------------------------------Rutas Ajax----------------------------------
    Route::get('analisis/datos', [LaboratorioController::class, 'analisisDatos']);
    Route::post('operacion', [LaboratorioController::class, 'operacion']); 
    Route::post('lote/equipo/guardarDatosGenerales', [LaboratorioController::class, 'guardarDatosGenerales']);
    Route::post('getDataLote/plantillaPredeterminada', [LaboratorioController::class, 'getPlantillaPred']);

    //ARCHIVO CAPTURA.JS
    Route::post('lote/procedimiento/busquedaFiltros', [LaboratorioController::class, 'busquedaFiltros']);

    //-----------------------------------------------------------------------------

    //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
    Route::post('lote/procedimiento', [LaboratorioController::class, 'guardarTexto']);

    //---------------------------------Ruta exportPDF------------------------------
    Route::get('captura/exportPdfCaptura/{idLote}', [LaboratorioController::class, 'exportPdfCaptura']);
    /*Route::get('captura/exportPdfCaptura/{formulaTipo}/{numeroMuestra}/{idLote}', [LaboratorioController::class, 'exportPdfCaptura'])->where('numeroMuestra', '(.*)');*/
});
