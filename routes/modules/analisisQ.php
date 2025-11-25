<?php

use App\Http\Controllers\AnalisisQ\ImportarController;
use App\Http\Controllers\AnalisisQ\LimitesController;
use App\Http\Controllers\AnalisisQ\LimitesNormaController;
use App\Http\Controllers\AnalisisQ\NormaController;
use App\Http\Controllers\AnalisisQ\ParametroController;
use App\Http\Controllers\AnalisisQ\FormulasController;
use App\Http\Controllers\AnalisisQ\EnvasesController;
use App\Http\Controllers\AnalisisQ\AnalisisController;
use App\Http\Controllers\AnalisisQ\ConcentracionController;
use App\Http\Controllers\Notificacion\NotificacionController;


use Illuminate\Support\Facades\Route;
 

 
Route::group(['prefix' => 'analisisQ'], function () {
 
    //rutas de prueba para realizar las notificaciones 
    Route::get('/notificaciones', [NotificacionController::class, 'obtenerNotificaciones']);
    Route::get('/contador-notificaciones', [NotificacionController::class, 'ContNot']);
    Route::get('VerNot', [NotificacionController::class, 'obtenerNotificaciones']);

    Route::get('parametros', [ParametroController::class,'index']); 
    Route::post('getParametros',[ParametroController::class,'getParametros']);
    Route::post('getDatoParametro',[ParametroController::class,'getDatoParametro']);
    Route::post('setParametros',[ParametroController::class,'setParametros']);
    Route::post('updateParametro',[ParametroController::class,'updateParametro']);
    Route::get('limiteAli', [ParametroController::class,'limiteAli']); 
    Route::post('setLimiteAli', [ParametroController::class,'setLimiteAli']); 
    Route::post('getParametrosAli', [ParametroController::class,'getParametrosAli']); 
    Route::post('delParametroAli', [ParametroController::class,'delParametroAli']); 
    Route::post('getDataParametroAli', [ParametroController::class,'getDataParametroAli']); 
    Route::post('updateParaAli', [ParametroController::class,'updateParaAli']); 
    
    
    

    Route::get('normas', [NormaController::class,'index']);

    Route::get('detalle_normas/{id}', [NormaController::class,'show']);
    Route::get('detalle_normas/{id}/{idSub}', [NormaController::class,'details']);
    Route::post('detalle_normas/setStd', [NormaController::class,'setStd']);
    //Peticiones 
    Route::post('detalle_normas/createNormaParametro', [NormaController::class,'createNormaParametro']);
    Route::post('detalle_normas/getParametro', [NormaController::class,'getParametro']);
    Route::post('detalle_normas/getParametroNorma', [NormaController::class,'getParametroNorma']);
    
    Route::get('limites', [LimitesController::class,'index']);
    Route::get('limites/{idNorma}', [LimitesController::class,'show']); 
    Route::get('limites/{idNorma}/{idParametro}', [LimitesController::class,'details']); 

    Route::get('LimitesNorma', [LimitesNormaController::class, 'index']);
    Route::post('LimitesNorma/parametros', [LimitesNormaController::class, 'getParametros']);

    Route::get('importar', [ImportarController::class,'index']);
    Route::post('importar/create', [ImportarController::class,'create']); 
 
    Route::get('formulas',[FormulasController::class, 'index']);
    Route::get('formulas/crear',[FormulasController::class, 'crearFormula']);
    Route::post('formulas/getVariables',[FormulasController::class,'getVariables']);
    Route::post('formulas/probarFormula',[FormulasController::class,'probarFormula']); 
    Route::get('formulas/nivel',[FormulasController::class,'nivel']);
    Route::get('formulas/crear_nivel',[FormulasController::class,'crear_nivel']); 
    Route::post('formulas/create',[FormulasController::class,'create']); 
    Route::post('formulas/createNiveles',[FormulasController::class,'createNiveles']); 

    Route::get('envases',[EnvasesController::class, 'index']);
    Route::get('envasesParametro',[EnvasesController::class, 'envase']);
    // Route::get('analisis',[AnalisisController::class, 'index']);
 
    Route::get('formulas/constantes',[FormulasController::class,'constantes']); 
    Route::post('formulas/constante_create',[FormulasController::class,'constante_create']); 

    Route::get('formulas/crear/{idFormula}',[FormulasController::class, 'editar_formula']);
    Route::post('formulas/update',[FormulasController::class, 'update']); 

    Route::get('concentracion',[ConcentracionController::class,'index']); 
    Route::post('concentracion/getParametroNorma',[ConcentracionController::class,'getParametroNorma'] );
    Route::post('concentracion/getConcentracionParametro',[ConcentracionController::class,'getConcentracionParametro'] );
    Route::post('concentracion/setConcentracionParametro',[ConcentracionController::class,'setConcentracionParametro'] );
    // Route::get('concentracion', function () {
    //     echo 'Concentracion';
    // });
});
  