<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\ConductividadMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\SolicitudPuntos;
use App\Models\UsuarioApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class RecepcionAppController extends Controller
{
    public function login(Request $res){
        $data = array(
            "estado" => "error",
            "mensaje" => "Usuario o contraseña incorrectos",
            "idUsuario" => 0,
            "avatar" => "",
        );

        $modelUsuarioApp = UsuarioApp::where('User', '=', $res->usuario)
        ->where('UserPass', '=', $res->contrasena)
        ->get();

        if(count($modelUsuarioApp)){
            $modelUser = DB::table('users')
            ->where('id', '=', $modelUsuarioApp[0]->Id_muestreador)
            ->first();

            $data["estado"] = "exito";
            $data["mensaje"] = "Sesion iniciada correctamente";
            $data["idUsuario"] = $modelUsuarioApp[0]->Id_muestreador;
            if($modelUser->avatar){
                $data["avatar"] = $modelUser->avatar;
            }
        }
        return response()->json($data);
    }

    public function getUser(Request $res){
        $data = array(
            "user" => "",
            "nombre" => "",
            "iniciales" => "",
        );

        $modelUser = DB::table('users')
        ->where('id', '=', $res->idUser)
        ->first();

        $modelUsuarioApp = UsuarioApp::where('Id_muestreador', '=', $modelUser->id)
        ->get();

        if(count($modelUsuarioApp)){
            $data["user"] = $modelUsuarioApp[0]->User;
            $data["nombre"] = $modelUser->name;
            if($modelUser->iniciales){
                $data["iniciales"] = $modelUser->iniciales;
            }
        }

        return response()->json($data);
    }

    public function getInformacionFolioAgua(Request $res){
        $data = array(
            "mensaje" => "error",
            "folio" => 'null',
            "descarga" => null,
            "cliente" => null,
            "empresa" => null,
            "horaRecepcion" => null,
            "horaEntrada" => null,
            "fechaMuestreo" => null,
            "fechaConformacion" => null,
            "procedencia" => null,
            "puntosMuestreo" => null
        );

        $modelProcesoAnalisis = DB::table('proceso_analisis')
        ->where('Folio', '=', $res->folio)
        ->first();

        if(!empty($modelProcesoAnalisis)){
            $puntosMuestreo = array();
            $modelHijosFolio = SolicitudPuntos::where('Id_solPadre', '=', $modelProcesoAnalisis->Id_solicitud)->get();
            foreach($modelHijosFolio as $hijo){
                $punto = array(
                    "idFolio" => $hijo->Id_solicitud,
                    "punto" => $hijo->Punto,
                    "conductividad" => null,
                    "cloruros" => null
                );
                $conductividadModel = ConductividadMuestra::where('Id_solicitud', $hijo->Id_solicitud)->where('Activo', '=', 1)->get();
                if($conductividadModel->count()){
                    $contador = 0;
                    $promedio = 0;
                    foreach($conductividadModel as $conductividad){
                        $promedio = $promedio + $conductividad->Promedio;
                        $contador++;
                    }
                    $promedio = $promedio / $contador;
                    $punto["conductividad"] = intval(round($promedio));
                }
                $campoCompuestoModel = CampoCompuesto::where('Id_solicitud', '=', $hijo->Id_solicitud)->first();
                if(!empty($campoCompuestoModel)){
                    $punto["cloruros"] = intval($campoCompuestoModel->Cloruros);
                }
                array_push($puntosMuestreo, $punto);
            }
            
            $data["mensaje"] = "exito";
            $data["folio"] = $modelProcesoAnalisis->Folio;
            $data["descarga"] = $modelProcesoAnalisis->Descarga;
            $data["cliente"] = $modelProcesoAnalisis->Cliente;
            $data["empresa"] = $modelProcesoAnalisis->Empresa;
            $data["horaRecepcion"] = $modelProcesoAnalisis->Hora_recepcion;
            $data["horaEntrada"] = $modelProcesoAnalisis->Hora_entrada;
            $data["puntosMuestreo"] = $puntosMuestreo;
        }

        return response()->json($data);
    }

    public function upHoraRecepcion(Request $res){
        //1 Recepción - 2 Entrada
        $data = array(
            "estado" => "error",
            "mensaje" => "la hora no pudo ser cambiada",
        );
        $modelProcesoAnalisis = ProcesoAnalisis::where('Folio', 'LIKE', '%' . $res->folio . '%')->get();
        if(!empty($modelProcesoAnalisis)){
            if($res->tipoHora == 1){
                foreach($modelProcesoAnalisis as $folios){
                    $folios->Hora_recepcion = $res->hora;
                    $folios->save();
                }
            }
            else{
                foreach($modelProcesoAnalisis as $folios){
                    $folios->Hora_entrada = $res->hora;
                    $folios->save();
                }
            }
            $data["estado"] = "exito";
            $data["mensaje"] = "la hora fue cambiada";
        }
        return response()->json($data);
    }
}