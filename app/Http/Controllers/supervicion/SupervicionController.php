<?php

namespace App\Http\Controllers\supervicion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervicionController extends Controller
{
    public function analisis()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $tipo = DB::table('tipo_formulas')
        ->where('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
        ->orWhere('Id_tipo_formula',58)
        ->orWhere('Id_tipo_formula',59)
        ->get();
        $data  = array(
            'parametro' => $parametro,
            'tipo' => $tipo,
        );
        return view('supervicion.analisis.analisis',$data); 
    }
    public function getLotes(Request $res)
    {
        // $model = DB::table('viewlotedetalle')->where('Id_parametro',$res->parametro)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        $model = DB::table('ViewLoteAnalisis')
        ->where('Id_tecnica',$res->parametro)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
} 
