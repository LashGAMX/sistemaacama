<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\LimiteParametros001;
use App\Http\Livewire\AnalisisQ\Normas;
use App\Models\ClienteSiralab;
use App\Models\ContactoCliente;
use App\Models\Cotizacion;
use App\Models\CotizacionMuestreo;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Intermediario;
use App\Models\Norma;
use App\Models\NormaParametros;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro; 
use App\Models\SolicitudPuntos;
use App\Models\SubNorma;
use App\Models\SucursalCliente;
use App\Models\TipoDescarga;
use App\Models\TipoServicios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        // $model = DB::table('ViewSolicitud')->get();
        $model = DB::table('ViewCotizacion')->get();
        return view('cotizacion.solicitud',compact('model'));
    } 
    public function buscarFecha()
    {
        
        
    }

    public function create($idCot)
    {
        $cotizacionModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->get();

        //Si existe una cotización previa para esta nueva solicitud
        if($cotizacionModel->count()){
            //Obtiene todos los datos de la cotización
            $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->first();            

            //Recupera los intermediarios registrados para esa cotización
            $intermediario = DB::table('ViewIntermediarios')->where('Id_intermediario', $model->Id_intermedio)->get();
            
            //Recupera los contactos del cliente registrados para esa cotización
            $contactoCliente = ContactoCliente::where('Id_cliente', $model->Id_cliente)->get();

            //ViewGenerales hace referencia a los clientes registrados
            $cliente = DB::table('ViewGenerales')->where('Id_cliente', $model->Id_cliente)->get();

            $servicios = TipoServicios::all();
            $descargas = TipoDescarga::all();
            $normas = Norma::all();
            $frecuencia = DB::table('frecuencia001')->where('Id_frecuencia', $model->Frecuencia_muestreo)->first();
            $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->get();            
            //$model = Solicitud::where('Id_cotizacion',$idCot)->first();
            $sw = false;
            
            return view('cotizacion.createSolicitud',
            compact(
                'sw',                
                'model',
                'idCot',
                'intermediario', 
                'cliente',
                'servicios',
                'descargas',
                'normas',
                'frecuencia',
                'contactoCliente'
            ));
        }else{  //Crea una plantilla vacía
            $intermediario = DB::table('ViewIntermediarios')->get();
            $cliente = DB::table('ViewGenerales')->get();
            $servicios = TipoServicios::all();
            $descargas = TipoDescarga::all();
            $frecuencia = DB::table('frecuencia001')->get();
            $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->get();
            $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->first();
            //$model = Solicitud::where('Id_cotizacion',$idCot)->first();
            $sw = false;
            return view('cotizacion.createSolicitud',
            compact(
                'sw',
                'cotizacion',
                'model',
                'idCot',
                'intermediario', 
                'cliente',
                'servicios',
                'descargas',
                'frecuencia',
            ));
        }            
    }

    public function createSinCot(){        
        $intermediario = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $cliente = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $cotizacion = DB::table('ViewCotizacion')->get();
        $contactoCliente = ContactoCliente::all();
        $normas = Norma::all();
        $subNormas = SubNorma::all();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get();
        
        //$model = DB::table('ViewCotizacion')->where('Id_cotizacion')->first();
        //$model = Solicitud::where('Id_cotizacion',$idCot)->first();
        
        $sw = false;
        return view('cotizacion.solicitudSinCot',
        compact(
            'sw',
            'cotizacion',
            'intermediario', 
            'cliente',
            'servicios',
            'descargas',
            'frecuencia',
            'contactoCliente',
            'normas',
            'subNormas',
            'metodoPago',
            'estados'
        ));                
    }

    public function setSolicitudSinCot(Request $request){
        
        //*************************************************CREACIÓN DE COTIZACIÓN**********************************************
        //Representa el año con dos dígitos; ej: 99 (1999) o 03 (2003)
        $year = date("y");

        //Representa el mes con ceros iniciales; ej: 01 hasta 12.
        $month = date("m");

        //Día del año (comenzando por 1)
        $dayYear = date("z") + 1; 
        
        //Crea la fecha de hoy utilizando el formato establecido; ej: 21-12-347
        $today = Carbon::now()->format('Y-m-d');

        //Busca y cuenta cuantas cotizaciones fueron creadas el día de hoy
        $cotizacionDay = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->count();

        //Busca y obtiene el número de cotizaciones filtrando la búsqueda por las que fueron creadas el día de hoy y por el Id del cliente
        $numCot = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->get();

        ////Busca y obtiene la primera cotización creada el día de hoy filtrando la búsqueda por las que fueron creadas el día de hoy y por el Id del cliente
        $firtsFol = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->first();
        
        //Almacena en la var.cantCot el número de cotizaciones almacenadas en la var.numCot
        $cantCot = $numCot->count();
                
        //Si existen creadas cotizaciones previas entonces creará Folios al estilo: 343-1/21-2, 343-1/21-3, etc.
        if ($cantCot > 0) {
            
            //Almacena en la var.folio el Folio recuperado de la cotización creada el día de hoy añadiéndole un guión(-) y el número de cotizaciones + 1
            $folio = $firtsFol->Folio . '-' . ($cantCot + 1);

        } else {//Si no existe una cotización creada previamente la var.folio establece un número de folio nuevo
            
            /*
            Contruye un folio comenzando por el día del año, un guión (-), el valor de la var. cotizacionDay (que tiene valor 0) + 1, 
            una diagonal, y el año; ej: 348-1/21 que hace referencia al 13 de diciembre con una cotización + 1, una diagonal y el año 2021
            */
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }

        //  var_dump($request->precioAnalisis);

        //Recupera el nombre del cliente
        $clienteReg = DB::table('ViewGenerales')->where('Id_cliente', $request->clientes)->first();        

        //Recupera la dirección del reporte
        if($request->siralab != "true"){
            $direccion = DireccionReporte::where('Id_direccion', $request->direccionReporte)->first();
        }else{
            $direccion = ClienteSiralab::where('Id_cliente_siralab', $request->direccionReporte)->first();
        }

        //Recupera el tipo de muestra;Aunque no es necesario si no se pone nada el atributo value en los option's
        if($request->tipoMuestra == 0){
            $tipoMuestra = "INSTANTANEA";
        }else if($request->tipoMuestra == 1){
            $tipoMuestra = "COMPUESTA";
        }

        //Crea una cotización nueva
        $cotizacion = Cotizacion::create([
            'Id_intermedio' => $request->intermediario,
            'Id_cliente' => $request->clientes,


            'Nombre' => $clienteReg->Empresa,
            //'Nombre' => $request->contacto,
            

            'Direccion' => $direccion->Direccion,
            'Atencion' => $request->atencion,
            'Telefono' => $request->telefonoContVal,
            'Correo' => $request->emailContVal,
            'Tipo_servicio' => $request->tipoServicio,
            'Tipo_descarga' => $request->tipoDescarga,
            'Id_norma' => $request->norma,
            'Id_subnorma' => $request->subnorma,
            'Fecha_muestreo' => $request->fechaMuestreo,
            'Frecuencia_muestreo' => $request->frecuencia,
            'Tomas' => $request->numTomas,
            'Tipo_muestra' => $tipoMuestra,
            'Promedio' => $request->promedio,
            'Numero_puntos' => $request->contPunto,
            'Tipo_reporte' => $request->tipoReporte,


            //Por revisar
            'Tiempo_entrega' => $request->tiempoEntrega,
            'Observacion_interna' => $request->observacionInterna,
            'Observacion_cotizacion' => $request->observacionCotizacion,
            'Folio' => $folio,
            'Metodo_pago' => $request->metodoPago,
            'Precio_analisis' => $request->precioAnalisis,
            'Descuento' => $request->descuento,
            'Precio_muestreo' => $request->precioMuestra,
            'Sub_total' => $request->subTotal,
            'Costo_total' => $request->precioTotal,
            'Estado_cotizacion' => 1,            
                        
            'Creado_por' => Auth::user()->id,
            'Actualizado_por' => Auth::user()->id,
        ]);

        /* $cotizacion = DB::table('cotizacion')->latest('created_at')->first(); */

        //Crea un registro en la tabla cotizacion_muestreo con valores predeterminados (de momento)
        CotizacionMuestreo::create([
            'Id_cotizacion' => $cotizacion->Id_cotizacion,
            'Dias_hospedaje' => $request->diasHospedaje,
            'Hospedaje' => $request->hospedaje,
            'Dias_muestreo' => $request->diasMuestreo,
            'Num_muestreo' => $request->numeroMuestreo,
            'Caseta' => $request->caseta,
            'Km' => $request->km,
            'Km_extra' => $request->kmExtra,
            'Gasolina_teorico' => $request->gasolinaTeorico,
            'Cantidad_gasolina' => $request->cantidadGasolina,
            'Paqueteria' => $request->paqueteria,
            'Adicional' => $request->gastosExtras,
            'Num_servicio' => $request->tipoServicio,
            'Num_muestreador' => $request->numMuestreador,
            'Estado' => $request->estado,
            'Localidad' => $request->localidad,
            'Total' => $request->totalMuestreo,

            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        //Almacena en la var.parámetro, el/los valor/es que llegan de solicitudSinCot.blade a través del request name parametrosSolicitud
        $parametro = $request->parametrosSolicitud;
        
        //Separa los parámetros almacenados en la var.parametro utilizando como delimitador una coma (,), posteriormente el método explode los almacena en un array de Strings
        $parametro = explode(',', $parametro);

        foreach ($parametro as $item) {
            //Obtiene los registros que fueron filtrados a través del ID de la subnorma y el parámetro
            $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();


            $extra = 0;

            //Si la var. subnorma cuenta con 1 registro mínimo
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else { //Si la var. subnorma tiene 0 registros
                $extra = 1;
            }

            //Crea una cotización de parametros nueva
            CotizacionParametros::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_subnorma' => $item,
                'Extra' => $extra,
            ]);

            echo $item;
        }

        //Almacena en la var.puntoMuestreo los puntos de muestreo creados en el formulario de solicitudSinCot.blade.php
        $puntoMuestreo = $request->puntosSolicitud;

        //Separa los puntos de muestreo a través del delimitador coma (,) y los almacena en un Array de Strings
        $puntoMuestreo = explode(',', $puntoMuestreo);

        foreach ($puntoMuestreo as $item) {
            CotizacionPunto::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Descripcion' => $item,
            ]);
        }
        //*************************************************FIN DE CREACIÓN DE COTIZACIÓN**********************************************

        //********************************************************CREACIÓN DE SOLICITUD***********************************************
        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');

        $solicitudDay = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->count();

        $numCot = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->get();
        
        $firtsFol = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->first();
        
        $cantCot = $numCot->count();
        
        if ($cantCot > 0) {//Crea un nuevo folio tomando como base el primero; ej: 348-1/21-2

            $folio = $firtsFol->Folio_servicio . '-' . ($cantCot + 1);
        } else {//Crea un nuevo folio; ej: 348-1/21
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        }

        // var_dump($folio);
        // Convertir cadena a array de datos

        //Si existe una cotización
        if($cotizacion->Id_cotizacion > 0)
        {

            $puntos = explode(",", $request->puntosSolicitud2);
            $parametros = explode(",", $request->parametrosSolicitud);

            if($request->siralab != NULL)
            {
                $siralab = 1;
            }else{
                $siralab = 0;
            }

            $model = Solicitud::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $request->intermediario,
                'Id_cliente' => $request->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $request->sucursal,
                'Id_direccion' => $request->direccionReporte,
                'Id_contacto' => $request->contacto,
                'Atencion' => $request->atencion,
                'Observacion' => $request->observacion,
                'Id_servicio' => $request->tipoServicio,
                'Id_descarga' => $request->tipoDescarga,
                'Id_norma' => $request->norma,
                'Id_subnorma' => $request->subnorma,
                'Fecha_muestreo' => $request->fechaMuestreo,
                'Id_muestreo' => $request->frecuencia,
                'Num_tomas' => $request->numTomas,
                'Id_muestra' => $tipoMuestra,
                'Id_promedio' => $request->promedio,
            ]);
    
            // var_dump($model->Id_solicitud);
             foreach($puntos as $p)
            {
                $puntoModel = SolicitudPuntos::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_muestreo' => $p //Contiene Strings y no INT's
                ]);
            }

            foreach ($parametros as $item) {
                $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();

                $extra = 0;
                if ($subnorma->count() > 0) {
                    $extra = 0;
                } else {
                    $extra = 1;
                }

                SolicitudParametro::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_subnorma' => $item,
                    'Extra' => $extra,
                ]);
            }
    
            //Actualiza la cotización de estado
            $cotModel = Cotizacion::find($cotizacion->Id_cotizacion);
            $cotModel->Folio_servicio = $model->Folio_servicio;
            $cotModel->Estado_cotizacion = 2;
            $cotModel->save();
            
            //todo Inicia seguimiento de analisis
            SeguimientoAnalisis::create([
                'Id_servicio' => $model->Id_solicitud,
                'Obs_solicitud' => '',
            ]);
        
        }else{ 

        }                     

        return redirect()->to('admin/cotizacion/solicitud');
    } 

    public function update($idCot)
    {
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->get();
        $cotizacionMuestreo = CotizacionMuestreo::where('Id_cotizacion', $idCot)->first();
        $sw = true;
        // $model = DB::table('solicitudes')->where('Id_cotizacion',$idCot)->first();
        $model = Solicitud::where('Id_cotizacion',$idCot)->first();
        $puntos = DB::table('ViewPuntoGenSol')->where('Id_solicitud',$model->Id_solicitud)->get();
        // var_dump($puntos);
        return view('cotizacion.editSolicitud',
        compact(
            'puntos',
            'sw',
            'model',
            'cotizacion',
            'cotizacionMuestreo', 
            'idCot',
            'intermediario', 
            'cliente',
            'servicios',
            'descargas',
            'frecuencia',
        ));
    }
    public function getDataSolicitud(Request $request)
    {
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$request->idSol)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    
    public function setSolicitud(Request $request)
    {
        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $solicitudDay = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->count();

        $numCot = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->get();
        $firtsFol = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->first();
        $cantCot = $numCot->count();
        if ($cantCot > 0) {

            $folio = $firtsFol->Folio_servicio . '-' . ($cantCot + 1);
        } else {
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        }
        // var_dump($folio);
        // Convertir cadena a array de datos

        if($request->idCotizacion > 0)
        {
            $puntos = explode(",", $request->puntosSolicitud);
            $parametros = explode(",", $request->parametrosSolicitud);

            if($request->siralab != NULL)
            {
                $siralab = 1;
            }else{
                $siralab = 0;
            }

            $model = Solicitud::create([
                'Id_cotizacion' => $request->idCotizacion,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $request->intermediario,
                'Id_cliente' => $request->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $request->sucursal,
                'Id_direccion' => $request->direccionReporte,
                'Id_contacto' => $request->contacto,
                'Atencion' => $request->atencion,
                'Observacion' => $request->observacion,
                'Id_servicio' => $request->tipoServicio,
                'Id_descarga' => $request->tipoDescarga,
                'Id_norma' => $request->norma,
                'Id_subnorma' => $request->subnorma,
                'Fecha_muestreo' => $request->fechaMuestreo,
                'Id_muestreo' => $request->frecuencia,
                'Num_tomas' => $request->numTomas,
                'Id_muestra' => $request->tipoMuestra,
                'Id_promedio' => $request->promedio,
            ]);
    
            // var_dump($model->Id_solicitud);
            foreach($puntos as $p)
            {
                $puntoModel = SolicitudPuntos::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_muestreo' => $p
                ]);
            }
            foreach ($parametros as $item) {
                $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();

                $extra = 0;
                if ($subnorma->count() > 0) {
                    $extra = 0;
                } else {
                    $extra = 1;
                }

                SolicitudParametro::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_subnorma' => $item,
                    'Extra' => $extra,
                ]);
            }
    
            //Actualiza la cotización de estado
            $cotModel = Cotizacion::find($request->idCotizacion);
            $cotModel->Folio_servicio = $model->Folio_servicio;
            $cotModel->Estado_cotizacion = 2;
            $cotModel->save();

            
            //todo Inicia seguimiento de analisis
            SeguimientoAnalisis::create([
                'Id_servicio' => $model->Id_solicitud,
                'Obs_solicitud' => '',
            ]);
        
        }else{ 

        }

        
        
        return redirect()->to('admin/cotizacion/solicitud');
    }
    public function getSucursal(Request $request)
    {
        $contacto = ContactoCliente::where('Id_cliente',$request->cliente)->get();
        $sucursal = SucursalCliente::where('Id_cliente',$request->cliente)->get();
        $data = array(
            'idCliente' => $request->cliente,
            'sucursal' => $sucursal,
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function getDatoIntermediario(Request $request)
    {
        $intermediario = DB::table('ViewIntermediarios')->where('Id_cliente',$request->idCliente)->first();
        $data = array(
            'intermediario' => $intermediario,
        );
        return response()->json($data);
    }
    public function getDireccionReporte(Request $request)
    {
        // $direccion = DB::table('di')
        $direccion = DireccionReporte::where('Id_sucursal',$request->idSucursal)->get();
        $data = array(
            'direccion' => $direccion,
        );
        return response()->json($data);
    }
    public function setContacto(Request $request)
    {
        ContactoCliente::create([
            'Id_cliente' => $request->idCliente,
            'Nombres' => $request->nombre,
            'A_paterno' => $request->paterno,
            'A_materno' => $request->materno,
            'Celular' => $request->celular,
            'Telefono' => $request->telefono,
            'Email' => $request->correo,
        ]);

        $model = ContactoCliente::Where('Id_cliente',$request->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function storeContacto(Request $request)
    {
        $model = ContactoCliente::find($request->idContacto);
        $model->Nombres = $request->nombre;
        $model->A_paterno = $request->paterno;
        $model->A_materno = $request->materno;
        $model->Celular = $request->celular;
        $model->Telefono = $request->telefono;
        $model->Email = $request->correo;
        $model->save();

        $model = ContactoCliente::Where('Id_cliente',$request->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getDataContacto(Request $request)
    {
        $model = ContactoCliente::where('Id_contacto',$request->idContacto)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getPuntoMuestro(Request $request)
    {
        if($request->siralab != 'true')
        {
            $model = PuntoMuestreoGen::where('Id_sucursal',$request->idSuc)->get();
        }else{
            $model = PuntoMuestreoSir::where('Id_sucursal',$request->idSuc)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getReporteSir(Request $request)
    {
        if($request->siralab != "true")
        {
            $direccion = DireccionReporte::where('Id_sucursal',$request->idSucursal)->get();
        }else{
            $direccion = ClienteSiralab::where('Id_sucursal',$request->idSucursal)->get();
        }
        
        $data = array(
            'direccion' => $direccion,
            'siralab' => $request->siralab,
        );
        return response()->json($data);
    }
    
    public function exportPdfOrden($idOrden) 
    {
        $qr = new DNS2D();
        $model = DB::table('ViewSolicitud')->where('Id_cotizacion',$idOrden)->first();
        $parametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$model->Id_solicitud)->get();
        $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion', $idOrden)->first();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);
        
        /* $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretada2.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true; */
        $html = view('exports.cotizacion.ordenServicio', compact('model','parametros','qr', 'cotizacion'));
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function setGenFolio(Request $request){
        //$model = DB::table('frecuencia001')->where('Id_frecuencia', $idFrecuencia)->first();
        $model = DB::table('ViewSolicitud')->where('Id_cotizacion',$request->idCot)->first();
        return response()->json($model);
    }

    public function getDatos2()
    {
        $idIntermediario = $_POST['intermediario'];
        $idSub = $_POST['idSub'];
        $idParametros = $_POST['idParametros'];
        $idServicio = $_POST['idServicio'];
        $idDescarga = $_POST['idDescarga'];

        $parametroExtra = array();

        $intermediarios = DB::table('ViewIntermediarios')->where('Id_cliente', $idIntermediario)->first();
        $subnorma = DB::table('sub_normas')->where('Id_subnorma', $idSub)->first();
        $servicio = DB::table('tipo_servicios')->where('Id_tipo', $idServicio)->first();
        $descarga = DB::table('tipo_descargas')->where('Id_tipo', $idDescarga)->first();

        $contExtra = 0;
        for ($i = 0; $i < sizeof($idParametros); $i++) {
            $parPre = DB::table('norma_parametros')->where('Id_norma', $idSub)->where('Id_parametro', $idParametros[$i])->get();
            if ($parPre->count()) {
            } else {
                $parametroExtra[$contExtra] = $idParametros[$i];
                $contExtra++;
            }
        }

        $precioTotal = 0;

        # Obtiene el precio del paquete
        $precioModel = DB::table('ViewPrecioPaqInter')->where('Id_intermediario', $idIntermediario)->where('Id_catalogo', $idSub)->first();
        if ($precioModel != NULL) {
            $precioTotal = $precioTotal + $precioModel->Precio;
        } else {
            $precioModel = DB::table('ViewPrecioPaq')->where('Id_paquete', $idSub)->first();
            $precioTotal = $precioTotal +  $precioModel->Precio;
        }
        # Obtener el precio por parametro extra

        if (sizeof($parametroExtra) > 0) {
            for ($i = 0; $i < sizeof($parametroExtra); $i++) {
                # code...
                $precioModel = DB::table('ViewPrecioCatInter')->where('Id_intermediario', $idIntermediario)->where('Id_catalogo', $parametroExtra[$i])->first();
                if ($precioModel != null) {
                    $precioTotal += $precioModel->Precio;
                } else {
                    $precioModel = DB::table('ViewPrecioCat')->where('Id_parametro', $parametroExtra[$i])->first();
                    $precioTotal += $precioModel->Precio;
                }
            }
        }


        $data = array(
            'intermediarios' => $intermediarios,
            'subnorma' => $subnorma,
            'idParametros' => $idParametros,
            'precioTotal' => $precioTotal,
            'servicio' => $servicio,
            'descarga' => $descarga
        );
        return response()->json($data);
    }
}
   