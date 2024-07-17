<?php 

use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'notificacion'], function () {
  
  //rutas para el chat
   
  Route::get('/groups', [GroupController::class, 'index']);
  Route::post('/groups', [GroupController::class, 'store']);
  Route::post('/saveColor', [GroupController::class, 'saveColor']);
  Route::post('/groups/{id}/update-color', [GroupController::class, 'updateColor']);


  Route::get('/groups', [GroupController::class, 'getGroups']);
 
  Route::get('/groups/{id}/details', [GroupController::class, 'getGroupDetails']);
  Route::post('/groups/{id}/edit', [GroupController::class, 'editGroup']);


  Route::get('/groups/{group}/messages', [GroupController::class, 'messages']);
  Route::get('/download/{file}', [MessageController::class, 'download'])->name('download.file');
  Route::post('/messages', [MessageController::class, 'store']);
  Route::get('asignarUser', [GroupController::class, 'asignarUser']);
  
  Route::post('/count/{group}/', [GroupController::class, 'resetMessage'])->name('group.resetMessage');
  Route::get('/message/count', [MessageController::class, 'getMessageCount'])->name('message.count');
  

  Route::get('/ruta', function () {
    return view('Chat.chat'); 
});


});