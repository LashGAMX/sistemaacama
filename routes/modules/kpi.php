<?php

use App\Http\Controllers\kpi\KpiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'kpi'], function () { 

    Route::get('/', [KpiController::class, 'index']);
});
