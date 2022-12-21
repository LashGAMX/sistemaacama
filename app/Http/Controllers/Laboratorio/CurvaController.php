<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\estandares;
use App\Models\Formulas;
use App\Models\Parametro;
use App\Models\Constante;
use App\Models\AreaAnalisis;
use App\Models\ConcentracionParametro;
use App\Models\LoteAnalisis;
use App\Models\CurvaConstantes;
use App\Models\LoteDetalle;
use App\Models\ParametroUsuario;
use App\Models\TipoFormula;
use Illuminate\Support\Facades\DB;
use App\Models\VariablesFormula;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CurvaController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $area = AreaAnalisis::where('Id_area_analisis', 2)->orWhere('Id_area_analisis', 16)->get();
        $parametro = Parametro::all();


        $model = "";
        return view('laboratorio/curva', compact('model', 'area', 'parametro','idUser'));
    }

    public function getParametro(Request $request) // Obtiene el parametro fuera de la modal
    {
        //*? funcion para obtener parametro por ususarios -------------------------------------
            
            $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', $request->idUser)->where('Curva', 1)->get();
           
        //*?-----------------------------------------------------------------------------------

        $data = array(
            'parametro' => $parametro,
        );
        return response()->json($data);
    }

    public function getLote(Request $request)
    {
        $lote = LoteAnalisis::where('Id_area', $request->idArea)->get();
        $parametro = Parametro::where('Id_area', $request->idArea)->get();
        $data = array(
            'area' => $request->idArea,
            'lote' => $lote,
            'parametro' => $parametro,

        );
        return response()->json($data);
    }
    public function buscar(Request $request)
    {
        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $lote = LoteAnalisis::where('Fecha', $request->fecha)->first();

        $hijos = Parametro::where('Padre', $request->parametro)->get();

        $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area) 
            ->where('Id_parametro', $request->parametro)->get();

        $concent = ConcentracionParametro::where('Id_parametro', $request->parametro)->get();
        $bmr = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)
            ->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->first();

        if ($model->count()) {
            $sw = true;
        } else {
            $sw = false;
        }
        if ($bmr != "") {
            $valbmr = true;
        } else {
            $valbmr = false;
        }
        $data = array(
            'parametro' => $request->parametro,
            'area' => $request->area,
            'stdModel' => $model,
            'concentracion' => $concent,
            'valbmr' => $valbmr,
            'bmr' => $bmr,
            'sw' => $sw,
            'fecha' => $today,
            'hijos' => $hijos,
        );
        return response()->json($data);
    }
    public function curvaHijos(Request $request){
        $hijos = Parametro::where('Padre', $request->idParametro)->get();

        $inicio = new Carbon($request->fechaInicio);
        $fin = new Carbon($request->fechaFin);
        $fechaInicio = $inicio->toDateString();
        $fechaFin = $fin->toDateString(); 

        for($i=0; $i < sizeof($hijos); $i++){
                    CurvaConstantes::create([
                        'Id_area' => $request->idArea,
                        'Id_parametro' => $hijos[$i]->Id_parametro,
                        'Fecha_inicio' => $fechaInicio,
                        'Fecha_fin' => $fechaFin,
                    ]);
                }

        $data = array(
            'hijos' => $hijos,
        );
        return response()->json($data);
    }
    public function createStd(Request $request)
    {
        $swCon = 0;
        $sw = 0;
        $const = null;
        $valFecha = 0;

        $inicio = new Carbon($request->fechaInicio);
        $fin = new Carbon($request->fechaFin);
        $fechaInicio = $inicio->toDateString();
        $fechaFin = $fin->toDateString();

        //comprobacion de bmr para validar existencia
        $estandares  = CurvaConstantes::whereDate('Fecha_inicio', '<=', $fechaInicio)->whereDate('Fecha_fin', '>=', $fechaFin)
        ->where('Id_area', $request->idArea)
        ->where('Id_parametro', $request->idParametro)->first();

       

        $concent = ConcentracionParametro::where('Id_parametro', "=", $request->idParametro)->get(); //valores de concentración

       if ($estandares != null) {
            $sw = 1;
        } elseif($concent != null) {
        
                $paraModel = Parametro::find($request->idParametro);
                $numEstandares = TipoFormula::where('Id_tipo_formula', $paraModel->Id_tipo_formula)->first();

                $num = $numEstandares->Concentracion;

                CurvaConstantes::create([
                    'Id_area' => $request->idArea,
                    'Id_parametro' => $request->idParametro,
                    'Fecha_inicio' => $fechaInicio,
                    'Fecha_fin' => $fechaFin,
            
                ]);
               
                if ($request->idArea == 2 || $request->idParametro == 95 || $request->idParametro == 243){
                    //Creacion del blanco
                estandares::create([
                    //'Id_lote' => $request->idLote,
                    'Id_area' => $request->idArea,
                    'Id_parametro' => $request->idParametro, 
                    'Fecha_inicio' => $fechaInicio,
                    'Fecha_fin' => $fechaFin,
                    'STD' => "Blanco",
                    ]);
                } 
                    
                for ($i = 0; $i < $num; $i++) {
                    estandares::create([
                        'Id_area' => $request->idArea,
                        'Id_parametro' => $request->idParametro,
                        'Fecha_inicio' => $fechaInicio,
                        'Fecha_fin' => $fechaFin,
                        'STD' => "STD" . ($i + 1) . "",
                    ]);
                }
                $valFecha = "entro a create";
                $sw = 0;
                $swCon = 0;
                //$valFecha = "entro a create";
           
               // $swCon = 1;
            
            
        } else {
            $swCon = 1;
        }
    


        $data = array(
            'sw' => $sw,
            'swCon' => $swCon,
            'estandares' => $estandares,
            'parametro' => $request->idParametro,
            'concentracion' => $concent,
            'valFecha' => $valFecha,
           
           
        );
        return response()->json($data);
    }
    //------------Guardar BMR-----------
    public function setConstantes(Request $request)
    {
        $model = 0;

        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $curvaModel = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)
            ->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->first();

        $hijos = CurvaConstantes::where('Id_parametroPadre',$request->parametro)->get();
        for($i = 0; $i < sizeof($hijos); $i++){
        $model = CurvaConstantes::where('Id_parametroPadre',$hijos[0]->Id_parametroPadre)->first();
            $model->B = $request->b;
            $model->M = $request->m;
            $model->R = $request->r;
            $model->save();
        }

        $const = CurvaConstantes::find($curvaModel->Id_curvaConst);
        $const->B = $request->b;
        $const->M = $request->m;
        $const->R = $request->r;
        $const->save();



        $sw = true;
        $data = array(
            'CurvaModel' => $curvaModel,
            'sw' => $sw,
            'hijos' => $hijos,
            'model' => $model,
        );
        return response()->json($data);
    }

    //-----------Formula para la BMR-----------------------------
   
    public function setCalcular(Request $request)
    {

        // $stdModel = estandares::where('Id_lote',$request->idLote)->get();

        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $stdModel = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->get();


        for ($i = 0; $i < $request->conArr; $i++) {
            $prom = ($request->arrCon[1][$i] + $request->arrCon[2][$i] + $request->arrCon[3][$i]) / 3;

            $stdM = estandares::find($stdModel[$i]->Id_std);
            $stdM->Concentracion = $request->arrCon[0][$i];
            $stdM->ABS1 = $request->arrCon[1][$i];
            $stdM->ABS2 = $request->arrCon[2][$i];
            $stdM->ABS3 = $request->arrCon[3][$i];
            $stdM->Promedio = round($prom, 4);
            $stdM->save();
        }


        // $model = estandares::where('Id_lote', $idLote)->get();
        $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->get();

        $c1 = 0;
        $b1 = 0;
        $bSuma = 0;
        $cElevada = 0;
        $bc = 0;
        if ($request->area == 2){
            $a = 0;
        } else {
        $a = 1;
        }
        foreach ($model as $item) {
            $a = $a + 1; //numero de estandares
            $c1 = $c1 + $item->Promedio; // suma de los promedios
            $bSuma = $bSuma + $item->Concentracion; //suma de concentración
            $b1 = $b1 + ($item->Concentracion * $item->Concentracion); //suma de concentración elevada al cuadrado
            $bc = $bc + $item->Concentracion * $item->Promedio; //Producto de b y c
            $cElevada = $cElevada + $item->Promedio * $item->Promedio; //Suma de c elevada a 2
        }

        if ($request->area != 16 || $request->parametro == 96) {
            $a = $a; //si contempla el blanco como número de estandares
        } else {
            $a = $a - 1; //No contempla el blanco como número de estandares
        }


        //todo:: b
        $s1 = $c1 * $b1;
        $s3 = $bc * $bSuma;
        $s5 = $a * $b1;
        $s6 = $bSuma * $bSuma; //elevacion al cuadrado
        //todo:: m
        $m1 = $a * $bc;
        $m2 = $bSuma * $c1;
        $m3 = $b1 * $a;
        $m4 = $s6; // misma operación de s6
        //todo:: r
        $r2 = $c1 * $bSuma;
        $r3 = $a * $b1;
        //r4 es igual a s6
        $r5 = $a * $cElevada;
        $r6 = $c1 * $c1;
        $rFinal = ($r3 - $s6) * ($r5 - $r6);

        //todo:: Formulas finales
        $b = ($s1 - $s3) / ($s5 - $s6);
        $m = ($m1 - $m2) / ($m3 - $m4);
        $r = ($m1 - $r2) / sqrt($rFinal);

        // $stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
        $stdModel = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->get();


        $data = array(
            'stdModel' => $stdModel,
            's1' => $s1,
            's3' => $s3,
            's5' => $s5,
            's6' => $s6,
            'model' => $model,
            'a' => $a,
            'c1' => $c1,
            'bsuma' => $bSuma,
            'b1' => $b1,
            'bc' => $bc,
            'cElevada' => $cElevada,
            'm' => $m,
            'b' => $b,
            'r' => $r,

        );
        return response()->json($data);
    }
}
