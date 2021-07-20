<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoGenerales;
use App\Models\SolicitudesGeneradas;
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

        $data = array(
            'datos' => $request->solicitudesModel,
            'modelSolGen' => $modelSolGen, 
            'response' => true,
        );
        return response()->json($data);
    } 
    public function enviarDatos(Request $request)
    {
        $jsonGeneral = json_decode($request->campoGenerales,true);

        $solModel = SolicitudesGeneradas::where('Folio',$request->folio);
        $solModel->Estado = 3;
        $solModel->save();

        $solModel = SolicitudesGeneradas::where('Folio',$request->folio)->first();

        //$campoGenModel = CampoGenerales::where('Folio',$solModel->Id)


        $data = array(
            'response' => true,
            'general' => $jsonGeneral,
            'dato' => $jsonGeneral[0]["Id_solicitud"]  
        );
        return response()->json($data);
    }

}














