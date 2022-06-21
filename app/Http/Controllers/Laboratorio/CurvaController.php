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
        $parametro = Parametro::all();
     

        $model = "";
        return view('laboratorio/curva', compact('model','lote','area','parametro'));
    }

    public function getParametro(Request $request){
        $idLote = $request->idLote;
        $model = LoteAnalisis::where('Id_lote', $idLote)->first();
        $parametro = Parametro::where('Id_area', $request->idArea)->get();
     
        $data = array(
            'parametro'=> $parametro,
            'idLote' => $idLote,
        );
        return response()->json($data);
    }
    public function getParametroModal(Request $request){ 
        $parametro = Parametro::where('Id_area', $request->idArea)->get();
     
        $data = array(
            'parametro'=> $parametro,
           
        );
        return response()->json($data);
    }
    
    
    public function getLote(Request $request){
        $lote = LoteAnalisis::where('Id_area', $request->idArea)->get();
        $parametro = Parametro::where('Id_area', $request->idArea)->get();
        $data = array(
            'area' => $request->idArea,
            'lote' => $lote,
            'parametro' => $parametro,
           
        );
        return response()->json($data);
    }
     public function buscar(Request $request){
        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $lote = LoteAnalisis::where('Fecha', $request->fecha)->first();

        $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->get(); 

        $concent = ConcentracionParametro::where('Id_parametro',$request->parametro)->get();
        $bmr = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)
            ->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->first();

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
            'fecha' => $today,
        );
        return response()->json($data);
     }
     public function createStd(Request $request)
     {
        
       // $model = estandares::where('Id_Lote', $request->idLote)->get(); 
        $now = Carbon::now();
        $now->toDateString();
        //$loteAnalisis = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        $estandares  = CurvaConstantes::where('Id_area', $request->area)->whereDate('Fecha_inicio', '>=', $now)->whereDate('Fecha_fin', '<=', $now)->first();

        $paraModel = Parametro::find($request->idParametroModal);
        $numEstandares = TipoFormula::where('Id_tipo_formula', $paraModel->Id_tipo_formula)->first();

        $num = $numEstandares->Concentracion; 
        
            estandares::create([
                //'Id_lote' => $request->idLote,
                'Id_area' => $request->idAreaModal,
                'Id_parametro' => $request->idParametroModal,
                'Fecha_inicio' => $request->fechaInicio,
                'Fecha_fin' => $request->fechaFin,
                'STD' => "Blanco", 
            ]);
            CurvaConstantes::create([
                'Id_area' => $request->idAreaModal,
                'Id_parameto' => $request->idParametroModal,
                'Fecha_inicio' => $request->fechaInicio,
                'Fecha_fin' => $request->fechaFin,
    
            ]);
            for ($i=0; $i < $num ; $i++) { 
                estandares::create([
                    //'Id_lote' => $request->idLote,
                    'Id_area' => $request->idAreaModal,
                    'Id_parametro' => $request->idParametroModal,
                    'Fecha_inicio' => $request->fechaInicio,
                    'Fecha_fin' => $request->fechaFin,
                    'STD' => "STD".($i+1)."",
                ]);

               
            }  
            $sw = true;
            
            //$stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
        
        //$loteDetalle = LoteDetalle::where('Id_lote', $request->idLote)->first();
        $concent = ConcentracionParametro::where('Id_parametro',$request->idParametro)->get();



        $data = array(
            'now' => $now,
            'num' => $num,
            'sw' => $sw, 
            'estandares' => $estandares,
            'concentracion' => $concent,
            'parametro' => $request->idParametro,
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
    //------------Guardar BMR-----------
    public function setConstantes(Request $request){ 
       
        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $curvaModel = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)
            ->whereDate('Fecha_fin', '>=', $today)
            ->where('Id_area', $request->area)
            ->where('Id_parametro', $request->parametro)->first();

            $const = CurvaConstantes::find($curvaModel->Id_curvaConst);
            $const->B = $request->b;
            $const->M = $request->m;
            $const->R = $request->r;
            $const->save(); 

        
        
        $sw = true;
         $data = array(
            'model' => $curvaModel,
            'sw' => $sw,
        );
        return response()->json($data);
    }

//-----------Formula para la BMR-----------------------------
    public function formula(Request $request){

        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
        ->where('Id_area', $request->area)
        ->where('Id_parametro', $request->parametro)->get(); 

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
            'm' => $m,
            'b' => $b,
            'r' => $r,

        );
        return response()->json($data);
    }
    public function setCalcular(Request $request)
    {

     // $stdModel = estandares::where('Id_lote',$request->idLote)->get();

        $fecha = new Carbon($request->fecha);
        $today = $fecha->toDateString();
        $stdModel = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
        ->where('Id_area', $request->area)
        ->where('Id_parametro', $request->parametro)->get(); 

      
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
    

       // $model = estandares::where('Id_lote', $idLote)->get();
       $model = estandares::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
       ->where('Id_area', $request->area)
       ->where('Id_parametro', $request->parametro)->get(); 

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

       // $stdModel = estandares::where('Id_Lote', $request->idLote)->get(); 
        


        $data = array(
            'stdModel' => $stdModel,
            'a' => $a,
            'conArra' => $request->conArr,
            'arrCon' => $request->arrCon,
            
            'm' => $m,
            'b' => $b,
            'r' => $r,

        );
        return response()->json($data);
    }

}
 