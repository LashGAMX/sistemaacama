<?php

namespace App\Http\Controllers\Kpi;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\Cotizacion;
use App\Models\ImpresionInforme;
use App\Models\Norma;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudPuntos;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    //Vistas
    public function index(){
        $norma = Norma::all();
        $data = array(
            'norma' => $norma,
        );
        return view('kpi.kpi',$data);
    }
    public function laboratorio(){
        $model = DB::table('ViewProcesoAnalisis')->where('Impresion_informe',0)->where('Cancelado',0)->where('Padre',1)->orderBy("Hora_entrada","asc")->get();
        $subModel = DB::table('ViewProcesoAnalisis')->where('Impresion_informe',0)->where('Cancelado',0)->where('Padre',0)->get();
        $siralab = DB::table('ViewProcesoAnalisis')->where('Impresion_informe',1)->whereDate('Hora_entrada', '<=', "2024-04-01")->whereDate('Hora_entrada', '>=', "2024-06-01")->where('Cancelado',0)->where('Siralab',1)->where('Padre',0)->get();
        $diasFolio = array(0,0,0,0,0,0,0);
        foreach ($model as $item) {
            $fechaHoraActual = Carbon::now(); // Obtiene la fecha y hora actual
            $fechaHoraActual->setTimezone('America/Mexico_City'); // Establece la zona horaria
            $fecha1 = Carbon::parse($fechaHoraActual);
            switch ($item->Id_norma) {
                // 11dias
                case 1:
                case 2:
                    $fecha2 = Carbon::parse($item->Hora_entrada)->addDays(11);
                    break;
                 //14dias
                case 5:
                case 30:
                    $fecha2 = Carbon::parse($item->Hora_entrada)->addDays(14);
                // 11 dias
                default:
                    $fecha2 = Carbon::parse($item->Hora_entrada)->addDays(11);
                   
                    break;
            }
            

            // Calcula la diferencia en días
            $diferenciaDias = $fecha1->diffInDays($fecha2);

            // echo "<br> ".$diferenciaDias;

            // Calcula la diferencia en días
            $diferenciaDias = $fecha1->diffInDays($fecha2);

            if ($fecha1->greaterThan($fecha2)) {
                switch ($diferenciaDias) {
                    case 0:
                        $diasFolio[0] = $diasFolio[0] + 1;
                        break;
                    case 1:
                        $diasFolio[1] = $diasFolio[1] + 1;
                        break;
                    case 2:
                        $diasFolio[2] = $diasFolio[2] + 1;
                        break;
                    case 3:
                        $diasFolio[3] = $diasFolio[3] + 1;
                        break;
                    case 4:
                        $diasFolio[5] = $diasFolio[5] + 1;
                        break;
                    case 5:
                        $diasFolio[5] = $diasFolio[5] + 1;
                        break;
                    default:
                       if ($diferenciaDias >= 5 ) {
                          $diasFolio[5] = $diasFolio[5] + 1;
                       }
                       if ($diferenciaDias < 0 ) {
                            $diasFolio[6] = $diasFolio[6] + 1;
                       }
                        break;
                }
            } elseif ($fecha1->lessThan($fecha2)) {
                $diasFolio[6] = $diasFolio[6] + 1;
            } else {
                $diasFolio[6] = $diasFolio[6] + 1;
            }

    
        }
        $data = array(
            'siralab'=> $siralab,
            'diasFolio' => $diasFolio,
            'subModel' => $subModel,
            'model' => $model,
        );
        return view('kpi.laboratorio',$data);
    }
    public function seguimientoFolio(){
        return view('kpi.seguimientoFolio');
    }
    //funciones
    public function getSeguimiento(Request $res)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud',$res->id)->first();
        $proceso = DB::table('ViewProcesoAnalisis')->where('Id_solicitud',$res->id)->first();
        $campo = SolicitudesGeneradas::where('Id_solicitud',$res->id)->first();
        $informe = ImpresionInforme::where('Id_solicitud',$res->id)->get();
        $codigo = DB::table('ViewCodigoRecepcion')->where('Id_solicitud',$res->id)->get();
        $data = array(
            'codigo' => $codigo,
            'proceso' => $proceso,
            'campo' => $campo,
            'informe' => $informe,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getbuscarFolio(Request $res)
    {
        $model = Solicitud::where('Folio_servicio','LIKE','%'.$res->folio.'%')->where('Padre',1)->first();
        $puntos = SolicitudPuntos::where('Id_solPadre',$model->Id_solicitud)->get();
        $data = array(
            'puntos' => $puntos,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getMuestrasPendientes(Request $res)
    {
        $model = Solicitud::where('Padre',1)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function indicadores(Request $res)
    {
        $mesesEnRango = $this->obtenerMesesEnRango($res->fechaInicio, $res->fechaFin);
        $model = Solicitud::where('Padre',1)->whereDate('Fecha_muestreo','>=',$res->fechaInicio)->whereDate('Fecha_muestreo','<=',$res->fechaFin)->get();
        $mes = array();
        $total = array();

        $registrosPorMes = Solicitud::where('Padre', 1)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();
    

        foreach ($registrosPorMes as $registro) {
            array_push($mes,$registro->mes_anio);
            array_push($total,$registro->total);
        }



        $data = array(
            'registrosPorMes' => $registrosPorMes,
            'mes' => $mes,
            'totalInd' => $total,
            'total' => $model->count(),
            'meses' => implode(', ', $mesesEnRango)
        );
        return response()->json($data);
    }
    function solicitudesGeneradas(Request $res){
        $mesesEnRango = $this->obtenerMesesEnRango($res->fechaInicio, $res->fechaFin);
        $model = Solicitud::where('Padre',1)->whereDate('Fecha_muestreo','>=',$res->fechaInicio)->whereDate('Fecha_muestreo','<=',$res->fechaFin)->get();
        $mes = array();
        $total = array();
        $totalHijo = array();
        

        $registrosPorMes = Solicitud::where('Padre', 1)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',0)
        ->get();

        $canceladoGen = Solicitud::where('Padre', 1)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',1)
        ->get();


        $registrosPorMesHijo = Solicitud::where('Padre', 0)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',0)
        ->get();
    
        
        $canceladoHijo = Solicitud::where('Padre', 0)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',1)
        ->get();

        foreach ($registrosPorMes as $registro) {
            array_push($mes,$registro->mes_anio);
            array_push($total,$registro->total);
        }

        foreach ($registrosPorMesHijo as $item) {
            array_push($totalHijo,$item->total);
        }



        $data = array(
            'canceladoHijo' => $canceladoHijo,
            'canceladoGen' => $canceladoGen,
            'registrosPorMesHijo'=> $registrosPorMesHijo,
            'registrosPorMes' => $registrosPorMes,
            'mes' => $mes,
            'totalInd' => $total,
            'totalIndHijo' => $totalHijo,
            'total' => $model->count(), 
            'meses' => implode(', ', $mesesEnRango)
        );
        return response()->json($data);
    }
    function cotizacionesGeneradas(Request $res){
        $mesesEnRango = $this->obtenerMesesEnRango($res->fechaInicio, $res->fechaFin);
        $model = Cotizacion::whereDate('created_at','>=',$res->fechaInicio)->whereDate('created_at','<=',$res->fechaFin)->get();
        $mes = array();
        $total = array();

        $registrosPorMes = Cotizacion::whereDate('created_at', '>=', $res->fechaInicio)
        ->whereDate('created_at', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(created_at) as mes, YEAR(created_at) as anio, COUNT(*) as total, DATE_FORMAT(created_at, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();

     

        foreach ($registrosPorMes as $registro) {
            array_push($mes,$registro->mes_anio);
            array_push($total,$registro->total);
        }

        $data = array(
            'registrosPorMes' => $registrosPorMes,
            'mes' => $mes,
            'totalInd' => $total,
            'total' => $model->count(), 
            'meses' => implode(', ', $mesesEnRango)
        );
        return response()->json($data);
    }
    function ordenServicioProceso(Request $res){
        $mesesEnRango = $this->obtenerMesesEnRango($res->fechaInicio, $res->fechaFin);
        $model = Solicitud::where('Padre',1)->whereDate('Fecha_muestreo','>=',$res->fechaInicio)->whereDate('Fecha_muestreo','<=',$res->fechaFin)->get();
        $mes = array();
        $totalPendiente = array();
        $totalImpreso = array();

        $registrosPendientes = DB::table('ViewProcesoAnalisisNorma')->whereDate('Hora_recepcion', '>=', $res->fechaInicio)
        ->whereDate('Hora_recepcion', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',0)
        ->get();

        $canceladosPendiente = DB::table('ViewProcesoAnalisisNorma')->whereDate('Hora_recepcion', '>=', $res->fechaInicio)
        ->whereDate('Hora_recepcion', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',1)
        ->get();

        $registrosImpresos = DB::table('ViewFoliosImpresos')->whereDate('Hora_recepcion', '>=', $res->fechaInicio)
        ->whereDate('Hora_recepcion', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',0)
        ->get();

        $canceladoImpreso = DB::table('ViewFoliosImpresos')->whereDate('Hora_recepcion', '>=', $res->fechaInicio)
        ->whereDate('Hora_recepcion', '<=', $res->fechaFin)
        ->when($res->norma[0] != 0, function ($query) use ($res) {
            $query->whereIn('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->where('Cancelado',1)
        ->get();

        // $aux  = 0;

        for ($i=0; $i < sizeof($registrosPendientes); $i++) { 
            array_push($mes,$registrosPendientes[$i]->mes_anio);
            array_push($totalPendiente,$registrosPendientes[$i]->total);
            array_push($totalImpreso,$registrosImpresos[$i]->total);
        }
  

   

        $data = array(
            'canceladoImpreso' => $canceladoImpreso,
            'canceladosPendiente' => $canceladosPendiente,
            'registrosPendientes' => $registrosPendientes,
            'registrosImpresos' => $registrosImpresos,
            'mes' => $mes,
            'totalPendiente' => $totalPendiente,
            'totalImpreso' => $totalImpreso,
            'total' => $model->count(), 
            'meses' => implode(', ', $mesesEnRango)
        );
        return response()->json($data);
    }
    function obtenerMesesEnRango($fechaInicio, $fechaFin) {
        $mesesYAnios = [];

        $fechaActual = new DateTime($fechaInicio);
        $fechaFinObj = new DateTime($fechaFin);
    
        while ($fechaActual <= $fechaFinObj) {
            $mesesYAnios[] = $fechaActual->format('F Y'); // Formato: "mes año" (por ejemplo, "enero 2024")
            $fechaActual->modify('+1 month'); // Avanza al siguiente mes
        }
    
        return $mesesYAnios;
    }
    
}
