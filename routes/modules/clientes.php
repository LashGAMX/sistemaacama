<?php

use App\Http\Controllers\Clientes\ClienteController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Clientes\ImportController;
use App\Http\Controllers\Clientes\IntermediarioController;
use App\Http\Livewire\Historial\Clientes;
use Illuminate\Support\Facades\Route;

Route::get('clientes/intermediarios', [IntermediarioController::class,'index']);

Route::get('clientes/clientes', [ClienteController::class,'index']);
Route::get('clientes/cliente_detalle/{id}', [ClienteController::class,'show']);
Route::get('clientes/cliente_detalle/{id}/{idSuc}', [ClienteController::class,'details']);

Route::post('clientes/datosGenerales', [ClienteController::class,'datosGenerales']);
Route::post('clientes/getDatosGenerales', [ClienteController::class,'getDatosGenerales']);
Route::post('clientes/setDatosGenerales', [ClienteController::class,'setDatosGenerales']);
Route::post('clientes/getContactoGeneral', [ClienteController::class,'getContactoGeneral']);
Route::post('clientes/storeContactoGeneral', [ClienteController::class,'storeContactoGeneral']);

Route::get('clientes/importar',[ImportController::class,'index'] );
Route::post('clientes/importar/create',[ImportController::class,'create'] );


//aqui erea js antes 
Route::get('clientes/clientesGen', [ClientesController::class,'clientesGen']);
Route::get('clientes/clientesGenDetalle/{id}', [ClientesController::class,'clientesGenDetalle']);

Route::get('clientes/datosClientes/{id}', [ClientesController::class, 'TablaSucursal']);

Route::post('clientes/getClientesGen', [ClientesController::class,'getClientesGen']);
Route::post('clientes/setClientesGen', [ClientesController::class,'setClientesGen']);
Route::post('clientes/upClientesGen', [ClientesController::class,'upClientesGen']);
