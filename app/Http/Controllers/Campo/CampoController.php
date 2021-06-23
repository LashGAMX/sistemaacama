<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\ConductividadCalidad;
use App\Models\ConductividadTrazable;
use App\Models\PHCalidad;
use App\Models\PHTrazable;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudesGeneradas;
use App\Models\TermFactorCorreccionTemp;
use App\Models\TermometroCampo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    // 
    
    public function asignar()
    {
        $model = DB::table('ViewSolicitud')->where('Id_servicio',1)->orWhere('Id_servicio',3)->get();
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',NULL)->get();
        $generadas = SolicitudesGeneradas::all();
        $usuarios = Usuario::all();
        return view('campo.asignarMuestreo',compact('model','intermediarios','generadas','usuarios'));
    }
    public function listaMuestreo() 
    {
        $model = DB::table('ViewSolicitudGenerada')->where('Id_muestreador',Auth::user()->id)->get();
        return view('campo.listaMuestreo',compact('model'));
    }
    public function captura($id)
    {
        $phTrazable = PHTrazable::all();
        $phCalidad = PHCalidad::all();
        $termometros = TermometroCampo::all();
        $conTrazable = ConductividadTrazable::all();
        $conCalidad = ConductividadCalidad::all();
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();
        // $frecuencia = DB::table('frecuencia001')->where('')
        $data = array(
            'model' => $model,
            'termometros' => $termometros,
            'phTrazable' => $phTrazable,
            'phCalidad' => $phCalidad,
            'conTrazable' => $conTrazable,
            'conCalidad' => $conCalidad,
        );
        return view('campo.captura',$data);  
    }
    public function generar(Request $request) //Generar solicitud 
    {
        $generadas = SolicitudesGeneradas::create([
            'Id_solicitud' => $request->idSolicitud,
            'Folio' => $request->folio,
        ]);
        return response()->json(
            compact('generadas')
        );
    }
    public function getFolio(Request $request)
    {
        $idUser = $request->idUser;
        $inge = Usuario::where('id', $idUser)->first();
      
        $folio = $request->folioAsignar;
        $nombres = $inge->name;
        $muestreador = $inge->id; 

        $update = SolicitudesGeneradas::where('Folio', $folio)
        ->update([
            'Nombres' => $nombres,
            'Id_muestreador' => $muestreador,
        ]);

        return response()->json(
            compact('update'),
        );
    }
    public function getFactorCorreccion(Request $request)
    {
        $model = TermFactorCorreccionTemp::where('Id_termometro',$request->idFactor)->get();
        return response()->json(compact('model'));
    }
    public function getPhTrazable(Request $request)
    {
        $model = PHTrazable::where('Id_ph',$request->idPh)->first();
        return response()->json(compact('model'));
    }
    public function getPhCalidad(Request $request)
    {
        $model = PHCalidad::where('Id_ph',$request->idPh)->first();
        return response()->json(compact('model'));
    }
    public function getConTrazable(Request $request)
    {
        $model = ConductividadTrazable::where('Id_conductividad',$request->idCon)->first();
        return response()->json(compact('model'));
    }
    public function getConCalidad(Request $request)
    {
        $model = ConductividadCalidad::where('Id_conductividad',$request->idCon)->first();
        return response()->json(compact('model'));
    }
}
