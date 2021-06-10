<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudesGeneradas;
use App\Models\Usuario;

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
        return view('campo.listaMuestreo');
    }
    public function captura()
    {
        return view('campo.captura');
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
        $inge = Usuario::where('id', $request->idUser)->all();
        // $update = DB::table('solicitudes_generadas')
        // ->where('Folio', $request->folioAsignar)
        // ->update([
        //     'Id_muestreador' => $inge->id,
        //     'Nombres' => $inge->name,
        // ]);
        $update = SolicitudesGeneradas::find('Folio', $request->folioAsignar);
        $update->Nombres = $request->name;
        $update->Id_muestreador = $request->id;
        $update->save();
        return response()->json(
            compact('update'),
        );
    }
}
