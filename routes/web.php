<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Pruebas\PruebaController;
use App\Http\Middleware\Authenticate;
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

Route::get('prueba', [PruebaController::class,'index']);
Route::get('/home/{name}',[HomeController::class,'index']);
Route::get('/home',[HomeController::class,'index']);
Route::post('/home/create',[HomeController::class,'create']);

Route::get('/', function () {
 return redirect()->to('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
