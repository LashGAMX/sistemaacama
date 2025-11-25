 <?php

    use App\Http\Controllers\Alimentos\AlimentosController;
    use Illuminate\Support\Facades\Route;

    Route::group(['prefix' => 'alimentos'], function () {
        Route::get('/supervicion', [AlimentosController::class, 'supervicion']);
        Route::get('/setDataCadena', [AlimentosController::class, 'setDataCadena']);

        
        Route::get('/detalleCadena/{id}', [AlimentosController::class, 'detalleCadena']);

        Route::get('/captura', [AlimentosController::class, 'captura']);
        //Todas las rutas post pertenecientes a captura van aquí
        Route::get('/cotizacion', [AlimentosController::class, 'cotizacion']);
        Route::get('/create-cotizacion', [AlimentosController::class, 'createcotizacion']);
        Route::get('/informe', [AlimentosController::class, 'informe']);
        Route::get('/InformeData', [AlimentosController::class, 'InformeData']);

        Route::get('/CampoAlimentos', [AlimentosController::class, 'CampoAlimentos']);
        Route::get('/GetBitacora', [AlimentosController::class, 'GetBitacora']);
        Route::post('/getbitacoras', [AlimentosController::class, 'getbitacoras']);
        Route::get('/orden-servicio', [AlimentosController::class, 'ordenServicio']);
        Route::get('/orden-servicio/create-orden', [AlimentosController::class, 'createOrden']);
        Route::get('/orden-servicio/edit-orden/{id}', [AlimentosController::class, 'editOrden']);

        Route::get('/recepcion-muestras', [AlimentosController::class, 'recepcionMuestras']);
        //Todas las rutas post pertenecientes a recepcion muestras van aquí
        Route::get('/orden-servicio/create-orden-ingreso', [AlimentosController::class, 'createOrdenIngreso']);

        //aqui consulto sucursales 
        Route::post('/getSucursal', [AlimentosController::class, 'getSucursal']);
        Route::post('/getSucursal2', [AlimentosController::class, 'getSucursal2']);

        Route::post('/getDireccionReporte', [AlimentosController::class, 'getDireccionReporte']);
        Route::post('/getContactoSucursal', [AlimentosController::class, 'getContactoSucursal']);
        Route::post('/getSubNormas', [AlimentosController::class, 'getSubNormas']);
        Route::post('/setSolicitud', [AlimentosController::class, 'setSolicitud']);
        Route::post('/setSolicitud2', [AlimentosController::class, 'setSolicitud2']);
        // Route::get('/getDataContacto', [AlimentosController::class, 'getDataContacto']);
        Route::get('/getOrden', [AlimentosController::class, 'getOrden']);


        Route::get('/duplicarSolicitud', [AlimentosController::class, 'duplicarSolicitud']);
        Route::post('/getDataSolicitud', [AlimentosController::class, 'getDataSolicitud']);
        Route::post('/setMuestraSol', [AlimentosController::class, 'setMuestraSol']);
        Route::post('/setMuestraSol2', [AlimentosController::class, 'setMuestraSol2']);

        Route::post('/getMuestraSol', [AlimentosController::class, 'getMuestraSol']);
        Route::post('/setSaveMuestra', [AlimentosController::class, 'setSaveMuestra']);
        Route::post('/DeleteMuestra', [AlimentosController::class, 'DeleteMuestra']);


        Route::get('/getcotizacionAli', [AlimentosController::class, 'getcotizacionAli']);
        // Route::get('/getcotizacionAli', [AlimentosController::class, 'getcotizacionAli']);
        Route::post('/getClientesIntermediarios', [AlimentosController::class, 'getClientesIntermediarios']);
        Route::get('/exportPdfOrden/{id}', [AlimentosController::class, 'exportPdfOrden']);
        Route::get('/ImprimirInforme/{id}', [AlimentosController::class, 'ImprimirInforme']);

        Route::get('/getClienteGen', [AlimentosController::class, 'getClienteGen']);
        Route::post('/setSucursal', [AlimentosController::class, 'setSucursal']);
        Route::post('/getDirecciones', [AlimentosController::class, 'getDirecciones']);
        Route::post('/getDataContacto', [AlimentosController::class, 'getDataContacto']);
        Route::post('/getDatos', [AlimentosController::class, 'getDatos']);


        Route::get('/getservicios', [AlimentosController::class, 'getservicios']);
        Route::get('/getNormas', [AlimentosController::class, 'getNormas']);
        Route::post('/getSubNorma', [AlimentosController::class, 'getSubNorma']);
        Route::post('/setContacto', [AlimentosController::class, 'setContacto']);
        Route::post('/editContacto', [AlimentosController::class, 'editContacto']);

        Route::post('/buscarFolio', [AlimentosController::class, 'buscarFolio']);
        Route::post('/setIncumplimiento', [AlimentosController::class, 'setIncumplimiento']);
        Route::get('/getinformes', [AlimentosController::class, 'getinformes']);
        Route::get('/exportPdfHojaCampo', [AlimentosController::class, 'exportPdfHojaCampo']);
        Route::get('/exportPdfInforme/{id}', [AlimentosController::class, 'exportPdfInforme']);
        Route::get('/setGenFolioSol', [AlimentosController::class, 'setGenFolioSol']);
        Route::post('/CodigoAlimentos', [AlimentosController::class, 'CodigoAlimentos']);
        Route::post('/ingresar', [AlimentosController::class, 'ingresar']);

        Route::post('/getLote', [AlimentosController::class, 'getLote']);
        Route::post('/getMuestraSinAsignar', [AlimentosController::class, 'getMuestraSinAsignar']);
        Route::post('/setMuestraLote', [AlimentosController::class, 'setMuestraLote']);
        Route::post('/getCapturaLote', [AlimentosController::class, 'getCapturaLote']);
        Route::post('/setLiberar', [AlimentosController::class, 'setLiberar']);
        Route::post('/setLiberarTodo', [AlimentosController::class, 'setLiberarTodo']);

        

        Route::post('/getPuntoMuestro', [AlimentosController::class, 'getPuntoMuestro']);
        Route::post('/getParametroCadena', [AlimentosController::class, 'getParametroCadena']);
        Route::post('/getDetalleAnalisis', [AlimentosController::class, 'getDetalleAnalisis']);
        Route::post('desactivarMuestra', [AlimentosController::class, 'desactivarMuestra']);
        Route::post('regresarMuestra', [AlimentosController::class, 'regresarMuestra']);


        Route::post('/UpdateMuestra', [AlimentosController::class, 'UpdateMuestra']);
        Route::get('RecepcionAli', [AlimentosController::class, 'RecepcionAli']);


        Route::post('/RepAlimentos', [AlimentosController::class, 'RepAlimentos']);
        Route::get('getRecepcionAli', [AlimentosController::class, 'getRecepcionAli']);
        Route::post('/setDetalleMuestra', [AlimentosController::class, 'setDetalleMuestra']);
        Route::post('/getDetalleMuestra', [AlimentosController::class, 'getDetalleMuestra']);
        Route::post('/setObservacion', [AlimentosController::class, 'setObservacion']);
        Route::post('/setControlCalidad', [AlimentosController::class, 'setControlCalidad']);
        Route::post('/reasignarMuestra2', [AlimentosController::class, 'reasignarMuestra2']);
        Route::post('/getRecepcion', [AlimentosController::class, 'getRecepcion']);

        Route::post('/IngresarRecepcion', [AlimentosController::class, 'IngresarRecepcion']);
        Route::post('/setEmision', [AlimentosController::class, 'setEmision']);

        Route::get('/BitacoraPdf/{Fini}/{Ffin}',[AlimentosController::class, 'BitacoraPdf']);
        Route::get('/BitacoraRecep',[AlimentosController::class, 'BitacoraRecep']);
        Route::get('/HistorialRecepAli',[AlimentosController::class, 'HistorialRecepAli']);

        Route::post('/getbitacorasAlimentos', [AlimentosController::class, 'getbitacorasAlimentos']);
        Route::post('parametros', [AlimentosController::class, 'parametros']);
        Route::post('/getPendientes', [AlimentosController::class, 'getPendientes']);
        Route::post('guardarCalculo', [AlimentosController::class, 'guardarCalculo']);
        Route::post('LiberarReg', [AlimentosController::class, 'LiberarReg']);
        Route::post('EntregaMuestra', [AlimentosController::class, 'EntregaMuestra']);
        Route::post('CancelarOrden', [AlimentosController::class, 'CancelarOrden']);
        Route::post('DuplicarSolAlimentos', [AlimentosController::class, 'DuplicarSolAlimentos']);
        Route::post('setFechaPA2', [AlimentosController::class, 'setFechaPA2']);

        //Captura
        Route::get('/capturaAlimentos',[AlimentosController::class, 'capturaAlimentos']);
    });

    ?> 