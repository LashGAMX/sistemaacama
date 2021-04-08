<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Cotizacion\Cotizacion;
use App\Models\Clientes;
use App\Models\Cotizaciones;
use App\Models\DetallesTipoCuerpo;
use App\Models\IntermediariosView;
use App\Models\Norma;
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
        $model = Cotizacion::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        return view('cotizacion.cotizacion', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas'));
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

        $now = Carbon::now();
        $year = $now->format('y');

        // $cotizacion = Cotizaciones::withTrashed()->get();
        // $num = count($cotizacion);
        // $num++;
        // // @$valoresParametros = @$request->valoresParametros;

        $newCotizacion =  Cotizaciones::create([
            'Cliente' => $request->nombreCliente,
            // 'Folio_servicio' => '21-92/' . $year,
            'Cotizacion_folio' => '21-92/' . $year,
            'Empresa' => $request->atencion,
            'Servicio' => $request->tipoServicio,
            'Fecha_cotizacion' => $request->fecha,
            'Supervicion' => 'por Asignar',
            'Telefono' => $request->telefono,
            'Correo' => $request->correo,
            'Tipo_descarga' => $request->tipoDescarga,
            'Tipo_servicio' => $request->tipoServicio,
            'Puntos_muestreo' => $request->puntosMuestreo,
            'Promedio' =>  $request->promedio,
            'Tipo_muestra' => $request->tipoMuestra,
            'frecuencia' => $request->frecuencia,
            // 'Norma_formulario_uno' => $normaFormularioUno,
            'clasificacion_norma' => $request->norma,
            'Reporte' => $request->tipoReporte,
            'condicciones_venta' =>  $request->codiccionesVenta,
            'Viaticos' =>  $request->viaticos,
            'Paqueteria' => $request->paqueteria,
            'Gastos_extras' => $request->gastosExtras,
            'Numero_servicio' => $request->numeroServicio,
            'Km_extra' => $request->kmExtra,
            'observacionInterna' => $request->observacionInterna,
            'observacionCotizacion' => $request->observacionCotizacion,
            'tarjeta' => $request->tarjeta,
            'tiempoEntrega' => NULL,
            'precioKmExtra' => $request->precioKmExtra
        ]);

        return redirect()->to('admin/cotizacion');
    }
    public function fecha()
    {
        $anio = date("y");
        $mes = date("m");
        $diaAnio = date("z") + 1;
        $fechaHoy = Carbon::now()->format('Y-m-d');
        // $cotizacionesPorDia = Cotizacion::whereDate('created_at', '=', $fechaHoy)->count();
        $cotizacionesPorDia = 1;
        // $identificadorCodigo = $diaAnio . "/" . $cotizacionesPorDia . "/" . $anio . "-" . $cotizacionesPorDia;
        $identificadorCodigo = $diaAnio . "-" . $cotizacionesPorDia . "/" . $anio;
        var_dump($identificadorCodigo);
    }

}
