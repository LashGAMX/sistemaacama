<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\Cotizacion;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DetallesTipoCuerpo;
use App\Models\IntermediariosView;
use App\Models\Norma;
use App\Models\NormaParametros;
use App\Models\SubNorma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cotizacion2Controller extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        $model = DB::table('ViewCotizacion')->get();
        return view('cotizacion.cotizacion', compact('model')); 
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at',null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'metodoPago' => $metodoPago,
        );
        return view('cotizacion.create',$data);
    }
    public function update($id)
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at',null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();

        $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$id)->first();
        $cotizacionParametros = CotizacionParametros::where('Id_cotizacion',$id)->get();
        $cotizacionPuntos = CotizacionPunto::where('Id_cotizacion',$id)->get();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'metodoPago' => $metodoPago,
            'model' => $model,
            'cotizacionParametros' => $cotizacionParametros,
            'cotizacionPuntos' => $cotizacionPuntos,
            'idCotizacion' => $id,
            'sw' => 1,
        ); 
        return view('cotizacion.create',$data);
    }
    public function getParametroCot(Request $request)
    {
        $model = DB::table('ViewCotParam ')->where('Id_cotizacion',$request->idCot)->get();
        return response()->json(compact('model'));
    }
    public function getCotizacionId(Request $request)
    {
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion',$request->idCotizacion)->first();
        return response()->json(compact('model'));
    }
    public function getCliente()
    {
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        return response()->json($model);
    }
    public function getSubNorma()
    {
        $id = $_POST['norma'];
        $model = DB::table('ViewPrecioPaq')->where('Id_norma',$id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSubNormaId()
    {
        $id = $_POST['idSub'];
        $model = DB::table('sub_normas')->where('Id_subnorma',$id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getNorma()
    {
        $diDescarga = $_POST['idDescarga'];
        $model = Norma::where('Id_descarga',$diDescarga)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDatos2()
    {
        $idIntermediario = $_POST['intermediario'];
        $idSub = $_POST['idSub'];
        $idParametros = $_POST['idParametros'];
        $idServicio = $_POST['idServicio'];
        $idDescarga = $_POST['idDescarga'];

        $parametroExtra = array();

        $intermediarios = DB::table('ViewIntermediarios')->where('Id_cliente',$idIntermediario)->first();
        $subnorma = DB::table('sub_normas')->where('Id_subnorma',$idSub)->first();
        $servicio = DB::table('tipo_servicios')->where('Id_tipo',$idServicio)->first();
        $descarga = DB::table('tipo_descargas')->where('Id_tipo',$idDescarga)->first();

        $contExtra = 0;
        for ($i=0; $i < sizeof($idParametros) ; $i++) {
            $parPre = DB::table('norma_parametros')->where('Id_norma',$idSub)->where('Id_parametro',$idParametros[$i])->get();
            if($parPre->count())
            {}else{
                $parametroExtra[$contExtra] = $idParametros[$i];
                $contExtra++;
            }
        }

        $precioTotal = 0;

            # Obtiene el precio del paquete
        $precioModel = DB::table('ViewPrecioPaqInter')->where('Id_intermediario',$idIntermediario)->where('Id_catalogo',$idSub)->first();
        if($precioModel != NULL){
            $precioTotal = $precioTotal + $precioModel->Precio;
        }else{
            $precioModel = DB::table('ViewPrecioPaq')->where('Id_paquete',$idSub)->first();
            $precioTotal = $precioTotal+  $precioModel->Precio;
        }
            # Obtener el precio por parametro extra

        if(sizeof($parametroExtra) > 0)
        {
            for ($i=0; $i < sizeof($parametroExtra); $i++) { 
                # code...
                $precioModel = DB::table('ViewPrecioCatInter')->where('Id_intermediario',$idIntermediario)->where('Id_catalogo',$parametroExtra[$i])->first();
                if($precioModel != null)
                {
                    $precioTotal += $precioModel->Precio;
                }else{
                    $precioModel = DB::table('ViewPrecioCat')->where('Id_laboratorio',1)->where('Id_parametro',$parametroExtra[$i])->first();
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
    public function getTomas()
    {
        $idFrecuencia = $_POST['idFrecuencia'];
        $model = DB::table('frecuencia001')->where('Id_frecuencia',$idFrecuencia)->first();
        return response()->json($model);
    }
    public function setCotizacion(Request $request){

        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $cotizacionDay = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->count();
        
        $numCot = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->where('Id_cliente',$request->clientes)->get();
        $firtsFol = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->where('Id_cliente',$request->clientes)->first();
        $cantCot = $numCot->count();
        if($cantCot > 0)
        {
            
            $folio = $firtsFol->Folio . '-' . ($cantCot + 1); 

        }else{
            $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        }


        $cotizacion = Cotizacion::create([
            'Id_intermedio' => $request->intermediario,
            'Id_cliente' => $request->clientes,
            'Nombre' => $request->nombreCliente, 
            'Direccion' => $request->direccion,
            'Atencion' => $request->atencion,
            'Telefono' => $request->telefono,
            'Correo' => $request->correo,
            'Tipo_servicio' => $request->tipoServicio,
            'Tipo_descarga' => $request->tipoDescarga,
            'Id_norma' => $request->norma,
            'Id_subnorma' => $request->subnorma,
            'Fecha_muestreo' => $request->fecha,
            'Frecuencia_muestreo' => $request->frecuencia,
            'Tomas' => $request->tomas,
            'Tipo_muestra' => $request->tipoMuestra,
            'Promedio' => $request->promedio,
            'Numero_puntos' => $request->promedio,
            'Tipo_reporte' => $request->tipoReporte,
            'Viaticos' => $request->viaticos,
            'Paqueteria' => $request->paqueteria,
            'Adicional' => $request->gastosExtras,
            'Servicio' => $request->numeroServicio,
            'Km_extra' => $request->kmExtra,
            'Precio_km' => $request->precioKm,
            'Precio_km_extra' => $request->precioKmExtra,
            'Tiempo_entrega' => $request->tiempoEntrega,
            'Observacion_interna' => $request->observacionInterna,
            'Observacion_cotizacion' => $request->observacionCotizacion,
            'Folio' => $folio,
            'Metodo_pago' => $request->metodoPago,
            'Costo_total' => $request->precio,
            'Estado_cotizacion' => 1,
            'Creado_por' => Auth::user()->id,
            'Actualizado_por' => Auth::user()->id,
        ]);

        $parametro = $request->parametrosCotizacion;
        $parametro = explode(',',$parametro);


        foreach($parametro as $item)
        {
            $subnorma = NormaParametros::where('Id_norma',$request->subnorma)->where('Id_parametro',$item)->get();

            $extra = 0;
            if($subnorma->count() > 0)
            {
                $extra = 0;
            }else{
                $extra = 1;
            }

            CotizacionParametros::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_subnorma' => $item,
                'Extra' => $extra,
            ]);
            echo $item;
        }

        $puntoMuestreo = $request->puntosCotizacion;
        $puntoMuestreo = explode(',',$puntoMuestreo);
        foreach($puntoMuestreo as $item)
        {
            CotizacionPunto::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Descripcion' => $item,
            ]);
        }
        

        return redirect()->to('admin/cotizacion');
    }
    public function fecha()
    {
        // $year = date("y");
        // $month = date("m");
        // $dayYear = date("z") + 1;
        // $today = Carbon::now()->format('Y-m-d');
        // $cotizacionDay = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->count();
        // $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
        // var_dump($folio);
        // $firtsFol = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->where('Id_cliente',$request->clientes)->first();
        // var_dump($);
    }//

}
