<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\ConcentracionParametro;
use App\Models\HistorialAnalisisqConcentracion;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\TipoFormula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $model = DB::table('ViewParametroNorma')->where('Id_norma',$request->idNorma)->orderBy('Parametro','ASC')->get();
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
                $model = ConcentracionParametro::create([
                    'Id_parametro' => $request->idParametro,
                    'Concentracion' => 0,
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);

                $nota = 'Creación de registro';
                // $this->historial($nota, $model->Id_concentracion);
            }
            $model = ConcentracionParametro::where('Id_parametro',$request->idParametro)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function historial($nota, $idConcentracion)
    {        
        $model = DB::table('concentracion_parametro')->where('Id_concentracion', $idConcentracion)->first();
        HistorialAnalisisqConcentracion::create([
            'Id_concentracion' => $model->Id_concentracion,
            'Id_parametro' => $model->Id_parametro,
            'Concentracion' => $model->Concentracion,
            'Nota' => $nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => Auth::user()->id
        ]);
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
