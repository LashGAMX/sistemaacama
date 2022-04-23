<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\Parametro;
use Illuminate\Http\Request;

class LimitesNormaController extends Controller
{
   public function index(){
    return view('analisisQ.limitesNorma');
    }

    public function getParametros(Request $request){

        $parametro = Parametro::where('Id_norma', $request->norma)->get();
        
        $data = array(
            'parametro' => $parametro,
            'norma' => $$request->norma,
        );
        
        return response()->json($data);
    }
}
   
