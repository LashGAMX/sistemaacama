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

        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $cotizacionDay = DB::table('cotizacion')->where('created_at','LIKE',"%{$today}%")->count();
        $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;

        // $cotizacion = Cotizacion::create([
        //     'Id_intermedio' => $_POST['intermediario'],
        //     'Id_cliente' => $_POST['clientes'],
        //     'Nombre' => $_POST['nombreCliente'],
        //     'Direccion' => $_POST['direccion'],
        //     'Atencion' => $_POST['atencion'],
        //     'Telefono' => $_POST['telefono'],
        //     'Correo' => $_POST['correo'],
        //     'Tipo_servicio' => $_POST['tipoServicio'],
        //     'Tipo_descarga' => $_POST['tipoDescarga'],
        //     'Id_norma' => $_POST['norma'],
        //     'Id_subnorma' => $_POST['subnorma'],
        //     'Frecuencia_muestreo' => $_POST['frecuencia'],
        //     'Tipo_muestra' => $_POST['tipoMuestra'],
        //     'Promedio' => $_POST['promedio'],
        //     // 'Numero_puntos' => ,
        //     'Tipo_reporte' => $_POST['tipoReporte'],
        //     // 'Condicion_venta' => ,
        //     'Metodo_pago' => $_POST['metodoPago'],
        //     'Tiempo_entrega' => $_POST['tiempoEntrega'],
        //     'Costo_total' => $_POST['precio'],
        //     // 'Supervicion' => ,
        //     'Folio' => $folio,
        //     // 'Fecha_cotizacion' => $_POST['fecha'],
        //     'Estado_cotizacion' => 1, 
        // ]);

        
        return redirect()->to('admin/cotizacion');
    }
    public function fecha()
    {
        $anio = date("y");
        $mes = date("m");
        $diaAnio = date("z") + 1;
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $cotizacionesPorDia = DB::table('cotizacion')->where('created_at','LIKE',"%{$fechaHoy}%")->count();
        
        // $identificadorCodigo = $diaAnio . "/" . $cotizacionesPorDia . "/" . $anio . "-" . $cotizacionesPorDia;
        $identificadorCodigo = $diaAnio . "-" . $cotizacionesPorDia . "/" . $anio;
        var_dump($cotizacionesPorDia + 1);
    }

}
