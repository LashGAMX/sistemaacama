<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoConCalidad;
use App\Models\CampoConTrazable;
use App\Models\CampoGenerales;
use App\Models\CampoPhCalidad;
use App\Models\CampoPhTrazable;
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
    public function prueba(Request $request)
    {
        $catPhTra = PHTrazable::where('Ph',$request->id)->first();
        $data = array('model' => $catPhTra);
        return response()->json($data);
    }
    public function enviarDatos(Request $request) 
    {
        $jsonGeneral = json_decode($request->campoGenerales,true);
        $jsonPhTra = json_decode($request->phTrazable,true);
        $jsonPhCal = json_decode($request->phCalidad,true);
        $jsonConTra = json_decode($request->conTrazable,true);
        $jsonConCal = json_decode($request->conCalidad,true);

        $solModel = SolicitudesGeneradas::where('Folio',$request->folio)->first();
        $solModel->Estado = 3;
        $solModel->save();

        $campoGenModel = CampoGenerales::where('Id_solicitud',$solModel->Id_solicitud)->first();
        $campoGenModel->Captura = "Mobil";
        $campoGenModel->Id_equipo = $jsonGeneral[0]["Id_equipo"]; 
        $campoGenModel->Temperatura_a = $jsonGeneral[0]["Temperatura_a"];
        $campoGenModel->Temperatura_b = $jsonGeneral[0]["Temperatura_b"];
        $campoGenModel->Latitud = $jsonGeneral[0]["Latitud"];
        $campoGenModel->Longitud = $jsonGeneral[0]["Longitud"];
        $campoGenModel->Pendiente = $jsonGeneral[0]["Pendiente"];
        $campoGenModel->Criterio = $jsonGeneral[0]["Criterio"];
        $campoGenModel->save();

        $catPhTra = PHTrazable::where('Ph',$jsonPhTra[0]["Id_phTrazable"])->first();
        CampoPhTrazable::create([
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_phTrazable' => $catPhTra->Id_ph,
            'Lectura1' => $jsonPhTra[0]["Lectura1"],
            'Lectura2' => $jsonPhTra[0]["Lectura2"],
            'Lectura3' => $jsonPhTra[0]["Lectura3"],
            'Estado' => $jsonPhTra[0]["Estado"]
        ]);
        $catPhTra = PHTrazable::where('Ph',$jsonPhTra[1]["Id_phTrazable"])->first();
        CampoPhTrazable::create([
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_phTrazable' => $catPhTra->Id_ph,
            'Lectura1' => $jsonPhTra[1]["Lectura1"],
            'Lectura2' => $jsonPhTra[1]["Lectura2"],
            'Lectura3' => $jsonPhTra[1]["Lectura3"],
            'Estado' => $jsonPhTra[1]["Estado"]
        ]);

        $catPhCal = PHCalidad::where('Ph_calidad',$jsonPhCal[0]["Id_phCalidad"])->first();
        CampoPhCalidad::create([
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_phCalidad' => $catPhCal->Id_ph,
            'Lectura1' => $jsonPhCal[0]["Lectura1"],
            'Lectura2' => $jsonPhCal[0]["Lectura2"],
            'Lectura3' => $jsonPhCal[0]["Lectura3"],
            'Estado' => $jsonPhCal[0]["Estado"],
            'Promedio' => $jsonPhCal[0]["Promedio"]
        ]);
        $catPhCal = PHCalidad::where('Ph_calidad',$jsonPhCal[1]["Id_phCalidad"])->first();
        CampoPhCalidad::create([
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_phCalidad' => $catPhCal->Id_ph,
            'Lectura1' => $jsonPhCal[1]["Lectura1"],
            'Lectura2' => $jsonPhCal[1]["Lectura2"],
            'Lectura3' => $jsonPhCal[1]["Lectura3"],
            'Estado' => $jsonPhCal[1]["Estado"],
            'Promedio' => $jsonPhCal[1]["Promedio"]
        ]);

        $catConTra = ConductividadTrazable::where('Conductividad',$jsonConTra[0]["Id_conTrazable"])->first();
        CampoConTrazable::create([
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_conTrazable' => $catConTra->Id_conductividad,
            'Lectura1' => $jsonConTra[0]["Lectura1"],
            'Lectura2' => $jsonConTra[0]["Lectura2"],
            'Lectura3' => $jsonConTra[0]["Lectura3"],
            'Estado' => $jsonConTra[0]["Estado"]
        ]);
        $catConCal = ConductividadCalidad::where('Conductividad',$jsonConCal[0]["Id_conCalidad"])->first();
        CampoConCalidad::create([ 
            'Id_solicitud' => $solModel->Id_solicitud,
            'Id_conCalidad' => $catConCal->Id_conductividad,
            'Lectura1' => $jsonConCal[0]["Lectura1"],
            'Lectura2' => $jsonConCal[0]["Lectura2"], 
            'Lectura3' => $jsonConCal[0]["Lectura3"],
            'Estado' => $jsonConCal[0]["Estado"],
            'Promedio' => $jsonConCal[0]["Promedio"]
        ]);



        $data = array(
            'response' => true,
            'catPhTra' => $catPhTra,
            'JsonPhTra' => $jsonPhTra,
            'solModel' => $solModel,
            'idPhTra' => $jsonPhTra[0]["Id_phTrazable"],
        );
        return response()->json($data);
    }

}














