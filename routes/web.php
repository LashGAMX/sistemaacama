<?php

use App\Http\Controllers\Beto\BetoController;
use App\Http\Controllers\Campo\CampoController;
use App\Http\Controllers\ClientesAcama\ClientesAcamaController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Cotizacion\SolicitudController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Pruebas\PruebaController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Informes\InformesController;
use App\Http\Controllers\Seguimiento\SeguimientoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|-------------------------------------------------------------------------- 
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
 return redirect()->to('admin');
});
Route::get('/jsonParametros',[HomeController::class,'jsonParametros']);


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
}); 
Route::group(['prefix' => 'clientes'], function () {
    // Route::get('orden_servicio/{idOrden}',[SolicitudController::class,'exportPdfOrden']);
    // Route::get('informeMensualSinComparacion/{idSol}', [InformesController::class, 'pdfSinComparacionCliente']);
    // Route::get('informeMensualConComparacion/{idSol}', [InformesController::class, 'pdfComparacionCliente']);
    // Route::get('exportPdfSinComparacion/{idSol}', [InformesController::class, 'pdfSinComparacionCli']);
    // Route::get('exportPdfConComparacion/{idSol}', [InformesController::class, 'pdfConComparacionCli']);
    // Route::get('exportPdfCustodiaInterna/{idSol}', [InformesController::class, 'custodiaInternaCli']);
    // Route::get('hojaCampo/{id}', [CampoController::class, 'hojaCampoCli']);
    Route::get('cadena-custodia-interna/{id}',[ClientesAcamaController::class,'cadenacustodiainterna']);
    Route::get('informe-de-resultados-acama/{id}',[ClientesAcamaController::class,'informederesultados']);
}); 

Route::group(['prefix' => 'admin'], function (){
    //? Cliente seguimiento
    Route::get('seguimiento',[SeguimientoController::class,'index']);
});
