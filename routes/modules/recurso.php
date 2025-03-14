<?php

use App\Http\Controllers\Mail\MailController;
use App\Http\Controllers\Recursos\RecursoController;
use App\Http\Controllers\Recursos\CampoController;
use App\Http\Controllers\Recursos\AppController;
use Illuminate\Support\Facades\Route;

Route::get('recursos', [RecursoController::class, 'index']);
Route::get('recursos/ingCampo', [CampoController::class, 'index']);
Route::get('recursos/ingCampo/app', [AppController::class, 'index']);
Route::get('recursos/recepcion', [RecursoController::class, 'recepcion']);
Route::get('recursos/recepcion/appRecepcion', [RecursoController::class, 'appRecepcion']);
Route::get('recursos/basura', [RecursoController::class, 'basura']);
Route::post('recursos/buscar', [RecursoController::class, 'buscarBasura']);
Route::post('recursos/eliminar', [RecursoController::class, 'tirarlabasura']);
Route::post('recursos/reasignar', [RecursoController::class, 'reasignar']);

Route::get('sendmail', [MailController::class, 'mail']);
Route::get('viewmail', [MailController::class, 'viewmail']);