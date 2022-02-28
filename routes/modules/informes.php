<?

use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']);

    //Rutas temporales para la generación de plantillas de las bitácoras
    Route::get('exportPdfSinComparacion', [InformesController::class, 'pdfSinComparacion']);
    Route::get('exportPdfComparacion', [InformesController::class, 'pdfComparacion']);
});
