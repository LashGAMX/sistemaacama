<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\Reportes;
use Illuminate\Support\Facades\DB;

class LaboratorioController extends Controller
{
    function index(){ 
        return view('laboratorio.laboratorio');  
    }
  
    public function analisis(){        
        $model = DB::table('proceso_analisis')->get();

        //Devuelve el tamaño del arreglo model
        $elements = DB::table('proceso_analisis')->count();

        //Para buscar el punto de muestreo
        $puntoMuestreo = DB::table('cotizacion_puntos')->get();
        $puntoMuestreoLength = DB::table('cotizacion_puntos')->count();
        $solicitudPuntos = DB::table('solicitud_puntos')->get();
        $solicitudPuntosLength = DB::table('solicitud_puntos')->count();

        //Para buscar la Norma de la solicitud
        $solicitud = DB::table('ViewSolicitud')->get();        

        //Para buscar los parámetros de la solicitud
        $parametros = DB::table('parametros')->get();
        
        return view('laboratorio.analisis', compact('model', 'elements', 'solicitud', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'puntoMuestreo', 'puntoMuestreoLength'));
    }
     
    public function observacion(){
        $formulas = DB::table('tipo_formulas')->get();
        return view('laboratorio.observacion', compact('formulas'));
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
        $formulas = DB::table('tipo_formulas')->get();
        return view('laboratorio.lote', compact('formulas'));
    }
    public function asignar()
    {
        return view('laboratorio.asignar');
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request){        
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        $lote = DB::table('reportes')->where('Id_reporte', $idLote)->get();
        
        if($lote->count()){
            $texto = Reportes::find($idLote);
            $texto->Texto = $textoPeticion;
            $texto->save();
        }else{
            $texto = Reportes::create(['Texto' => $textoPeticion]);
        }

        return response()->json(
            compact('texto')
        );
    }

    //Función para recuperar el texto almacenado en la tabla reportes; campo Texto
    public function busquedaPlantilla(Request $request){
        //Recibe el Id del lote para recuperar el texto almacenado en el campo Texto de la tabla reportes
        $textoRecuperado = Reportes::where('Id_reporte', $request->lote)->first();
        
        return response()->json(
            compact('textoRecuperado')
        );
    }
}
