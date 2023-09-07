<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CampoConCalidad;
use App\Models\CampoConTrazable;
use App\Models\CampoGenerales;
use App\Models\CampoPhCalidad;
use App\Models\CampoPhTrazable;
use App\Models\ConductividadCalidad;
use App\Models\ConductividadTrazable;
use App\Models\ConTratamiento;
use App\Models\TipoTratamiento;
use App\Models\Evidencia;
use App\Models\PHCalidad;
use App\Models\PhCalidadCampo;
use App\Models\PHTrazable;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudPuntos;
use App\Models\TermometroCampo;
use App\Models\UsuarioApp;
use App\Models\CampoCompuestos;
use App\Models\Color;
use Carbon\Carbon;
use App\Models\ConductividadMuestra;
use App\Models\GastoMuestra;
use App\Models\MetodoAforo;
use App\Models\PhMuestra;
use App\Models\TemperaturaAmbiente;
use App\Models\TemperaturaMuestra;
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

        $modelSolGen = DB::table('ViewSolicitudGenerada')->where('Id_muestreador', $request->idMuestreador)->where("StdSol",1)->orderBy('Id_solicitud','DESC')->get();
       // $termometro = TermometroCampo::all();
        $pc100 = TermometroCampo::where('Tipo', 2)->get();
        $hanna =  TermometroCampo::where('Tipo', 1)->get();
        $phCalidad = PHCalidad::all();
        $phTrazable = PHTrazable::all();
        $conTrazable = ConductividadTrazable::all();
        $conCalidad = ConductividadCalidad::all(); 
        //catalogos de muestra simple
        $color = Color::all();
        //catalogo datos compuestos
        $aforo = MetodoAforo::all();
        $conTratamiento = ConTratamiento::all();
        $tipo = TipoTratamiento::all();      

        $data = array( 
            'datos' => $request->solicitudesModel,
            'modelSolGen' => $modelSolGen, 
            'pc100' => $pc100,
            'hanna' => $hanna,
            'modelSolGen' => $modelSolGen, 
            'phCalidad' => $phCalidad,
            'phTrazable' => $phTrazable,
            'conTrazable' => $conTrazable,  
            'conCalidad' => $conCalidad, 
            'modelColor' => $color,
            'modelConTratamiento' => $conTratamiento,
            'modelAforo' => $aforo,
            'modelTipo' => $tipo,
            'response' => true,
        );
        return response()->json($data);
    } 
    public function version(request $request) {
        $version = "1.4.2";

        $data = array(
            'version' => $version,
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
        $jsonConTra = json_decode($request->conTrazable,true) ;
        $jsonConCal = json_decode($request->conCalidad,true);
        $jsonPhMuestra = json_decode($request->phMuestra,true);
        $jsonTempMuestra = json_decode($request->tempMuestra,true);
        $jsonTempAmbiente = json_decode($request->tempAmbiente,true);
        $jsonConMuestra = json_decode($request->conMuestra,true);
        $jsonGastoMuestra = json_decode($request->gastoMuestra,true);
        $jsonphCalidadMuestra = json_decode($request->phCalidadMuestra,true);
        $jsonDatosCompuestos = json_decode($request->campoCompuesto,true);
        $jsonEviencia = json_decode($request->evidencia,true);
        $jsonPunto = json_decode($request->solPunto,true);
        $jsonCanceladas = json_decode($request->canceladas,true);

        $solModel = SolicitudesGeneradas::where('Folio',$request->folio)->first();
        $solModel->Estado = 3;
        $solModel->save();
        $puntoModel = SolicitudPuntos::where('Id_solicitud',$solModel->Id_solicitud)->first();

        //obtener id de termometros a
        $termo1 = $jsonGeneral[0]["Id_equipo"]; 
        $idTermo1 = explode("/", $termo1);
        $termo2 = $jsonGeneral[0]["Id_equipo2"]; 
        $idTermo2 = explode("/", $termo2);
        //CAMPO GENERAL
        $campoGenModel = CampoGenerales::where('Id_solicitud',$solModel->Id_solicitud)->first();
        $campoGenModel->Captura = "App";
        $campoGenModel->Id_equipo = $idTermo1[0]; 
        $campoGenModel->Id_equipo2 = $idTermo2[0]; 
        $campoGenModel->Temperatura_a = $jsonGeneral[0]["Temperatura_a"];
        $campoGenModel->Temperatura_b = $jsonGeneral[0]["Temperatura_b"];
        $campoGenModel->Latitud = $jsonGeneral[0]["Latitud"];
        $campoGenModel->Longitud = $jsonGeneral[0]["Longitud"];
        $campoGenModel->Pendiente = $jsonGeneral[0]["Pendiente"];
        $campoGenModel->Criterio = $jsonGeneral[0]["Criterio"];
        $campoGenModel->Supervisor = $jsonGeneral[0]["Supervisor"];
        $campoGenModel->save();
      


        $catPhTra = PHTrazable::where('Ph',$jsonPhTra[0]["Id_phTrazable"])->first();
        $phTrazable = CampoPhTrazable::where('Id_solicitud',$solModel->Id_solicitud)->get();
            //$phTrazable[0]->Id_solicitud = $solModel->Id_solicitud;
            $phTrazable[0]->Id_phTrazable = $catPhTra->Id_ph;
            $phTrazable[0]->Lectura1 = $jsonPhTra[0]["Lectura1"];
            $phTrazable[0]->Lectura2 = $jsonPhTra[0]["Lectura2"];
            $phTrazable[0]->Lectura3 =$jsonPhTra[0]["Lectura3"];
            $phTrazable[0]->Estado = $jsonPhTra[0]["Estado"];
            $phTrazable[0]->save();
       
        $catPhTra = PHTrazable::where('Ph',$jsonPhTra[1]["Id_phTrazable"])->first();
            //$phTrazable[1]->Id_solicitud = $solModel->Id_solicitud;
            $phTrazable[1]->Id_phTrazable = $catPhTra->Id_ph;
            $phTrazable[1]->Lectura1 = $jsonPhTra[1]["Lectura1"];
            $phTrazable[1]->Lectura2 = $jsonPhTra[1]["Lectura2"];
            $phTrazable[1]->Lectura3 =$jsonPhTra[1]["Lectura3"];
            $phTrazable[1]->Estado = $jsonPhTra[1]["Estado"];
            $phTrazable[1]->save();

        $catPhCal = PHCalidad::where('Ph_calidad',$jsonPhCal[0]["Id_phCalidad"])->first();
        $phCalidad = CampoPhCalidad::where('Id_solicitud',$solModel->Id_solicitud)->get();
        
            //$phCalidad->Id_solicitud[0] = $solModel->Id_solicitud;
            //$phCalidad->Id_phCalidad[0] = $catPhCal->Id_ph;
            $phCalidad[0]->Lectura1 = $jsonPhCal[0]["Lectura1"];
            $phCalidad[0]->Lectura2 = $jsonPhCal[0]["Lectura2"];
            $phCalidad[0]->Lectura3 = $jsonPhCal[0]["Lectura3"];
            $phCalidad[0]->Estado = $jsonPhCal[0]["Estado"];
            $phCalidad[0]->Promedio = $jsonPhCal[0]["Promedio"];
            $phCalidad[0]->save();


        $catPhCal = PHCalidad::where('Ph_calidad',$jsonPhCal[1]["Id_phCalidad"])->first();
            //$phCalidad->Id_solicitud[1] = $solModel->Id_solicitud;
            //$phCalidad->Id_phCalidad[1] = $catPhCal->Id_ph;
            $phCalidad[1]->Lectura1 = $jsonPhCal[1]["Lectura1"];
            $phCalidad[1]->Lectura2 = $jsonPhCal[1]["Lectura2"];
            $phCalidad[1]->Lectura3 = $jsonPhCal[1]["Lectura3"];
            $phCalidad[1]->Estado = $jsonPhCal[1]["Estado"];
            $phCalidad[1]->Promedio = $jsonPhCal[1]["Promedio"];
            $phCalidad[1]->save();

        $catConTra = ConductividadTrazable::where('Conductividad',$jsonConTra[0]["Id_conTrazable"])->first();
        $conTrazable = CampoConTrazable::where('Id_solicitud',$solModel->Id_solicitud)->first();
        
           // $conTrazable->Id_solicitud = $solModel->Id_solicitud;
            $conTrazable->Id_conTrazable = $catConTra->Id_conductividad;
            $conTrazable->Lectura1 = $jsonConTra[0]["Lectura1"];
            $conTrazable->Lectura2 = $jsonConTra[0]["Lectura2"];
            $conTrazable->Lectura3 = $jsonConTra[0]["Lectura3"];
            $conTrazable->Estado = $jsonConTra[0]["Estado"];
            $conTrazable->save();
        
        $catConCal = ConductividadCalidad::where('Conductividad',$jsonConCal[0]["Id_conCalidad"])->first();
        $conCalidad = CampoConCalidad::where('Id_solicitud',$solModel->Id_solicitud)->first();
        
            //$conCalidad->Id_solicitud = $solModel->Id_solicitud;
            $conCalidad->Id_conCalidad = $catConCal->Id_conductividad;
            $conCalidad->Lectura1 = $jsonConCal[0]["Lectura1"];
            $conCalidad->Lectura2 = $jsonConCal[0]["Lectura2"];
            $conCalidad->Lectura3 = $jsonConCal[0]["Lectura3"];
            $conCalidad->Estado = $jsonConCal[0]["Estado"];
            $conCalidad->Promedio = $jsonConCal[0]["Promedio"];
            $conCalidad->save();


        //MUESTRA
        
       // phMuestra
         $phMuestra = PhMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
                $m = "";
                $d = "";
                $finalDate = "";
         if($phMuestra->count())
         {
            for ($i = 0; $i < $phMuestra->count(); $i++) {
                $materia  = $jsonPhMuestra[$i]["Materia"];
                $olor = $jsonPhMuestra[$i]["Olor"];
                $color = $jsonPhMuestra[$i]["Color"];
                $ph1 = $jsonPhMuestra[$i]["Ph1"];
                $ph2 = $jsonPhMuestra[$i]["Ph2"];
                $ph3 = $jsonPhMuestra[$i]["Ph3"];
                $promedio = $jsonPhMuestra[$i]["Promedio"];
                $fecha = $jsonPhMuestra[$i]["Fecha"];
             
                //hora 
                
                $datetemp =  $jsonPhMuestra[$i]["Fecha"];
                $hour = $jsonPhMuestra[$i]["Hora"];
                $dateExplode = explode("-",$datetemp);
                $year = $dateExplode[0];
                $mes = $dateExplode[1];
                $dia = $dateExplode[2];
                $srtm=  strlen($mes);
                $srtd=  strlen($dia);
                if ($srtm == 1){
                    $m = "0" . $mes;
                } else {
                    $m = $mes;
                }
                if ($srtd == 1){
                    $d = "0" . $dia;
                }
                else {
                    $d = $dia;
                }
                $finalDate = $year."-".$m."-".$d."T".$hour;
              

                $phMuestra[$i]->Materia =$materia;
                $phMuestra[$i]->Olor = $olor;
                $phMuestra[$i]->Color = $color;
                $phMuestra[$i]->Ph1 = $ph1;
                $phMuestra[$i]->Ph2 = floatval($ph2);
                $phMuestra[$i]->Ph3 = floatval($ph3);
                $phMuestra[$i]->Promedio = floatval($promedio);
                $phMuestra[$i]->Fecha = $finalDate;
                $phMuestra[$i]->save();
             }
         }
           
      
        $tempAmbiente= TemperaturaAmbiente::where('Id_solicitud', $solModel->Id_solicitud)->get();
        
        for ($i = 0; $i < $tempAmbiente->count(); $i++){
            $tempA1 = $jsonTempAmbiente[$i]["TempA1"];
            $tempA2 = $jsonTempAmbiente[$i]["TempA2"];
            $tempA3 = $jsonTempAmbiente[$i]["TempA3"];
            $promedioA = $jsonTempAmbiente[$i]["PromedioA"];
            
            $tempAmbiente[$i]->TemperaturaSin1 = floatval($tempA1);
            $tempAmbiente[$i]->TemperaturaSin2 = floatval($tempA2);
            $tempAmbiente[$i]->TemperaturaSin3 = floatval($tempA3);
            $tempAmbiente[$i]->Promedio = floatval($promedioA);
            $tempAmbiente[$i]->save();
        }

        $condictuividad = ConductividadMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();

        for ($i = 0; $i < $condictuividad->count(); $i++){
            $temp1 = $jsonConMuestra[$i]["Conductividad1"];
            $temp2 = $jsonConMuestra[$i]["Conductividad2"];
            $temp3 = $jsonConMuestra[$i]["Conductividad3"];
            $temp4 = $jsonConMuestra[$i]["Promedio"];
            if ($temp1 == 0) { 
            } else {
                $condictuividad[$i]->Conductividad1 = floatval($temp1);
            }
            if ($temp2 == 0) { 
            } else {
                $condictuividad[$i]->Conductividad2 = floatval($temp2);
            }
            if ($temp3 == 0) { 
            } else {
                $condictuividad[$i]->Conductividad3 = floatval($temp3);
            }
            //$condictuividad[$i]->Conductividad1 = floatval($temp1);
            //$condictuividad[$i]->Conductividad2 = floatval($temp2);
            //$condictuividad[$i]->Conductividad3 = floatval($temp3);
            if ($temp4 == 0) { 
            } else {
                $condictuividad[$i]->Promedio = floatval($temp4);
            }
            //$condictuividad[$i]->Promedio = floatval($temp4);
            $condictuividad[$i]->save();
        }

        $gasto = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();

        for ($i = 0; $i < $gasto->count(); $i++){
            $temp1 = $jsonGastoMuestra[$i]["Gasto1"];
            $temp2 = $jsonGastoMuestra[$i]["Gasto2"];
            $temp3 = $jsonGastoMuestra[$i]["Gasto3"];
            $temp4 = $jsonGastoMuestra[$i]["Promedio"];
            $gastoProm = round($temp4,2);
            $gasto[$i]->Gasto1 = floatval($temp1);
            $gasto[$i]->Gasto2 = floatval($temp2);
            $gasto[$i]->Gasto3 = floatval($temp3);
            $gasto[$i]->Promedio = floatval($gastoProm);
            $gasto[$i]->save();
        }
        
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        
        for ($i = 0; $i < $tempMuestra->count(); $i++){
            $temp1 = $jsonTempMuestra[$i]["Temp1"];
            $temp2 = $jsonTempMuestra[$i]["Temp2"];
            $temp3 = $jsonTempMuestra[$i]["Temp3"];
            $temp4 = $jsonTempMuestra[$i]["Promedio"];
           
            $tempMuestra[$i]->TemperaturaSin1 = floatval($temp1);
            $tempMuestra[$i]->TemperaturaSin2 = floatval($temp2);
            $tempMuestra[$i]->TemperaturaSin3 = floatval($temp3);
            $tempMuestra[$i]->Promedio = floatval($temp4);
            $tempMuestra[$i]->save();
        }

       $phCalidadMuestra = PhCalidadCampo::where('Id_solicitud', $solModel->Id_solicitud)->get();
        
        for ($i = 0; $i < $phCalidadMuestra->count(); $i++){
            $lectura1 = $jsonphCalidadMuestra[$i]["Lectura1C"];
            $lectura2 = $jsonphCalidadMuestra[$i]["Lectura2C"];
            $lectura3 = $jsonphCalidadMuestra[$i]["Lectura3C"];
            $promedio = $jsonphCalidadMuestra[$i]["PromedioC"];
            $phCalidadMuestra[$i]->Ph_calidad = 7;
            $phCalidadMuestra[$i]->Lectura1 = floatval($lectura1);
            $phCalidadMuestra[$i]->Lectura2 = floatval($lectura2);
            $phCalidadMuestra[$i]->Lectura3 = floatval($lectura3);
            $phCalidadMuestra[$i]->Promedio = floatval($promedio);
            $phCalidadMuestra[$i]->save();
        }
      // DATOS COMPUESTOS -------------------------------------------------------------------
        $aforo = $jsonDatosCompuestos[0]["Metodo_aforo"];
        $aforoFinal = MetodoAforo::where("Aforo" , $aforo)->first();
        $conTratamiento = $jsonDatosCompuestos[0]["Con_tratamiento"];
        $contratamiento = ConTratamiento::where('Tratamiento', $conTratamiento)->first();
        $tipoTratamiento = $jsonDatosCompuestos[0]["Tipo_tratamiento"];
        $tipoTratamientoFinal = TipoTratamiento::where('Tratamiento', $tipoTratamiento)->first();
        $phMuestraComp = $jsonDatosCompuestos[0]["Ph_muestraComp"];
        
        $campoCompuesto = CampoCompuesto::where('Id_solicitud',$solModel->Id_solicitud)->first();
        $campoCompuesto->Metodo_aforo = $aforoFinal->Id_aforo;
        $campoCompuesto->Con_tratamiento = $contratamiento->Id_tratamiento;
        $campoCompuesto->Tipo_tratamiento = $tipoTratamientoFinal->Id_tratamiento;
        $campoCompuesto->Proce_muestreo = $jsonDatosCompuestos[0]["Proc_muestreo"];
        $campoCompuesto->Observaciones = $jsonDatosCompuestos[0]["Observaciones"];
        $campoCompuesto->Obser_solicitud = $jsonDatosCompuestos[0]["Obser_solicitud"];
        $campoCompuesto->Ph_muestraComp = $phMuestraComp;
        $campoCompuesto->Temp_muestraComp = $jsonDatosCompuestos[0]["Temp_muestraComp"];
        $campoCompuesto->Volumen_calculado = $jsonDatosCompuestos[0]["Volumen_calculado"];
        $explode =  explode(" ", $jsonDatosCompuestos[0]["Cloruros"]);
        $clorurosJson = $jsonDatosCompuestos[0]["Cloruros"];
        if ($explode[0] == "<"){
            $cloruros = 499;
        } else if ($explode[0] == ">"){
            $cloruros = 1500;
        }
        else {
            $cloruros = $explode[1];
        }
        $campoCompuesto->Cloruros = $cloruros;
        $campoCompuesto->save();
       // -------------------------EVIDENCIA---------------------------------------

        // for ($i=0; $i < sizeof($jsonEviencia); $i++) { 
        //     # code...
        //     Evidencia::create([
        //         'Id_solicitud' => $solModel->Id_solicitud,
        //         'Id_punto' => $puntoModel->Id_muestreo,
        //         'Base64' => $request->pruebaCod,
        //     ]);
        // }
        // Evidencia::create([
        //     'Id_solicitud' => $solModel->Id_solicitud,
        //     'Id_punto' => $puntoModel->Id_muestreo,
        //     'Base64' => $jsonEviencia[0]["Codigo"],
        // ]);
        //        // $campoGenModel->Id_equipo = $jsonGeneral[0]["Id_equipo"]; 

        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = PhMuestra::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
            
        }
        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = TemperaturaAmbiente::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
        }
        
        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = ConductividadMuestra::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
        }
        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = GastoMuestra::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
        }
        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = TemperaturaMuestra::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
        }
        for ($i=0; $i < sizeof($jsonCanceladas); $i++) { 
            $ph = PhCalidadCampo::where('Id_solicitud',$solModel->Id_solicitud)->where('Num_toma',$jsonCanceladas[$i]["Muestra"])->first();
            $ph->Activo = 0;
            $ph->save();
        }
    
       

        $data = array(
            'response' => true,
             'solModel' => $solModel->Id_solicitud,
             'punto' => $puntoModel->Id_muestreo,
             'aforo' => $tipoTratamientoFinal,
             
          
        );
        return response()->json($data);
    }

}














    