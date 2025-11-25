<?php

use App\Http\Controllers\Clientes\ClienteController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Clientes\ImportController;
use App\Http\Controllers\Clientes\IntermediarioController;
use App\Http\Controllers\Historial\ClientesController as HistorialClientesController;
use App\Http\Livewire\Historial\Clientes;
use Illuminate\Support\Facades\Route;

Route::get('clientes/intermediarios', [IntermediarioController::class, 'index']);
Route::get('clientes/clientes', [ClienteController::class, 'index']);
Route::get('clientes/cliente_detalle/{id}', [ClienteController::class, 'show']);
Route::get('clientes/cliente_detalle/{id}/{idSuc}', [ClienteController::class, 'details']);
Route::post('clientes/datosGenerales', [ClienteController::class, 'datosGenerales']);
Route::post('clientes/getDatosGenerales', [ClienteController::class, 'getDatosGenerales']);
Route::post('clientes/setDatosGenerales', [ClienteController::class, 'setDatosGenerales']);
Route::post('clientes/getContactoGeneral', [ClienteController::class, 'getContactoGeneral']);
Route::post('clientes/storeContactoGeneral', [ClienteController::class, 'storeContactoGeneral']);
Route::get('clientes/importar', [ImportController::class, 'index']);
Route::post('clientes/importar/create', [ImportController::class, 'create']);


//aqui erea js antes 
Route::get('clientes/clientesGen', [ClientesController::class, 'clientesGen']);
Route::get('clientes/clientesGenDetalle/{id}', [ClientesController::class, 'clientesGenDetalle']);
Route::get('clientes/datosClientes/{id}', [ClientesController::class, 'TablaSucursal']);
Route::get('clientes/TablaRFC/{idSucursal}', [ClientesController::class, 'TablaRFC']);
Route::get('clientes/TablaDirReport/{idSucursal}', [ClientesController::class, 'TablaDirReport']);
Route::get('clientes/TablaPM/{idSucursal}', [ClientesController::class, 'TablaPM']);
Route::get('clientes/TablaConcesión/{idSucursal}', [ClientesController::class, 'TablaConcesión']);
Route::get('clientes/TablaDireccionSiralab/{idSucursal}', [ClientesController::class, 'TablaDireccionSiralab']);
Route::get('clientes/TablePuntoSiralab/{idSucursal}', [ClientesController::class, 'TablePuntoSiralab']);
Route::post('clientes/getClientesGen', [ClientesController::class, 'getClientesGen']);
Route::post('clientes/setClientesGen', [ClientesController::class, 'setClientesGen']);
Route::post('clientes/upClientesGen', [ClientesController::class, 'upClientesGen']);
Route::get('clientes/Consuc/{idSucursal}', [ClientesController::class, 'Consuc']);
Route::get('clientes/TablaDatosGen/{idSucursal}', [ClientesController::class, 'TablaDatosGen']);

//estas son las rutas que no se visualizan correctamente TablaDatosGen
Route::get('clientes/Nombrematrix/{idCliente}', [ClientesController::class, 'Nombrematrix']);
Route::post('clientes/EditarSuc', [ClientesController::class, 'EditarSuc']);
Route::post('clientes/CrearSuc', [ClientesController::class, 'CrearSuc']);
Route::get('clientes/GetDatosGen/{datos}', [ClientesController::class, 'GetDatosGen']);
Route::get('clientes/GetPunDetails/{punto}', [ClientesController::class, 'GetPunDetails']);
Route::post('clientes/UpdatePunto', [ClientesController::class, 'UpdatePunto']);
Route::get('clientes/GetRFCDetails/{Rfc}', [ClientesController::class, 'GetRFCDetails']);
Route::post('clientes/UpdateRFC', [ClientesController::class, 'UpdateRFC']);
Route::get('clientes/GetDirDetails/{direccion}', [ClientesController::class, 'GetDirDetails']);
Route::post('clientes/UpdateDIRrep', [ClientesController::class, 'UpdateDIRrep']);
Route::get('clientes/GetTituloDetails/{titulo}', [ClientesController::class, 'GetTituloDetails']);
Route::post('clientes/UpdateTitulo', [ClientesController::class, 'UpdateTitulo']);
Route::get('clientes/GetDirSiralbDetails/{direccionReporteSir}', [ClientesController::class, 'GetDirSiralbDetails']);
Route::post('clientes/UpdateTituloRepSir', [ClientesController::class, 'UpdateTituloRepSir']);
Route::post('clientes/CrearDirRepSir', [ClientesController::class, 'CrearDirRepSir']);
Route::post('clientes/NuevoRFC', [ClientesController::class, 'NuevoRFC']);
Route::post('clientes/NuevaDireccion', [ClientesController::class, 'NuevaDireccion']);
Route::post('clientes/NuevoPunto', [ClientesController::class, 'NuevoPunto']);
Route::post('clientes/NuevoTConcesion', [ClientesController::class, 'NuevoTConcesion']);
Route::post('tituloPunSir/{id}', [ClientesController::class,'tituloPunSir']);
Route::post('clientes/EditarDatos', [ClientesController::class,'EditarDatos']);
Route::post('clientes/Precon', [ClientesController::class,'Precon']);
Route::post('clientes/CreatePunSir', [ClientesController::class,'CreatePunSir']);
Route::post('clientes/updatePun', [ClientesController::class, 'updatePun']);
Route::post('clientes/conreg',[ClientesController::class, 'conreg']);


