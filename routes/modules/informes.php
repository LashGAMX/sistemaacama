<?php
use App\Http\Controllers\Informes\InformesController;
use Illuminate\Support\Facades\Route; 
 
 
Route::group(['prefix' => 'informes'], function () {
    Route::get('/', [InformesController::class, 'index']); 
    Route::get('/info', [InformesController::class,'informe2']);
    Route::get('/getinforme', [InformesController::class,'getinforme']);

    Route::get('/firma', [InformesController::class, 'firma']); 
    Route::post('/getPuntoMuestro',[InformesController::class,'getPuntoMuestro']);
    Route::post('/getSolParametro',[InformesController::class,'getSolParametro']); 
    Route::post('/getInformacionPuntosMuestreo',[InformesController::class,'getInformacionPuntosMuestreo']);
    
    Route::get('exportPdfSinComparacion/{idSol}/{idPunto}', [InformesController::class, 'pdfSinComparacion']);
    Route::get('exportPdfConComparacion/{idSol}/{idPunto}', [InformesController::class, 'pdfConComparacion']);
    Route::get('exportPdfInforme/{idSol}/{idPunto}/{tipo}', [InformesController::class, 'exportPdfInforme']);
    //nueva ruta informe general
    Route::get('InformeGeneral/{idSol}/{idPunto}/{tipo}', [InformesController::class, 'InformeGeneral']);

    Route::get('exportPdfInformeCampo/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeCampo']);
    Route::get('exportHojaCampoAdd/{id}', [InformesController::class, 'exportHojaCampoAdd']);
    Route::get('exportPdfInformeVidrio/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeVidrio']);
    Route::get('exportPdfInformeAdd/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeAdd']);

    Route::get('exportPdfInformeEbenhochSin/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeEbenhochSin']);
    Route::get('exportPdfInformeEbenhochSolo/{idSol}/{idPunto}', [InformesController::class, 'exportPdfInformeEbenhochSolo']);
 
    Route::get('/mensual', [InformesController::class, 'mensual']);
    Route::post('/getPreReporteMensual', [InformesController::class, 'getPreReporteMensual']);
    //Ruta temporal para la generación de plantilla de custodia interna
    Route::get('CustodiaInterna/{idSol}/{idPunto}',[InformesController::class,'custodiaInterna']);

    Route::get('cadena/pdf/{idSol}',[InformesController::class,'exportPdfCustodiaInterna']);
    Route::get('cadenavidrio/pdf/{idSol}',[InformesController::class,'exportPdfCustodiaInternaVidrio']);


    Route::get('exportPdfInformeMensual/{idSol1}/{idsol2}/{tipo}', [InformesController::class, 'exportPdfInformeMensual']);
    Route::get('exportPdfInformeMensual/001/{idSol1}/{idsol2}/{tipo}', [InformesController::class, 'exportPdfInformeMensual001']);
    Route::get('exportPdfInformeMensualCampo/{idSol1}/{idsol2}', [InformesController::class, 'exportPdfInformeMensualCampo']);
    Route::get('informeMensualSinComparacion/{idSol1}/{idsol2}', [InformesController::class, 'pdfSinComparacion2']);
    Route::get('informeMensualConComparacion/{idSol1}/{idsol2}', [InformesController::class, 'pdfComparacion2']);

    //! Funciones adicionales
    Route::get('muestrasCanceladas',[InformesController::class,'muestrasCanceladas']);
    Route::post('setNota4',[InformesController::class,'setNota4']);
    Route::post('setFirmaAut',[InformesController::class,'setFirmaAut']);
    Route::post('setfirmaPad',[InformesController::class,'setfirmaPad']);
    
    Route::post('/BuscarMesA',[InformesController::class,'BuscarMesA']); 
    Route::post('/setIncertidumbre',[InformesController::class,'setIncertidumbre']); 


});