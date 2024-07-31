<?php 

use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'mensaje'], function () {
  

  Route::get('/ruta', function () {
  return view('Chat.chat'); 
});


Route::get('getGroups', [GroupController::class, 'getGroups']);
Route::get('getGroupDetails', [GroupController::class, 'getGroupDetails']);
Route::post('editGroup', [GroupController::class, 'editGroup']);



});