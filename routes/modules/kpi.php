<?php

use App\Http\Controllers\kpi\KpiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'kpi'], function () { 

    Route::get('/', [KpiController::class, 'index']);
    Route::post('/getMuestrasPendientes', [KpiController::class, 'getMuestrasPendientes']);
    Route::post('/indicadores', [KpiController::class, 'indicadores']); 

    Route::post('/solicitudesGeneradas', [KpiController::class, 'solicitudesGeneradas']); 
    Route::post('/cotizacionesGeneradas', [KpiController::class, 'cotizacionesGeneradas']); 
    Route::post('/ordenServicioProceso', [KpiController::class, 'ordenServicioProceso']); 
});

