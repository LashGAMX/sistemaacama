<?php
use App\Http\Controllers\Notificacion\NotificacionController;
use App\Http\Controllers\supervicion\SupervicionController;
use Illuminate\Support\Facades\Route;
use App\Models\Notificacion;

Route::group(['prefix' => 'notificacion'], function () {

//rutas de Prueba para realizar las notificaciones 
Route::get('ContNot', [NotificacionController::class, 'ContNot']);
Route::get('verNotificaciones', [NotificacionController::class, 'verNotificaciones'])->name('verNotificaciones');
//Route::post('Marcarleido',[NotificacionController::class,'Marcarleido']);
Route::get('obtenerYMarcarLeidas', [NotificacionController::class, 'obtenerYMarcarLeidas']);
Route::get('liberarTodo', [SupervicionController::class,'liberarTodo']);
});