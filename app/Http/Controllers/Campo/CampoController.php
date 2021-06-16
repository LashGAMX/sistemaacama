<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudesGeneradas;
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
        // $termometros = TermometroCampo::where()
        $model = DB::table('ViewSolicitud')->where('Id_solicitud',$id)->first();
        return view('campo.captura',compact('model'));  
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
    // public function generarUpdate() 
    // {
        
    // } 
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
}
