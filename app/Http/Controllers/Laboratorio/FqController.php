<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FqController extends Controller
{
    //
    public function analisis()
    {
        $model = DB::table('proceso_analisis')->get();

        //Devuelve el tamaño del arreglo model
        $elements = DB::table('proceso_analisis')->count();

        //Para buscar el punto de muestreo 
        $puntoMuestreo = DB::table('cotizacion_puntos')->get();
        $puntoMuestreoLength = DB::table('cotizacion_puntos')->count();
        $solicitudPuntos = DB::table('solicitud_puntos')->get();
        $solicitudPuntosLength = DB::table('solicitud_puntos')->count();

        //Para select Filtro de vista análisis
        $tecnicas = DB::table('tecnicas')->get();

        //Para buscar la Norma de la solicitud
        $solicitud = DB::table('ViewSolicitud')->get();
        $solicitudLength = DB::table('ViewSolicitud')->count();

        //Para buscar los parámetros de la solicitud
        $parametros = DB::table('parametros')->get();
        $parametrosLength = DB::table('parametros')->count();

        return view('laboratorio.metales.analisis', compact('model', 'elements', 'solicitud', 'solicitudLength', 'tecnicas', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'parametrosLength', 'puntoMuestreo', 'puntoMuestreoLength'));
    }
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')->where('Id_area', 2)->get();
        return view('laboratorio.metales.observacion', compact('formulas'));
    }
}
 