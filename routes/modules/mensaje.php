<?php 

use App\Http\Controllers\Chat\GrupoController; 
use App\Http\Controllers\chat\MensajeController; 

use Illuminate\Support\Facades\Route;

// Define tus rutas aquí
Route::group(['prefix' => 'chat'], function () {

  Route::get('getGroups', [GrupoController::class, 'getGroups']);
  Route::get('asignarUser', [GrupoController::class, 'asignarUser']);
  Route::post('store', [GrupoController::class, 'store']);

  // Route::get('chat-view', function () {
  //   return view('Chat.chat'); 
  // });


  //RUTAS NUEVAS PARA PRODUCCION 
  Route::get('getGroupDetails/{id}', [GrupoController::class, 'getGroupDetails']);
  Route::post('editGroup/{id}', [GrupoController::class, 'editGroup']);
  Route::post('updateColor/{id}', [GrupoController::class, 'updateColor']);

  Route::post('CountGrupo/{group}', [GrupoController::class, 'CountGrupo']);
  Route::get('ContGen', [MensajeController::class, 'ContGen']);


  Route::post('mensaje', [MensajeController::class, 'mensaje']);
  Route::get('messages/{group}', [GrupoController::class, 'messages']);

});