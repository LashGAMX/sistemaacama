<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('laboratorio.captura');
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
