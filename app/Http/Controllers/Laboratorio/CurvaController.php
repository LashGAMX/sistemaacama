<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\estandares;
use App\Models\Formulas;

class CurvaController extends Controller
{
    public function index(){

        $model = Estandares::all();
        $formula = Formulas::all();
        return view('laboratorio/curva',compact('model','formula'));
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
    }

}
