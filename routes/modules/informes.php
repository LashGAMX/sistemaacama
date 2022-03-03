<?

use App\Http\Controllers\Controller;
use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']);
    Route::post('/getPuntoMuestro',[InformesController::class,'getPuntoMuestro']);
    Route::post('/getSolParametro',[InformesController::class,'getSolParametro']);

    //Rutas temporales para la generación de plantillas de las bitácoras
    Route::get('exportPdfSinComparacion/{idSol}', [InformesController::class, 'pdfSinComparacion']);
    Route::get('exportPdfConComparacion/{idSol}', [InformesController::class, 'pdfComparacion']);
});
