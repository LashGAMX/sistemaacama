<?

use App\Http\Controllers\Controller;
use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route; 
 
Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']); 
    Route::post('/getPuntoMuestro',[InformesController::class,'getPuntoMuestro']);
    Route::post('/getSolParametro',[InformesController::class,'getSolParametro']);
    
    Route::get('exportPdfSinComparacion/{idSol}/{idPunto}', [InformesController::class, 'pdfSinComparacion']);
    Route::get('exportPdfConComparacion/{idSol}/{idPunto}', [InformesController::class, 'pdfConComparacion']);
    Route::get('exportPdfInforme/{idSol}/{idPunto}/{tipo}', [InformesController::class, 'exportPdfInforme']);
    Route::get('exportPdfInformeCampo/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeCampo']);

    Route::get('/mensual', [InformesController::class, 'mensual']);
    Route::post('/getPreReporteMensual', [InformesController::class, 'getPreReporteMensual']);
    //Ruta temporal para la generación de plantilla de custodia interna
    Route::get('exportPdfCustodiaInterna/{idSol}', [InformesController::class, 'custodiaInterna']);
    Route::get('cadena/pdf/{idSol}',[InformesController::class,'exportPdfCustodiaInterna']);


    Route::get('exportPdfInformeMensual/{idSol1}/{idsol2}/{tipo}', [InformesController::class, 'exportPdfInformeMensual']);
    Route::get('exportPdfInformeMensual/001/{idSol1}/{idsol2}/{tipo}', [InformesController::class, 'exportPdfInformeMensual001']);
    Route::get('exportPdfInformeMensualCampo/{idSol1}/{idsol2}', [InformesController::class, 'exportPdfInformeMensualCampo']);
    Route::get('informeMensualSinComparacion/{idSol1}/{idsol2}', [InformesController::class, 'pdfSinComparacion2']);
    Route::get('informeMensualConComparacion/{idSol1}/{idsol2}', [InformesController::class, 'pdfComparacion2']);
});

