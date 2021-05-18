<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\LimiteParametros001;
use App\Models\ContactoCliente;
use App\Models\Cotizacion;
use App\Models\DireccionReporte;
use App\Models\Intermediario;
use App\Models\NormaParametros;
use App\Models\PuntoMuestreoGen;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SucursalCliente;
use App\Models\TipoDescarga;
use App\Models\TipoServicios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PDF;

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
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $cotizacion = DB::table('ViewCotizacion')->where('Id_cotizacion',$idCot)->get();
        $sw = false;
        return view('cotizacion.createSolicitud',
        compact(
            'sw',
            'cotizacion',
            'idCot',
            'intermediario', 
            'cliente',
            'servicios',
            'descargas',
            'frecuencia',
        ));
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
        return view('cotizacion.createSolicitud',
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
            $model = Solicitud::create([
                'Id_cotizacion' => $request->idCotizacion,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $request->intermediario,
                'Id_cliente' => $request->clientes,
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
        $model = PuntoMuestreoGen::where('Id_sucursal',$request->idSuc)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function exportPdfOrden($idOrden)
    {
        $model = DB::table('ViewSolicitud')->where('Id_cotizacion',$idOrden)->first();
        $parametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$model->Id_solicitud)->get();
        $pdf = PDF::loadView('exports.cotizacion.ordenServicio',compact('model','parametros'))->setPaper('letter','portrait');
        
        $canvas $pdf->getCanvas();

        // Renderizamos el documento PDF.
        return $pdf->stream('prueba.pdf');
    }
}
  
 