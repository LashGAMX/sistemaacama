<?php

use App\Http\Controllers\Laboratorio\LaboratorioController;
use App\Http\Controllers\Laboratorio\CurvaController;
use App\Http\Controllers\Laboratorio\FqController;
use App\Http\Controllers\Laboratorio\MbController;
use App\Http\Controllers\Laboratorio\MetalesController;
use App\Http\Controllers\laboratorio\MicroController;
use App\Http\Controllers\Laboratorio\VolController;
use App\Http\Controllers\Laboratorio\DirectosController;
use App\Http\Controllers\Laboratorio\PotableController;
use App\Http\Controllers\Seguimiento\SeguimientoController;
use App\Models\Laboratorio; 
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'laboratorio'], function () {

    //! Grupo de metales
    Route::group(['prefix' => 'metales'], function () {
        //? Modulo Analisis -  Solo visualizar analisis pendientes
        Route::get('analisis', [MetalesController::class, 'analisis']);

        //? Modulo Observacion - Agregar observaciones a analisis
        Route::get('observacion', [MetalesController::class, 'observacion']);
        Route::post('getObservacionanalisis', [MetalesController::class, 'getObservacionanalisis']);
        Route::post('getPuntoAnalisis', [MetalesController::class, 'getPuntoAnalisis']);
        Route::post('aplicarObservacion', [MetalesController::class, 'aplicarObservacion']);
        Route::post('getHistorial', [MetalesController::class, 'getHistorial']);
        Route::post('setTituloBit', [MetalesController::class, 'setTituloBit']);
        Route::get('actualizarLiberaciones', [MetalesController::class, 'actualizarLiberaciones']);

        //? Modulo captura de datos analisis
        Route::get('tipoAnalisis', [MetalesController::class, 'tipoAnalisis']);
        Route::get('captura', [MetalesController::class, 'captura']);
        Route::post('getDataCaptura', [MetalesController::class, 'getDataCaptura']);
        Route::post('getCapturaLote', [MetalesController::class, 'getCapturaLote']);
        Route::post('setControlCalidad', [MetalesController::class, 'setControlCalidad']);
        Route::post('liberarMuestraMetal', [MetalesController::class, 'liberarMuestraMetal']);
        Route::post('liberarTodo', [MetalesController::class, 'liberarTodo']); //revisar método

        //? Modulo Lote - Creación  de lotes 
        Route::get('lote', [MetalesController::class, 'lote']);
        Route::post('createLote', [MetalesController::class, 'createLote']);
        Route::post('buscarLote', [MetalesController::class, 'buscarLote']);
        Route::post('getLote', [MetalesController::class, 'getLote']);
        Route::post('getLoteCaptura', [MetalesController::class, 'getLoteCaptura']);
        Route::get('asignar', [MetalesController::class, 'asignar']);
        Route::post('getPendientes', [MetalesController::class, 'getPendientes']);
        // Route::post('getDatalote', [MetalesController::class, 'getDatalote']);
        Route::get('asgnarMuestraLote/{id}', [MetalesController::class, 'asgnarMuestraLote']);
        Route::post('muestraSinAsignar', [MetalesController::class, 'muestraSinAsignar']); 
        Route::post('asignarMuestraLote', [MetalesController::class, 'asignarMuestraLote']);
        Route::post('getMuestraAsignada', [MetalesController::class, 'getMuestraAsignada']);
        Route::post('getMuestras', [MetalesController::class, 'getMuestras']);
        Route::post('delMuestraLote', [MetalesController::class, 'delMuestraLote']);
        Route::post('sendMuestrasLote', [MetalesController::class, 'sendMuestrasLote']);
        Route::post('createControlCalidadMetales', [MetalesController::class, 'createControlCalidadMetales']);
        Route::post('getDetalleLote', [MetalesController::class, 'getDetalleLote']);
        Route::post('setDetalleLote', [MetalesController::class, 'setDetalleLote']);

        //Route::get('analisis/datos', [MetalesController::class, 'analisisDatos']);
        //? Modulo de captura de resultados
        Route::post('createControlCalidad', [MetalesController::class, 'createControlCalidad']);
        Route::post('operacion', [MetalesController::class, 'operacion']);
        Route::post('enviarObservacion', [MetalesController::class, 'enviarObservacion']);
        Route::post('lote/equipo/guardarDatosGenerales', [MetalesController::class, 'guardarDatosGenerales']);
        Route::post('getDataLote/plantillaPredeterminada', [MetalesController::class, 'getPlantillaPred']);
        Route::post('lote/procedimiento/busquedaFiltros', [MetalesController::class, 'busquedaFiltros']);
        //Almacena el texto en la table reportes, campo Texto, el texto introducido en el editor de texto > Procedimiento/Validación
        Route::post('lote/procedimiento', [MetalesController::class, 'guardarTexto']);
        Route::get('exportPdfCaptura/{id}', [MetalesController::class, 'exportPdfCaptura']);
        Route::post('setPlantillaDetalleMetales', [MetalesController::class, 'setPlantillaDetalleMetales']);
        
        Route::get('capturaIcp', [MetalesController::class, 'capturaIcp']);
        Route::post('createLoteIcp', [MetalesController::class, 'createLoteIcp']);
        Route::post('buscarLoteIcp', [MetalesController::class, 'buscarLoteIcp']);
        Route::post('importCvs', [MetalesController::class, 'importCvs']);
        Route::post('getLoteCapturaIcp', [MetalesController::class, 'getLoteCapturaIcp']);
        Route::post('liberarIcp', [MetalesController::class, 'liberarIcp']);
        Route::get('bitacoraIcp/{id}', [MetalesController::class, 'bitacoraIcp']);
        Route::post('getPlantilla', [MetalesController::class, 'getPlantilla']); 
        Route::post('setPlantilla', [MetalesController::class, 'setPlantilla']); 
        //Configuracion metales 
        Route::get('configuracionMetales', [MetalesController::class, 'configuracionMetales']);
        Route::post('getConfiguraciones', [MetalesController::class, 'getConfiguraciones']);
        Route::post('setConfiguraciones', [MetalesController::class, 'setConfiguraciones']);
        
        
    });

    // todo Modulo FisicoQuimicos
    Route::group(['prefix' => 'fq'], function () {
        //? Modulo Analisis -  Solo visualizar analisis pendientes
        Route::get('analisis', [FqController::class, 'analisis']);

        //? Modulo Observacion - Agregar observaciones a analisis
        Route::get('observacion', [FqController::class, 'observacion']);
        Route::post('getObservacionanalisis', [FqController::class, 'getObservacionanalisis']);
        Route::post('aplicarObservacion', [FqController::class, 'aplicarObservacion']);
 
        //? Modulo Lote - Creación  de lotes
        Route::get('lote', [FqController::class, 'lote']); 
        Route::post('createLote', [FqController::class, 'createLote']); 
        Route::post('buscarLote', [FqController::class, 'buscarLote']);
        Route::post('getPendientes', [FqController::class, 'getPendientes']);
        Route::post('getDatalote', [FqController::class, 'getDatalote']);
        Route::get('asgnarMuestraLote/{id}', [FqController::class, 'asgnarMuestraLote']);
        Route::post('muestraSinAsignar', [FqController::class, 'muestraSinAsignar']);
        Route::post('asignarMuestraLote', [FqController::class, 'asignarMuestraLote']);
        Route::post('sendMuestrasLote', [FqController::class, 'sendMuestrasLote']);
        Route::post('getMuestraAsignada', [FqController::class, 'getMuestraAsignada']);
        Route::post('delMuestraLote', [FqController::class, 'delMuestraLote']);
        Route::post('getDetalleLoteFq', [FqController::class, 'getDetalleLoteFq']);
        Route::post('setPlantillaDetalleFq', [FqController::class, 'setPlantillaDetalleFq']);
  
        //? Modulo captura de datos analisis
        Route::get('tipoAnalisis', [FqController::class, 'tipoAnalisis']);

    //***************************Espectrofotometría******************************************
        Route::get('capturaEspectro', [FqController::class, 'capturaEspectro']);
        Route::post('getLoteEspectro', [FqController::class, 'getLoteEspectro']);
        Route::post('getLoteCapturaEspectro', [FqController::class, 'getLoteCapturaEspectro']);
        Route::post('getDetalleEspectro', [FqController::class, 'getDetalleEspectro']);
        Route::post('getDetalleEspectroSulfatos', [FqController::class, 'getDetalleEspectroSulfatos']);
        Route::post('operacionEspectro', [FqController::class, 'operacionEspectro']);
        Route::post('operacionSulfatos', [FqController::class, 'operacionEspectro']);
        Route::post('operacionCOT', [FqController::class, 'operacionCOT']);
        Route::post('operacionDureza', [FqController::class, 'operacionDureza']);
        Route::post('guardarEspectro', [FqController::class, 'guardarEspectro']);
        Route::post('guardarSulfatos', [FqController::class, 'guardarSulfatos']);
        Route::post('guardarCOT', [FqController::class, 'guardarCOT']);
        Route::post('getDetalleCOT', [FqController::class, 'getDetalleCOT']);
        Route::post('guardarDureza', [FqController::class, 'guardarDureza']);
        Route::post('getDetalleDureza', [FqController::class, 'getDetalleDureza']);
        Route::post('updateObsMuestraEspectro', [FqController::class, 'updateObsMuestraEspectro']);
        Route::post('updateObsMuestraEspectroSulfatos', [FqController::class, 'updateObsMuestraEspectroSulfatos']);
        Route::post('updateObsMuestraEspectroDureza', [FqController::class, 'updateObsMuestraEspectroDureza']);
        Route::post('createControlCalidadEspectro', [FqController::class, 'createControlCalidadEspectro']);
        Route::post('createControlesCalidadEspectro', [FqController::class, 'createControlesCalidadEspectro']);
        Route::post('liberarMuestraEspectro', [FqController::class, 'liberarMuestraEspectro']);
        Route::post('liberarTodoEspectro', [FqController::class, 'liberarTodoEspectro']);
        
    //****************************************GA***********************************************

        Route::get('capturaGA', [FqController::class, 'capturaGA']);
        Route::post('getLoteGA', [FqController::class, 'getLoteGA']);
        Route::post('getLoteCapturaGA', [FqController::class, 'getLoteCapturaGA']);
        Route::post('getDetalleGA', [FqController::class, 'getDetalleGA']);
        Route::post('guardarDetalleGrasas', [FqController::class, 'guardarDetalleGrasas']);
        Route::post('operacionGA', [FqController::class, 'operacionGA']); 
        Route::post('operacionGASimple', [FqController::class, 'operacionGASimple']);
        Route::post('operacionGALarga', [FqController::class, 'operacionGALarga']);
        Route::post('createControlCalidad', [FqController::class, 'createControlCalidad']);
        Route::post('updateObsMuestraGA', [FqController::class, 'updateObsMuestraGA']);
        Route::post('liberarMuestraGa', [FqController::class, 'liberarMuestraGa']);
        Route::post('liberarTodoGA', [FqController::class, 'liberarTodoGA']);

//****************************************SOLIDOS***********************************************
        Route::get('capturaSolidos', [FqController::class, 'capturaSolidos']);
        Route::post('getLoteSolidos', [FqController::class, 'getLoteSolidos']);
        Route::post('getLoteCapturaSolidos', [FqController::class, 'getLoteCapturaSolidos']);
        Route::post('getDataCapturaSolidos', [FqController::class, 'getDataCapturaSolidos']);
        Route::post('getDetalleSolidos', [FqController::class, 'getDetalleSolidos']);
        Route::post('operacionSolidosSimple', [FqController::class, 'operacionSolidosSimple']);
        Route::post('operacionSolidosLarga', [FqController::class, 'operacionSolidosLarga']);
        Route::post('operacionSolidosDif', [FqController::class, 'operacionSolidosDif']);
        Route::post('guardarDirecto', [FqController::class, 'guardarDirecto']);
        Route::post('createControlCalidadSolidos', [FqController::class, 'createControlCalidadSolidos']);
        Route::post('updateObsMuestraSolidos', [FqController::class, 'updateObsMuestraSolidos']);
        Route::post('updateObsMuestraSolidosDif', [FqController::class, 'updateObsMuestraSolidosDif']);
        Route::post('liberarMuestraSolidos', [FqController::class, 'liberarMuestraSolidos']);


//****************************************FIN SOLIDOS***********************************************

//****************************************VOLUMETRIA***********************************************

        Route::get('loteVol', [VolController::class, 'loteVol']);
        // Route::post('createLote', [FqController::class, 'createLote']);
        // Route::post('buscarLote', [FqController::class, 'buscarLote']);
        Route::post('getDataloteVol', [VolController::class, 'getDataloteVol']); 
        Route::post('guardarValidacionVol', [VolController::class, 'guardarValidacionVol']);
        Route::get('asgnarMuestraLoteVol/{id}', [VolController::class, 'asgnarMuestraLoteVol']);
        Route::post('muestraSinAsignarVol', [VolController::class, 'muestraSinAsignarVol']);
        Route::post('asignarMuestraLoteVol', [VolController::class, 'asignarMuestraLoteVol']);
        Route::post('getMuestraAsignadaVol', [VolController::class, 'getMuestraAsignadaVol']);
        Route::post('delMuestraLoteVol', [VolController::class, 'delMuestraLoteVol']);
        Route::post('updateObsVolumetria', [VolController::class, 'updateObsVolumetria']);
        Route::post('enviarObsGeneralVol', [VolController::class, 'enviarObsGeneralVol']);
        Route::post('setTipoDqo',[VolController::class,'setTipoDqo']);
        Route::post('sendMuestrasLote',[VolController::class,'sendMuestrasLote']);
        Route::post('setPlantillaDetalleVol',[VolController::class,'setPlantillaDetalleVol']);

        Route::get('capturaVolumetria', [VolController::class, 'capturaVolumetria']);
        Route::post('getLotevol', [VolController::class, 'getLotevol']);
        Route::post('getPendientes', [VolController::class, 'getPendientes']);
        Route::post('getLoteCapturaVol', [VolController::class, 'getLoteCapturaVol']);
        Route::post('getDetalleVol', [VolController::class, 'getDetalleVol']);
        Route::post('operacionVolumetriaDqo', [VolController::class, 'operacionVolumetriaDqo']);
        Route::post('operacionVolumetriaCloro', [VolController::class, 'operacionVolumetriaCloro']);
        Route::post('operacionVolumetriaNitrogenoEquipo', [VolController::class, 'operacionVolumetriaNitrogenoEquipo']);
        Route::post('operacionVolumetriaNitrogeno', [VolController::class, 'operacionVolumetriaNitrogeno']);
        Route::post('guardarCloro', [VolController::class, 'guardarCloro']);
        Route::post('guardarDqo', [VolController::class, 'guardarDqo']);
        Route::post('guardarNitrogeno', [VolController::class, 'guardarNitrogeno']);
        Route::post('guardarNitrogenoEquipo', [VolController::class, 'guardarNitrogenoEquipo']);
        Route::post('createControlCalidadVol', [VolController::class, 'createControlCalidadVol']);
        Route::post('createControlesCalidadVol', [VolController::class, 'createControlesCalidadVol']);
        Route::post('liberarMuestraVol', [VolController::class, 'liberarMuestraVol']);
        Route::post('getPlantillaPredVol', [VolController::class, 'getPlantillaPred']);
        Route::post('liberarTodo', [VolController::class, 'liberarTodo']);
        

//****************************************FIN VOLUMETRIA***********************************************


        Route::get('capturaGravi', [FqController::class, 'capturaGravi']);
        
        // Route::get('captura/exportPdfCapturaGA/{idLote}', [FqController::class, 'exportPdfCapturaGA']);
        // Route::post('getDataCapturaEspectro', [FqController::class, 'getDataCapturaEspectro']);
        
        Route::post('setControlCalidad', [FqController::class, 'setControlCalidad']);
        Route::post('liberarMuestraMetal', [FqController::class, 'liberarMuestraMetal']);
        Route::post('getDataLote/plantillaPredeterminada', [FqController::class, 'getPlantillaPred']);
        Route::post('lote/guardarDatos', [FqController::class, 'guardarDatos']);
        Route::post('lote/procedimiento', [FqController::class, 'guardarTexto']);        

        //? Export PDF
        Route::get('captura/exportPdfEspectro/{idLote}', [FqController::class, 'exportPdfEspectro']);
        Route::get('captura/exportPdfCapturaEspectro/{idLote}', [FqController::class, 'exportPdfCapturaEspectro']);
        Route::get('captura/exportPdfCapturaGA/{idLote}', [FqController::class, 'exportPdfCapturaGA']);
        Route::get('captura/exportPdfCapturaSolidos/{idLote}', [FqController::class, 'exportPdfCapturaSolidos']);
        Route::get('captura/exportPdfCapturaVolumetria/{idLote}', [VolController::class, 'exportPdfCapturaVolumetria']);
        Route::get('captura/exportPdfBitacoraVol/{idLote}', [VolController::class, 'exportPdfBitacoraVol']);
    });
    //******************************************DIRECTOS****************************************************

    Route::group(['prefix' => 'directos'], function () {
        Route::get('lote', [DirectosController::class, 'lote']);
        Route::post('getLote',[DirectosController::class, 'getLote']); 
        Route::post('getDetalleLote',[DirectosController::class, 'getDetalleLote']); 
        Route::post('setLote',[DirectosController::class, 'setLote']); 
        Route::post('setPlantilla',[DirectosController::class, 'setPlantilla']); 
        Route::get('loteDetalle/{id}',[DirectosController::class, 'loteDetalle']);
        Route::post('getPendientes', [DirectosController::class, 'getPendientes']);
        Route::post('muestraSinAsignar', [DirectosController::class, 'muestraSinAsignar']);
        Route::post('asignarMuestraLote', [DirectosController::class, 'asignarMuestraLote']);
        Route::post('sendMuestrasLote', [DirectosController::class, 'sendMuestrasLote']);
        Route::post('getMuestraAsignada', [DirectosController::class, 'getMuestraAsignada']);
        Route::post('delMuestraLote', [DirectosController::class, 'delMuestraLote']);

        Route::get('captura',[DirectosController::class, 'captura']);
        Route::post('getLoteCapturaDirecto', [DirectosController::class, 'getLoteCapturaDirecto']);
        Route::post('getDetalleDirecto', [DirectosController::class, 'getDetalleDirecto']);
        Route::post('operacion', [DirectosController::class, 'operacion']);
        Route::post('operacionTemperatura', [DirectosController::class, 'operacionTemperatura']);
        Route::post('operacionTurbiedad', [DirectosController::class, 'operacionTurbiedad']);
        Route::post('operacionCloro', [DirectosController::class, 'operacionCloro']);
        Route::post('operacionColor', [DirectosController::class, 'operacionColor']);
        Route::post('enviarObsGeneral', [DirectosController::class, 'enviarObsGeneral']);
        Route::post('updateObsMuestra', [DirectosController::class, 'updateObsMuestra']);
        Route::post('liberarMuestra', [DirectosController::class, 'liberarMuestra']);
        Route::post('createControlCalidadDirectos', [DirectosController::class, 'createControlCalidadDirectos']);
        Route::get('captura/exportPdfDirecto/{idLote}', [DirectosController::class, 'exportPdfDirecto']);
        
    });
 

    //****************************************FIN DIRECTOS***********************************************
        //******************************************POTABLE****************************************************

        Route::group(['prefix' => 'potable'], function () {
            Route::get('lote', [PotableController::class, 'lote']);
            Route::post('getPendientes', [PotableController::class, 'getPendientes']);
            Route::post('getLote',[PotableController::class, 'getLote']); 
            Route::post('getDetalleLote',[PotableController::class, 'getDetalleLote']); 
            Route::post('setLote',[PotableController::class, 'setLote']); 
            Route::post('setPlantilla',[PotableController::class, 'setPlantilla']); 
            Route::post('valoracionDureza',[PotableController::class, 'valoracionDureza']); 
            Route::get('loteDetalle/{id}',[PotableController::class, 'loteDetalle']);
            Route::post('muestraSinAsignar', [PotableController::class, 'muestraSinAsignar']);
            Route::post('asignarMuestraLote', [PotableController::class, 'asignarMuestraLote']);
            Route::post('sendMuestrasLote', [PotableController::class, 'sendMuestrasLote']);
            Route::post('getMuestraAsignada', [PotableController::class, 'getMuestraAsignada']);
            Route::post('delMuestraLote', [PotableController::class, 'delMuestraLote']);
    
            Route::get('captura',[PotableController::class, 'captura']);
            Route::post('getLoteCapturaPotable', [PotableController::class, 'getLoteCapturaPotable']);
            Route::post('getDetallePotable', [PotableController::class, 'getDetallePotable']);
            Route::post('operacion', [PotableController::class, 'operacion']);
            Route::post('enviarObsGeneral', [PotableController::class, 'enviarObsGeneral']);
            Route::post('updateObsMuestra', [PotableController::class, 'updateObsMuestra']);
            Route::post('liberarMuestra', [PotableController::class, 'liberarMuestra']);
            Route::get('captura/exportPdfPotable/{idLote}', [PotableController::class, 'exportPdfPotable']);
            Route::post('createControlCalidad', [PotableController::class, 'createControlCalidad']);
            
        }); 
     
    
        //****************************************FIN POTABLE***********************************************
        // todo Modulo MicroBiologia
        Route::group(['prefix' => 'micro'], function () {
            //? Modulo Analisis -  Solo visualizar analisis pendientes
            Route::get('analisis', [MbController::class, 'analisis']);
    
            //? Modulo Observacion - Agregar observaciones a analisis
            Route::get('observacion', [MbController::class, 'observacion']);
            Route::post('getObservacionanalisis', [MbController::class, 'getObservacionanalisis']);
            Route::post('aplicarObservacion', [MbController::class, 'aplicarObservacion']);
    
            //? Modulo Lote - Creación  de lotes
            Route::get('lote', [MbController::class, 'lote']);
            Route::post('getPendientes', [MbController::class, 'getPendientes']);  
            Route::post('createLote', [MbController::class, 'createLote']);
            Route::post('buscarLote', [MbController::class, 'buscarLote']);
            Route::post('getDatalote', [MbController::class, 'getDatalote']);
            Route::get('asgnarMuestraLote/{id}', [MbController::class, 'asgnarMuestraLote']);
            Route::post('muestraSinAsignar', [MbController::class, 'muestraSinAsignar']);
            Route::post('asignarMuestraLote', [MbController::class, 'asignarMuestraLote']);
            Route::post('getMuestraAsignada', [MbController::class, 'getMuestraAsignada']);
            Route::post('delMuestraLote', [MbController::class, 'delMuestraLote']);
    
            //? Modulo captura de datos analisis
            Route::get('tipoAnalisis', [MbController::class, 'tipoAnalisis']);
    
            Route::get('capturaMicro', [MbController::class, 'capturaMicro']);
            Route::post('getLoteMicro', [MbController::class, 'getLoteMicro']);
            Route::post('getLoteCapturaMicro', [MbController::class, 'getLoteCapturaMicro']);
            Route::post('getDetalleColiAlimentos', [MbController::class, 'getDetalleColiAlimentos']);
            Route::post('getDetalleEcoli', [MbController::class, 'getDetalleEcoli']);
            Route::post('operacionEcoliFinal', [MbController::class, 'operacionEcoliFinal']);
            Route::post('operacionColAlimentos', [MbController::class, 'operacionColAlimentos']);
            Route::post('operacionEcoli', [MbController::class, 'operacionEcoli']);
            Route::post('getDetalleCol', [MbController::class, 'getDetalleCol']);
            Route::post('getDetalleHH', [MbController::class, 'getDetalleHH']);
            Route::post('getDetalleDbo', [MbController::class, 'getDetalleDbo']);
            Route::post('operacion', [MbController::class, 'operacion']);
            Route::post('metodoCortoCol', [MbController::class, 'metodoCortoCol']);
            Route::post('metodoCortoEnt', [MbController::class, 'metodoCortoEnt']);
            Route::post('updateObsMuestra', [MbController::class, 'updateObsMuestra']);
            Route::post('updateObsMuestraEcoli', [MbController::class, 'updateObsMuestraEcoli']);
            Route::post('createControlCalidadMb', [MbController::class, 'createControlCalidadMb']);
            Route::post('createControlesCalidadMb', [MbController::class, 'createControlesCalidadMb']);
            Route::post('setPlantillaDetalleMb', [MbController::class, 'setPlantillaDetalleMb']);
            
            
            Route::post('liberarMuestra', [MbController::class, 'liberarMuestra']);
            Route::post('getDataLote/plantillaPredeterminada', [MbController::class, 'getPlantillaPred']);
            Route::post('lote/guardarDatos', [MbController::class, 'guardarDatos']);
            Route::post('lote/setDetalleLote', [MbController::class, 'setDetalleLote']);
            Route::post('lote/procedimiento', [MbController::class, 'guardarTexto']);
            Route::post('lote/guardarDqo', [MbController::class, 'guardarDqo']);
 

            //RUTA PARA VISTA CAPTURA DE MICROBIOLOGÍA
            Route::get('capturaMicro', [MbController::class, 'capturaMicro']);
                        
            Route::get('captura/exportPdfCaptura/{idLote}', [MbController::class, 'exportPdfCaptura']);
            Route::get('captura/exportPdfCapturaMb/{idLote}', [MbController::class, 'exportPdfCapturaMb']);
            
          
        });

    //? Módulo curva - Creación de curva
    Route::get('asignar', [MetalesController::class, 'asignar']);
    Route::get('curva', [CurvaController::class, 'index']);
    Route::post('promedio', [CurvaController::class, 'promedio']);
    Route::post('guardar', [CurvaController::class, 'guardar']);
    Route::post('formula', [CurvaController::class, 'formula']);
    Route::post('buscar', [CurvaController::class, 'buscar']);
    Route::post('createStd', [CurvaController::class, 'createStd']);
    Route::post('getParametro', [CurvaController::class, 'getParametro']);
    Route::post('getParametroModal', [CurvaController::class, 'getParametroModal']);
    Route::post('getLote', [CurvaController::class, 'getLote']);
    Route::post('setCalcular', [CurvaController::class, 'setCalcular']);
    Route::post('setConstantes', [CurvaController::class, 'setConstantes']);
    Route::post('tablaVigencias', [CurvaController::class, 'tablaVigencias']); 
    Route::post('curvaHijos', [CurvaController::class, 'curvaHijos']); 
   


    //? PDF
    Route::get('captura/exportPdfCaptura/{idLote}', [LaboratorioController::class, 'exportPdfCaptura']);
    /*Route::get('captura/exportPdfCaptura/{formulaTipo}/{numeroMuestra}/{idLote}', [LaboratorioController::class, 'exportPdfCaptura'])->where('numeroMuestra', '(.*)');*/
});

