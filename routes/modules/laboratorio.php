<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laboratorio'], function () {
    Route::get('analisis',[LaboratorioController::class,'analisis'] );
    Route::get('observacion',[LaboratorioController::class,'observacion']);
    Route::get('tipoAnalisis',[LaboratorioController::class,'tipoAnalisis']);
    Route::get('captura',[LaboratorioController::class,'captura']);
    Route::get('lote',[LaboratorioController::class,'lote']);
    Route::get('asignar',[LaboratorioController::class,'asignar']);

    Route::get('curva',[CurvaController::class,'index']);
    Route::post('promedio',[CurvaController::class, 'promedio']);

    //---------------------------------Rutas Ajax----------------------------------
    Route::get('analisis/datos', [LaboratorioController::class, 'analisisDatos']);

    //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
    Route::post('lote/procedimiento', [LaboratorioController::class, 'guardarTexto']);

    //Recupera el texto almacenado en el campo Texto de la tabla reportes
    Route::get('lote/procedimiento/busquedaPlantilla', [LaboratorioController::class, 'busquedaPlantilla']);

});
