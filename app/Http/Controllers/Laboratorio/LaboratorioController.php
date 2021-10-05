<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Parametro;  
=======
use Illuminate\Support\Facades\DB;
>>>>>>> 098f4e4c206c35427395823875e8a02319129083

class LaboratorioController extends Controller
{
    function index(){ 
        return view('laboratorio.laboratorio'); 
    }
  
    public function analisis(){        
        $model = DB::table('proceso_analisis')->get();
        $elements = DB::table('proceso_analisis')->count();

        //Para buscar la Norma de la solicitud
        $solicitud = DB::table('ViewSolicitud')->get();
        
        return view('laboratorio.analisis', compact('model', 'elements', 'solicitud'));
    }
    
    public function analisisDatos(){
        $norma = DB::table('')->get();

        return response()->json(compact('norma'));
    }
 
    public function observacion(){
        return view('laboratorio.observacion');
    }
    
    public function tipoAnalisis(){ 
        return view('laboratorio.tipoAnalisis');
    }
    public function captura()
    {
        $parametro = Parametro::all();
        return view('laboratorio.captura',compact('parametro'));
    }
    public function lote()
    {
        return view('laboratorio.lote');
    }
    public function asignar()
    {
        return view('laboratorio.asignar');
    }
}
