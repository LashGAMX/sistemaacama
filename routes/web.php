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
use TCG\Voyager\Facades\Voyager;


Route::get('/', function () {
 return redirect()->to('admin'); 
});
Route::get('/jsonParametros',[HomeController::class,'jsonParametros']);
Route::get('/getRespaldoObservacion',[HomeController::class,'getRespaldoObservacion']);
Route::get('/getRegresarMuestraParametro',[HomeController::class,'getRegresarMuestraParametro']);

Route::get('/pruebaFuncion',function(){
    echo Auth::user()->role_id;
});


Route::group(['prefix' => 'admin'], function () { 
    Voyager::routes(); 
});  
Route::group(['prefix' => 'clientes'], function () {
    
    // Route::get('cadena-custodia-interna/{id}',[ClientesAcamaController::class,'cadenacustodiainterna']);
    // Route::get('informe-de-resultados-acama/{id}',[ClientesAcamaController::class,'informederesultados']);
     Route::get('cadena-custodia-interna/{id}',function(){echo "<br><center><h1>Deshabilitado temporalmente por mantenimiento</h1></center>";});
    Route::get('informe-de-resultados-acama/{id}', [ClientesAcamaController::class, 'getDatosInforme'])
    ->where('id', '.+');
    // Route::get('informe-de-resultados-acama/{id}',function(){echo "<br><center><h1>Prueba</h1></center>";});
    // Route::get('informe-de-resultados-acama-mensual/{id}/{id2}',function(){echo "<br><center><h1>Deshabilitado temporalmente por mantenimiento</h1></center>";});
    Route::get('seguimiento-servicio', [ClientesAcamaController::class, 'seguimientoServicio']);
    Route::get('validacion-informe-diario', [ClientesAcamaController::class, 'validacionInformeDiario']);
    Route::post('getFolioServicio', [ClientesAcamaController::class, 'getFolioServicio']);


    //Peticiones
    Route::post('getFirmaEncriptada', [ClientesAcamaController::class, 'getFirmaEncriptada']);
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
