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
use App\Models\TipoFormula;
use Carbon\Carbon;

class CurvaController extends Controller
{
    public function index(){
        $lote = LoteAnalisis::all();
        $area = AreaAnalisis::all();
        $model = "";
        return view('laboratorio/curva', compact('model','lote','area'));
    }

    public function getParametro(Request $request){
        $idLote = $request->idLote;
        $model = LoteAnalisis::where('Id_lote', $idLote)->first();
        $parametro = Parametro::where('Id_area', $request->idArea)->get();
     
        $data = array(
            'model'=> $parametro,
        );
        return response()->json($data);
    }
    public function getParametroModal(Request $request ){
        $parametro = Parametro::where('Id_area', $request->idArea)->get();

        $data = array(
            
            'parametro' => $parametro
        );
        return response()->json($data);
    }
    public function getLote(Request $request){
        $lote = LoteAnalisis::where('Id_area', $request->idArea)->get();

        $data = array(
            'area' => $request->idArea,
            'lote' => $lote,
        );
        return response()->json($data);
    }
     public function buscar(Request $request){
        $lote = LoteAnalisis::where('Fecha', $request->fecha)->first();
        $model = estandares::where('Id_Lote', $lote->Id_lote)->get(); 
        //$loteDetalle = LoteDetalle::where('Id_lote',$request->idLote)->first();
        $concent = ConcentracionParametro::where('Id_parametro',$request->parametro)->get();
        $bmr = CurvaConstantes::where('Id_lote', $lote->id_lote)->first();

        if($model->count()){
            $sw = true;
        }else{
            $sw = false;
        }
        if($bmr != "" ){
            $valbmr = true;
        }else{
            $valbmr = false;
        }
        $data = array(
            'stdModel' => $model,
            'concentracion' => $concent,
            'valbmr' => $valbmr,
            'bmr' => $bmr,
            'sw' => $sw,
        );
        return response()->json($data);
     }
     public function createStd(Request $request)
     {
        
        $model = estandares::where('Id_Lote', $request->idLote)->get(); 
        $loteAnalisis = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        $paraModel = Parametro::find($loteAnalisis->Id_tecnica);
        $numEstandares = TipoFormula::where('Id_tipo_formula', $paraModel->Id_tipo_formula)->first();

        $num = $numEstandares->Concentracion;
         if($model->count()){
             $sw = false; 
             $stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
         }else{
            estandares::create([
                'Id_lote' => $request->idLote,
                'STD' => "Blanco",
            ]);
            for ($i=0; $i < $num ; $i++) { 
                estandares::create([
                    'Id_lote' => $request->idLote,
                    'STD' => "STD".($i+1)."",
                ]);
            }  
            $sw = true;
            
            $stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
        }
        $loteDetalle = LoteDetalle::where('Id_lote', $request->idLote)->first();
        $concent = ConcentracionParametro::where('Id_parametro',$loteDetalle->Id_parametro)->get();



        $data = array(
            'num' => $num,
            'sw' => $sw, 
            'concentracion' => $concent,
            'stdModel' => $stdModel,
        );
        return response()->json($data);
     }

    public function promedio(Request $request){
        $promedio = $request->promedio;
        $suma = $request->abs1 + $request->abs2 + $request->abs3;
        $resultado = $suma / 3;


        $data = array(
            'resultado' => $promedio,
            'suma' => $suma,
            'resultado' => $resultado
        );
        return response()->json($data);
    }
    public function setConstantes(Request $request){ 
        $lote = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        $fechaNow = Carbon::now();
    

        if($lote->count()){
            $curvaModel = CurvaConstantes::where('Id_lote', $request->idLote)->first();
            $const = CurvaConstantes::find($curvaModel->Id_curvaConst);
            $const->B = $request->b;
            $const->M = $request->m;
            $const->R = $request->r;
            $const->Fecha_inicio = $fechaNow;
            $const->save(); 
        }else{
            $model = CurvaConstantes::create([
                'Id_lote' => $request->idLote,
                'B' => $request->b,
                'M' => $request->m,
                'R' => $request->r,
                'Fecha_inicio' => $fechaNow,
            ]); 
        }

        
        
        $sw = true;
         $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }


    public function formula(Request $request){

        $idLote = $request->idLote;
        $model = estandares::where('Id_lote', $idLote)->get();
        $c1 = 0;
        $b1 = 0;
        $bSuma = 0;
        $cElevada = 0;
        $bc = 0;
        $a = 0;

        foreach ($model as $item){
            $a = $a + 1; //numero de estandares
            $c1 = $c1 + $item->Promedio; // suma de los promedios
            $bSuma = $bSuma + $item->Concentracion; //suma de concentración
            $b1 += ($item->Concentracion * $item->Concentracion); //suma de concentración elevada al cuadrado
            $bc = $bc + $item->Concentracion * $item->Promedio; //Producto de b y c
            $cElevada = $cElevada + $item->Promedio * $item->Promedio; //Suma de c elevada a 2

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
        $b = ($s1 - $s3)/($s5 - $s6);
        $m = ($m1 - $m2)/($m3 - $m4);
        $r = ($m1 - $r2)/sqrt($rFinal);


        $data = array(
            'idLote' => $idLote,
            'm' => $m,
            'b' => $b,
            'r' => $r,

        );
        return response()->json($data);
    }
    public function setCalcular(Request $request)
    {
        $idLote = $request->idLote;

        $stdModel = estandares::where('Id_lote',$request->idLote)->get();
      
        for ($i=0; $i < $request->conArr; $i++) { 
            $prom = ($request->arrCon[1][$i] + $request->arrCon[2][$i] + $request->arrCon[3][$i]) / 3;

            $stdM = estandares::find($stdModel[$i]->Id_std);
            $stdM->Concentracion = $request->arrCon[0][$i];
            $stdM->ABS1 = $request->arrCon[1][$i];
            $stdM->ABS2 = $request->arrCon[2][$i];
            $stdM->ABS3 = $request->arrCon[3][$i];
            $stdM->Promedio = round($prom,4);
            $stdM->save();
        }
    

        $model = estandares::where('Id_lote', $idLote)->get();
        $c1 = 0;
        $b1 = 0;
        $bSuma = 0;
        $cElevada = 0;
        $bc = 0;
        $a = 0;

        foreach ($model as $item){
            $a = $a + 1; //numero de estandares
            $c1 = $c1 + $item->Promedio; // suma de los promedios
            $bSuma = $bSuma + $item->Concentracion; //suma de concentración
            $b1 += ($item->Concentracion * $item->Concentracion); //suma de concentración elevada al cuadrado
            $bc = $bc + $item->Concentracion * $item->Promedio; //Producto de b y c
            $cElevada = $cElevada + $item->Promedio * $item->Promedio; //Suma de c elevada a 2

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
        $b = ($s1 - $s3)/($s5 - $s6);
        $m = ($m1 - $m2)/($m3 - $m4);
        $r = ($m1 - $r2)/sqrt($rFinal);

        $stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
        


        $data = array(
            'stdModel' => $stdModel,
            'idLote' => $idLote,
            'm' => $m,
            'b' => $b,
            'r' => $r,

        );
        return response()->json($data);
    }

}
 