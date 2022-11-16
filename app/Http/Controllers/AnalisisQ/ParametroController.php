<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\AreaAnalisis;
use App\Models\Laboratorio;
use App\Models\MatrizParametro;
use App\Models\MetodoPrueba;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\ParametroNorma;
use App\Models\ProcedimientoAnalisis;
use App\Models\Rama; 
use App\Models\Regla;
use App\Models\SimbologiaInforme;
use App\Models\SimbologiaParametros;
use App\Models\Sucursal;
use App\Models\Tecnica;
use App\Models\TipoFormula;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParametroController extends Controller
{
    //
    public function index()
    {
        $laboratorios = Sucursal::all();
        $unidades = Unidad::all();
        $tipos = TipoFormula::all();
        $areas = AreaAnalisis::all();
        $normas = Norma::all();
        $matrices = MatrizParametro::all();
        $ramas = Rama::all();
        $metodos = MetodoPrueba::all();
        $tecnicas = Tecnica::all();
        $procedimientos = ProcedimientoAnalisis::all();
        $simbologias = SimbologiaParametros::all();
        $simbologiasInf = SimbologiaInforme::all();
        $parametroPadre = Parametro::where('Curva', 1)->get();


        $data = array(
            'laboratorios' => $laboratorios,
            'unidades' => $unidades,
            'tipos' => $tipos,
            'areas' => $areas,
            'normas' => $normas,
            'matrices' => $matrices,
            'ramas' => $ramas,
            'metodos' => $metodos,
            'tecnicas' => $tecnicas,
            'procedimientos' => $procedimientos,
            'simbologias' => $simbologias,
            'simbologiasInf' => $simbologiasInf,
            'parametroPadre' => $parametroPadre,
        );
        return view('analisisQ.parametro', $data);
    }
    public function getParametros(Request $res)
    {
        $model = DB::table('ViewParametros')->get();
        $norma = array();


        foreach ($model as $item) {
            $temp = "";
            $mod = DB::table('ViewParametroNorma')->where('Id_parametro', $item->Id_parametro)->get();
            foreach ($mod as $item2) {
                $temp .= " " . $item2->Clave_norma . " //";
            }
            array_push($norma, $temp);
        }
        $data = array(
            'model' => $model,
            'norma' => $norma,
        );
        return response()->json($data);
    }
    public function getDatoParametro(Request $res)
    {
        $model = DB::table('ViewParametros')->where('Id_parametro',$res->id)->first();
        $norma = DB::table('ViewParametroNorma')->where('Id_parametro', $res->id)->get();
        $data = array( 
            'model' => $model,
            'norma' => $norma,
        );
        return response()->json($data);
    }
    public function updateParametro(Request $res)
    {
        $curva = 0;
        if($res->curva == "true"){
            $curva = 1;
        }
        Parametro::withTrashed()->find($res->id)->restore();
        $model = Parametro::find($res->id);
        $model->Id_laboratorio = $res->sucursal;
        $model->Id_tipo_formula = $res->tipo;
        $model->Id_area = $res->area;
        $model->Id_rama = $res->rama;
        $model->Parametro = $res->parametro;
        $model->Id_unidad = $res->unidad;
        $model->Id_metodo = $res->metodo;
        $model->Id_tecnica = $res->tecnica;
        $model->Limite = $res->limite;
        $model->Id_procedimiento = $res->procedimiento;
        $model->Id_matriz = $res->matriz;
        $model->Id_simbologia = $res->simbologia;
        $model->Id_simbologia_info = $res->simbologiaInf;
        $model->Id_user_m = Auth::user()->id;
        $model->Curva = $curva;
        $model->save();

        $model = DB::table('parametros_normas')->where('Id_parametro',$res->id)->delete();

        for ($i=0; $i < sizeof($res->norma); $i++) { 
            $model = ParametroNorma::create([
                'Id_norma' => $res->norma[$i],
                'Id_parametro' => $res->id,
            ]);
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
}
