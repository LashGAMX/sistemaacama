<?php

use App\Http\Controllers\Beto\BetoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Cotizacion\SolicitudController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Pruebas\PruebaController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
Route::group(['prefix' => 'clientes'], function () {
    Route::get('orden_servicio/{idOrden}',[SolicitudController::class,'exportPdfOrden']);
});



// Route::get('animacion', [BetoController::class,'animacion']);