<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\ConcentracionParametro;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\TipoFormula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConcentracionController extends Controller
{
    //
    public function index()
    {
        $norma = Norma::all();
        return view('analisisQ.concentracion',compact('norma'));
    }
    public function getParametroNorma(Request $request)
    {
        $model = DB::table('ViewParametros')->where('Id_norma',$request->idNorma)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getConcentracionParametro(Request $request)
    {
        $model = ConcentracionParametro::where('Id_parametro',$request->idParametro)->get();
        if($model->count())
        {
            
        }else{
            $parametro = Parametro::where('Id_parametro',$request->idParametro)->first();
            $tipoModel = TipoFormula::where('Id_tipo_formula',$parametro->Id_tipo_formula)->first();

            for ($i=0; $i < $tipoModel->Concentracion; $i++) { 
                ConcentracionParametro::create([
                    'Id_parametro' => $request->idParametro,
                    'Concentracion' => 0,
                ]);
            }
            $model = ConcentracionParametro::where('Id_parametro',$request->idParametro)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setConcentracionParametro(Request $request)
    {
        $model = ConcentracionParametro::where('Id_parametro',$request->idParametro)->get();
        for ($i=0; $i < sizeof($request->concentracion); $i++) { 
            $concentracion = ConcentracionParametro::find($model[$i]->Id_concentracion);
            $concentracion->Concentracion = $request->concentracion[$i];
            $concentracion->save();
        }
        $data = array(
            'sw' => true,
        );
        return response()->json($data);
    }
}
