<?php
use App\Http\Controllers\Notificacion\NotificacionController;
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
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Informes\InformesController;
use App\Http\Controllers\Seguimiento\SeguimientoController;
use App\Mail\ConfirmacionMailable;


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
    
    // Route::get('cadena-custodia-interna/{id}',[ClientesAcamaController::class,'cadenacustodiainterna']);
    // Route::get('informe-de-resultados-acama/{id}',[ClientesAcamaController::class,'informederesultados']);
    Route::get('cadena-custodia-interna/{id}',function(){echo "<br><center><h1>Deshabilitado temporalmente por mantenimiento</h1></center>";});
    Route::get('informe-de-resultados-acama/{id}',function(){echo "<br><center><h1>Deshabilitado temporalmente por mantenimiento</h1></center>";});
    Route::get('informe-de-resultados-acama-mensual/{id}/{id2}',function(){echo "<br><center><h1>Deshabilitado temporalmente por mantenimiento</h1></center>";});
}); 

Route::group(['prefix' => 'admin'], function (){
    //? Cliente seguimiento
    Route::get('seguimiento',[SeguimientoController::class,'index']);
    // Ordenamiento
    Route::get('ordenJson',[HomeController::class,'ordenJson']);
    
   
});


Route::get('/email', function (){
    //return new ConfirmacionMailable();
    Mail::to('isaacyannis@gmail.com')->send(new ConfirmacionMailable);
    return 'Email enviado';
});
