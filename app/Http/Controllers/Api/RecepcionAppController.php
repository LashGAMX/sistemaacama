<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        );

        $modelProcesoAnalisis = DB::table('proceso_analisis')
        ->where('Folio', '=', $res->folio)
        ->first();

        if(!empty($modelProcesoAnalisis)){
            $data["mensaje"] = "exito";
            $data["folio"] = $modelProcesoAnalisis->Folio;
            $data["descarga"] = $modelProcesoAnalisis->Descarga;
            $data["cliente"] = $modelProcesoAnalisis->Cliente;
            $data["empresa"] = $modelProcesoAnalisis->Empresa;
            $data["horaRecepcion"] = $modelProcesoAnalisis->Hora_recepcion;
            $data["horaEntrada"] = $modelProcesoAnalisis->Hora_entrada;
        }

        return response()->json($data);
    }
}