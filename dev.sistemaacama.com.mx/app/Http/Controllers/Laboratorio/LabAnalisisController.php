<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\GrasasDetalle;
use App\Models\LoteAnalisis;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabAnalisisController extends Controller
{
    //
    public function captura()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $data = array(
            'model' => $parametro,
        );
        return view('laboratorio.analisis.captura',$data);
    }
    public function getPendientes(Request $res)
    {
        $model = array();
        $temp = array();
        $codigo = DB::table('ViewCodigoParametro')->where('Asignado', 0)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp, $item->Codigo);
                    array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                    array_push($temp, $item->Hora_recepcion);
                    array_push($model, $temp);
                    break;
                }
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getLote(Request $res)
    {
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica',$res->id)->where('Fecha',$res->fecha)->get();
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLote(Request $res) 
    {
        $parametro = Parametro::where('Id_parametro',$res->id)->first();
        $model = LoteAnalisis::create([
            'Id_area' => $parametro->Id_area,
            'Id_tecnica' => $res->id,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id,
        ]);
        if ($res->tipo == 13) {
            GrasasDetalle::create([
                'Id_lote' => $model->Id_lote,
            ]);
        }
        $data = array( 
            'model' => $model
        );
        return response()->json($data);
    }
    public function getMuestraSinAsignar(Request $res)
    {
        if ($res->fecha != "") {
            
        } else {
            $model = 
        }
        
        $data = array( 
            'model' => $model
        );
        return response()->json($data);
    }
}
 