<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\estandares;
use App\Models\Formulas;

class CurvaController extends Controller
{
    public function index(){
        $model = "";
        return view('laboratorio/curva', compact('model'));
    }
     public function buscar(Request $request){
        $id = $request->idLote;
        $model = estandares::where('Id_Lote', $id)->get();
        return view('laboratorio/curva',compact('model'));
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

    public function guardar(Request $request){

        $idLote = $request->idLote;
        $std = $request->std;
        $concentracion = $request->concentracion;
        $abs1 = $request-> abs1;
        $abs2 = $request->abs2;
        $abs3 = $request->abs3;
        $promedio = $request->promedio;

       $model = estandares::create([
        'Id_lote' => $idLote,
        'STD' => $std,
        'Concentracion' => $concentracion,
        'ABS1' => $abs1,
        'ABS2'=> $abs2,
        'ABS3' => $abs2,
        'Promedio' => $promedio
        ]);

        $data = array(
            'model' => $model
        );
        return response()->json($data);
        //return view('laboratorio/curva');
    }

    public function formula(Request $request){

        $idLote = $request->idLote;
        $model = estandares::where('Id_lote', $idLote)->get();
        $c1 = 0;
        $b1 = 0;
        $bSuma = 0;
        $bc = 0;
        $a = 0;

        foreach ($model as $item){
            $a = $a + 1; //numero de estandares
            $c1 = $c1 + $item->Promedio; // suma de los promedios
            $bSuma = $bSuma + $item->Concentracion; //suma de concentración
            $b1 += ($item->Concentracion * $item->Concentracion); //suma de concentración elevada al cuadrado
            $bc = $bc + $item->Concentracion * $item->Promedio; //Producto de b y c

        }
        $s1 = $c1 * $b1;
        $s3 = $bc * $bSuma;
        $s5 = $a * $b1;
        $s6 = $bSuma * $bSuma; //elevacion al cuadrado

        $b = ($s1 -$s3)/($s5 - $s6);




        $data = array(
            'idLote' => $idLote,
            'S1' => $s1,
            'S3' => $s3,
            'S5' => $s5,
            'S6' => $s6,
            'b' => $b,

        );
        return response()->json($data);
    }

}
