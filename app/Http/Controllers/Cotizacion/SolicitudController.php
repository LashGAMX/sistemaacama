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
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $cotizacion = DB::table('ViewCotizacion')->get();
        $contactoCliente = ContactoCliente::all();
        $normas = Norma::all();
        
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
            'normas'
        ));                
    }

    public function setSolicitudSinCot(Request $request){
        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $cotizacionDay = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->count();

        $numCot = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->get();
        $firtsFol = DB::table('cotizacion')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $request->clientes)->first();
        $cantCot = $numCot->count();
        if ($cantCot > 0) {

            $folio = $firtsFol->Folio . '-' . ($cantCot + 1);
        } else {
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }


        //  var_dump($request->precioAnalisis);

        $cotizacion = Cotizacion::create([
            'Id_intermedio' => $request->intermediario,
            'Id_cliente' => $request->clientes,
            

            'Nombre' => $request->contacto,
            

            'Direccion' => $request->direccionReporte,
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
            'Tipo_muestra' => $request->tipoMuestra,
            'Promedio' => $request->promedio,
            'Numero_puntos' => $request->contPunto,
            'Tipo_reporte' => $request->tipoReporte,


            'Tiempo_entrega' => 0,
            'Observacion_interna' => $request->observacion,
            'Observacion_cotizacion' => "Sin obs",
            'Folio' => $folio,
            'Metodo_pago' => 1,
            'Precio_analisis' => 2000,
            'Descuento' => 200,
            'Precio_muestreo' => 500,
            'Sub_total' => 2300,
            'Costo_total' => 2300,
            'Estado_cotizacion' => 1,
            
                        
            'Creado_por' => Auth::user()->id,
            'Actualizado_por' => Auth::user()->id,
        ]);

        /* $cotizacion = DB::table('cotizacion')->latest('created_at')->first(); */

        CotizacionMuestreo::create([
            'Id_cotizacion' => $cotizacion->Id_cotizacion,
            'Dias_hospedaje' => 0,
            'Hospedaje' => 0,
            'Dias_muestreo' => 0,
            'Num_muestreo' => 0,
            'Caseta' => 0,
            'Km' => 0,
            'Km_extra' => 0,
            'Gasolina_teorico' => 0,
            'Cantidad_gasolina' => 0,
            'Paqueteria' => 0,
            'Adicional' => 0,
            'Num_servicio' => $request->tipoServicio,
            'Num_muestreador' => null,
            'Estado' => null,
            'Localidad' => null,
            'Total' => 0,

            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);

        $parametro = $request->parametrosSolicitud;
        $parametro = explode(',', $parametro);


        foreach ($parametro as $item) {
            $subnorma = NormaParametros::where('Id_norma', $request->subnorma)->where('Id_parametro', $item)->get();

            $extra = 0;
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else {
                $extra = 1;
            }

            CotizacionParametros::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_subnorma' => $item,
                'Extra' => $extra,
            ]);
            echo $item;
        }

        $puntoMuestreo = $request->puntosSolicitud;
        $puntoMuestreo = explode(',', $puntoMuestreo);
        foreach ($puntoMuestreo as $item) {
            CotizacionPunto::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Descripcion' => $item,
            ]);
        }                                                                               
        
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

        if($cotizacion->id_cotizacion > 0)
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
                'Id_cotizacion' => $cotizacion->id_cotizacion,
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

    public function update($idCot)
    {
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->get();
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

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 30,
            'margin_bottom' => 18
        ]);
        
        $mpdf->SetWatermarkImage(
            asset('storage/HojaMembretada.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $mpdf->showWatermarkImage = true;
        $html = view('exports.cotizacion.ordenServicio', compact('model','parametros','qr'));
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function setGenFolio(Request $request){
        //$model = DB::table('frecuencia001')->where('Id_frecuencia', $idFrecuencia)->first();
        $model = DB::table('ViewSolicitud')->where('Id_cotizacion',$request->idCot)->first();
        return response()->json($model);
    }
}
  
 