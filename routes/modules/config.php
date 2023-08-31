<?php

use App\Http\Controllers\Config\AnalisisController;
use App\Http\Controllers\Config\CampoController;
use App\Http\Controllers\Config\LaboratorioController;
use App\Http\Controllers\Config\ConfiguracionesController;
use App\Http\Controllers\Config\PlantillasController;
use App\Http\Controllers\Config\ReportesController;
use App\Http\Livewire\Counter;
use App\Models\PlantillasFq;
use Illuminate\Support\Facades\Route;

Route::get('config/config', [ConfiguracionesController::class, 'index']);
Route::get('config/laboratorio', [LaboratorioController::class,'index']);
Route::get('config/analisis', [AnalisisController::class,'index']);
Route::get('config/campo', [CampoController::class,'index']);
Route::get('config/termometros', [CampoController::class,'termometros']);
Route::get('config/reportes', [ReportesController::class,'index']);

Route::group(['prefix' => 'config/plantillas'], function () {
    Route::get('/', [PlantillasController::class,'index']);

    // Route::get('/bitacoras/{tipo}', [PlantillasController::class,'bitacoras']);
    Route::get('/bitacoras', [PlantillasController::class,'bitacoras']);
    Route::post('/getPlantillas',[PlantillasController::class,'getPlantillas']);
    Route::post('/getDetalleBitacora',[PlantillasController::class,'getDetalleBitacora']);
    Route::post('/setPlantilla',[PlantillasController::class,'setPlantilla']); 
    Route::post('/setNewPlantilla',[PlantillasController::class,'setNewPlantilla']);
});

