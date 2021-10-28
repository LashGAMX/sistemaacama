<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {
    Route::get('analisis',[LaboratorioController::class,'analisis'] );
    
    Route::get('observacion',[LaboratorioController::class,'observacion']);
    Route::post('getObservacionanalisis', [LaboratorioController::class,'getObservacionanalisis']);

    Route::get('tipoAnalisis',[LaboratorioController::class,'tipoAnalisis']);
    Route::get('captura',[LaboratorioController::class,'captura']);
    
    Route::get('lote',[LaboratorioController::class,'lote']);
    Route::post('createLote', [LaboratorioController::class,'createLote']);
    Route::post('buscarLote', [LaboratorioController::class,'buscarLote']);
    Route::get('asgnarMuestraLote/{id}', [LaboratorioController::class,'asgnarMuestraLote']);

    Route::get('asignar',[LaboratorioController::class,'asignar']);

    Route::get('curva',[CurvaController::class,'index']);
    Route::post('promedio',[CurvaController::class, 'promedio']);
    Route::post('guardar',[CurvaController::class, 'guardar']);
    Route::post('formula',[CurvaController::class, 'formula']);
    Route::post('create',[CurvaController::class, 'create']);
    Route::get('buscar/{idLote}',[CurvaController::class, 'buscar']);

    //---------------------------------Rutas Ajax----------------------------------
    Route::get('analisis/datos', [LaboratorioController::class, 'analisisDatos']);

    //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
    Route::post('lote/procedimiento', [LaboratorioController::class, 'guardarTexto']);

    //Recupera el texto almacenado en el campo Texto de la tabla reportes
    Route::get('lote/procedimiento/busquedaPlantilla', [LaboratorioController::class, 'busquedaPlantilla']);

    //---------------------------------Ruta exportPDF------------------------------
    Route::get('captura/exportPdfCaptura/{formulaTipo}', [LaboratorioController::class, 'exportPdfCaptura']);

});
