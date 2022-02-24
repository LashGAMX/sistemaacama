<?

use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']);

});
