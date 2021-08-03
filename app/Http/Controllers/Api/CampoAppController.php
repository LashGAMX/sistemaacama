<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoGenerales;
use App\Models\ConductividadCalidad;
use App\Models\ConductividadTrazable;
use App\Models\PHCalidad;
use App\Models\PHTrazable;
use App\Models\SolicitudesGeneradas;
use App\Models\TermometroCampo;
use App\Models\UsuarioApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampoAppController extends Controller
{
    public function login(Request $request)
    {
        $idMuestreador = 0;
        $model = UsuarioApp::where('User',$request->user)
            ->where('UserPass',$request->pass) 
            ->get();
        if($model->count()){
            foreach($model as $item){
                $idMuestreador = $item->Id_muestreador;
            }
            $success = true;    
        }else{
            $success = false;
        }
        $data = array(
            'usuarios' => UsuarioApp::all(),
            'solicitudes' => DB::table('ViewSolicitudGenerada')->where('Id_muestreador', $idMuestreador)->get(),
            'response' => $success,
            'data' => $model,  
        );
        return response()->json($data); 
    } 
    public function sycnDatos(Request $request){
        $arr = $request->solicitudesModel;
        $json = json_decode($arr,true);

        $modelSolGen = DB::table('ViewSolicitudGenerada')->where('Id_muestreador', $request->idMuestreador)->where("StdSol",1)->get();
        $termometro = TermometroCampo::all();
        $phCalidad = PHCalidad::all();
        $phTrazable = PHTrazable::all(); 
        $conTrazable = ConductividadTrazable::all();
        $conCalidad = ConductividadCalidad::all();

        $data = array(
            'datos' => $request->solicitudesModel,
            'termometro' => $termometro,
            'phCalidad' => $phCalidad,
            'phTrazable' => $phTrazable,
            'conTrazable' => $conTrazable,
            'conCalidad' => $conCalidad,
            'modelSolGen' => $modelSolGen, 
            'response' => true,
        );
        return response()->json($data);
    } 
    public function enviarDatos(Request $request)
    {
        $jsonGeneral = json_decode($request->campoGenerales,true);

        $solModel = SolicitudesGeneradas::where('Folio',$request->folio)->first();
        $solModel->Estado = 3;
        $solModel->save();

        //$solModel = SolicitudesGeneradas::where('Folio',$request->folio)->first();

        $campoGenModel = CampoGenerales::where('Id_solicitud',$solModel->Id_solicitud)->first();
        $campoGenModel->Captura = "Mobil";
        $campoGenModel->Id_equipo = $jsonGeneral[0]["Id_equipo"];
        $campoGenModel->Temperatura_a = $jsonGeneral[0]["Temperatura_a"];
        $campoGenModel->Temperatura_b = $jsonGeneral[0]["Temperatura_b"];
        $campoGenModel->Latitud = $jsonGeneral[0]["Latitud"];
        $campoGenModel->Longitud = $jsonGeneral[0]["Longitud"];
        $campoGenModel->Altitud = $jsonGeneral[0]["Altitud"];
        $campoGenModel->Pendiente = $jsonGeneral[0]["Pendiente"];
        $campoGenModel->Criterio = $jsonGeneral[0]["Criterio"];
        $campoGenModel->save();


        $data = array(
            'response' => true,
            'general' => $jsonGeneral,
            'dato' => $jsonGeneral[0]["Temperatura_a"],
        );
        return response()->json($data);
    }

}














