<?

use App\Http\Controllers\Controller;
use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']);
    Route::post('/getPuntoMuestro',[InformesController::class,'getPuntoMuestro']);
    Route::post('/getSolParametro',[InformesController::class,'getSolParametro']);
    
    Route::get('exportPdfSinComparacion/{idSol}', [InformesController::class, 'pdfSinComparacion']);
    Route::get('exportPdfConComparacion/{idSol}', [InformesController::class, 'pdfConComparacion']);

    Route::get('/mensual', [InformesController::class, 'mensual']);
    //Ruta temporal para la generación de plantilla de custodia interna
    Route::get('exportPdfCustodiaInterna/{idSol}', [InformesController::class, 'custodiaInterna']);
});
