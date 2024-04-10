<?php

namespace App\Http\Controllers\kpi;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Norma;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    //
    public function index(){
        $norma = Norma::all();
        $data = array(
            'norma' => $norma,
        );
        return view('kpi.kpi',$data);
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
        ->when($res->norma != 0, function ($query) use ($res) {
            $query->where('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();

        $registrosPorMesHijo = Solicitud::where('Padre',0)
        ->whereDate('Fecha_muestreo', '>=', $res->fechaInicio)
        ->whereDate('Fecha_muestreo', '<=', $res->fechaFin)
        ->when($res->norma != 0, function ($query) use ($res) {
            $query->where('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Fecha_muestreo) as mes, YEAR(Fecha_muestreo) as anio, COUNT(*) as total, DATE_FORMAT(Fecha_muestreo, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();
    

        foreach ($registrosPorMes as $registro) {
            array_push($mes,$registro->mes_anio);
            array_push($total,$registro->total);
        }

        foreach ($registrosPorMesHijo as $item) {
            array_push($totalHijo,$item->total);
        }



        $data = array(
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
        ->when($res->norma != 0, function ($query) use ($res) {
            $query->where('Id_norma', $res->norma);
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
        ->when($res->norma != 0, function ($query) use ($res) {
            $query->where('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();

        $registrosImpresos = DB::table('ViewFoliosImpresos')->whereDate('Hora_recepcion', '>=', $res->fechaInicio)
        ->whereDate('Hora_recepcion', '<=', $res->fechaFin)
        ->when($res->norma != 0, function ($query) use ($res) {
            $query->where('Id_norma', $res->norma);
        })
        ->selectRaw('MONTH(Hora_recepcion) as mes, YEAR(Hora_recepcion) as anio, COUNT(*) as total, DATE_FORMAT(Hora_recepcion, "%M %Y") as mes_anio')
        ->groupBy('mes', 'anio', 'mes_anio')
        ->get();

        // $aux  = 0;

        for ($i=0; $i < sizeof($registrosPendientes); $i++) { 
            array_push($mes,$registrosPendientes[$i]->mes_anio);
            array_push($totalPendiente,$registrosPendientes[$i]->total);
            array_push($totalImpreso,$registrosImpresos[$i]->total);
        }
        // foreach ($registrosPendientes as $registro) {
        //     array_push($mes,$registro->mes_anio);
        //     array_push($totalPendiente,$registro->total);
        //     array_push($totalImpreso,$registrosImpresos[$this->$aux]->total);
        //     $aux++;
        // }
        // foreach ($registrosImpresos as $registro) {
        //     array_push($totalImpreso,$registro->total);

        // }


   

        $data = array(
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
