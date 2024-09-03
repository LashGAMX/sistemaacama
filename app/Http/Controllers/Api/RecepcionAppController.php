<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\FotoRecepcion;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SucursalCliente;
use App\Models\UsuarioApp;
use Carbon\Carbon;
use DateTime;
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
            "idSolicitud" => null,
            "folio" => null,
            "muestraIngresada" => false,
            "codigosGenerados" => false,
            "siralab" => false,
            "descarga" => null,
            "cliente" => null,
            "empresa" => null,
            "fechaMuestreo" => null,
            "horaRecepcion" => null,
            "horaEntrada" => null,
            "fechaFinMuestreo" => null,
            "fechaConformacion" => null,
            "procedencia" => null,
            "puntosMuestreo" => null,
            "idNorma" => null,
            "fechaEmision" => null,
            "historial" => false,
        );

        $modelSolicitud = DB::table('viewsolicitud2')
        ->where('Folio_servicio', '=', $res->folio)
        ->first();

        $modelProcesoAnalisis = DB::table('proceso_analisis')
        ->where('Folio', '=', $res->folio)
        ->first();

        if(!empty($modelSolicitud)){
            $data["mensaje"] = "exito";
            $data["idSolicitud"] = $modelSolicitud->Id_solicitud;
            $data["folio"] = $modelSolicitud->Folio_servicio;
            $data["descarga"] = $modelSolicitud->Descarga;
            $data["cliente"] = $modelSolicitud->Nombres . ' ' . $modelSolicitud->A_paterno;
            $data["empresa"] = $modelSolicitud->Empresa;
            $data["fechaMuestreo"] = $modelSolicitud->Fecha_muestreo;
            $data["idNorma"] = $modelSolicitud->Id_norma;
            if($modelSolicitud->Siralab == 1){
                $data["siralab"] = true;
            }

            $puntosMuestreo = array();
            $modelHijosFolio = SolicitudPuntos::where('Id_solPadre', '=', $modelSolicitud->Id_solicitud)->get();
            foreach($modelHijosFolio as $hijo){
                $punto = array(
                    "idFolio" => $hijo->Id_solicitud,
                    "punto" => $hijo->Punto,
                    "conductividad" => null,
                    "cloruros" => null
                );
                if(empty($modelProcesoAnalisis)){
                    if(empty($hijo->Conductividad)){
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
                    }
                    else{
                        $punto["conductividad"] = intval(round($hijo->Conductividad));
                    }
                    if(empty($hijo->Cloruros)){
                        $campoCompuestoModel = CampoCompuesto::where('Id_solicitud', '=', $hijo->Id_solicitud)->first();
                        if(!empty($campoCompuestoModel)){
                            $punto["cloruros"] = intval($campoCompuestoModel->Cloruros);
                        }
                    }
                    else{
                        $punto["cloruros"] = intval($hijo->Cloruros);
                    }
                }
                else{
                    if($hijo->Conductividad != NULL){
                        $punto["conductividad"] = intval($hijo->Conductividad);
                    }
                    if($hijo->Cloruros != NULL){
                        $punto["cloruros"] = intval($hijo->Cloruros);
                    }
                }
                array_push($puntosMuestreo, $punto);
            }
            
            $data["puntosMuestreo"] = $puntosMuestreo;

            if(!empty($modelProcesoAnalisis)){
                $data["muestraIngresada"] = true;
                $data["horaRecepcion"] = $modelProcesoAnalisis->Hora_recepcion;
                $data["horaEntrada"] = $modelProcesoAnalisis->Hora_entrada;
                $data["fechaEmision"] = $modelProcesoAnalisis->Emision_informe;
                if($modelProcesoAnalisis->Historial_resultado == 1){
                    $data["historial"] = true;
                }
            }

            $modelCodigoParametro = CodigoParametros::where('Codigo', 'LIKE', '%' . $res->folio . '%')->get();
            if(count($modelCodigoParametro) > 0){
                $data["codigosGenerados"] = true;
            }
            // if(!empty($modelCodigoParametro)){
            //     $data["codigosGenerados"] = true;
            // }
        }

        return response()->json($data);
    }

    public function getParametros(Request $res){
        $data = array("parametros" => null);

        $modelCodigoRecepcion = DB::table('viewcodigorecepcion')
        ->where('Codigo', 'LIKE', '%' . $res->folio  . '%')
        ->where('deleted_at', '=', NULL)
        ->select('Codigo', 'Parametro')
        ->get();
        if(!empty($modelCodigoRecepcion)){
            $data["parametros"] = $modelCodigoRecepcion;

            return response()->json($data);
        }
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

    public function setImagenPunto(Request $res){
        $data = array(
            "estado" => "error",
            "mensaje" => "la imagen no se subió correctamente",
        );
        FotoRecepcion::create([
            "Id_solicitud" => $res->idSolicitud,
            "Foto" => $res->foto,
        ]);
        $data["estado"] = "exito";
        $data["mensaje"] = "imagen guardada con éxito";

        return response()->json($data);
    }

    public function getImagenesPunto(Request $res){
        $modelFotoRecepcion = FotoRecepcion::where('Id_solicitud', '=', $res->idSolicitud)
        ->select('Id_foto_recepcion', 'Id_solicitud', 'Foto')
        ->get();
        $data = array("model" => $modelFotoRecepcion);

        return response()->json($data);
    }

    public function getDatosPunto(Request $res){
        $data = array(
            "fechaFinMuestreo" => null,
            "fechaConformacion" => null,
            "procedencia" => null,
        );

        $modelSolicitud = Solicitud::where('Id_solicitud', '=', $res->idSolicitud)->first();
        $modelPhMuestra = PhMuestra::where('Id_solicitud', '=', $res->idSolicitud)->orderBy('Id_ph', 'DESC')->first();

        if(!empty($modelSolicitud) && $modelSolicitud->Id_sucursal != NULL){
            $modelSucursalCliente = SucursalCliente::where('Id_sucursal', '=', $modelSolicitud->Id_sucursal)->first();
        }
        
        if(!empty($modelPhMuestra)){
            $data["fechaFinMuestreo"] = $modelPhMuestra->Fecha;
            if($data["fechaFinMuestreo"] != NULL){
                $fechaAuxiliar = new \Carbon\Carbon($data["fechaFinMuestreo"]);
                $data["fechaConformacion"] = $fechaAuxiliar->addMinutes(30)->format('Y-m-d H:i:s');
            }

        }
        if(isset($modelSucursalCliente) && !empty($modelSucursalCliente) && $modelSucursalCliente->Estado != NULL){
            $data["procedencia"] = $modelSucursalCliente->Estado;
        }

        return response()->json($data);
    }

    public function upFechaEmision(Request $res){
        $data = array(
            "estado" => "error",
            "mensaje" => "La hora no se pudo actualizar",
        );

        $modelProcesoAnalisis = ProcesoAnalisis::where('Folio', 'LIKE', '%' . $res->folio . '%')->get();

        if(!empty($modelProcesoAnalisis)){
            foreach($modelProcesoAnalisis as $fila){
                $fila->Emision_informe = $res->fechaEmision;
                $fila->save();
            }
            $data["estado"] = "exito";
            $data["mensaje"] = "La hora se ha actualizado";
        }

        return response()->json($data);
    }

    public function setGenFolio(Request $res)
    {
        $msg = "";
        $model = Solicitud::where('Hijo', $res->id)->get();

        $contP = 0;
        foreach ($model as $item) {
            $swCodigo = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->get();
            $puntoMuestra = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->first();
            $puntoMuestra->Conductividad = $res->conductividad[$contP];
            $puntoMuestra->Cloruros = $res->cloruros[$contP];
            if ($res->condiciones == "true") {
                $puntoMuestra->Condiciones = 1;
            }else{
                $puntoMuestra->Condiciones = 0;
            }
            $puntoMuestra->save();

            if ($swCodigo->count()) {
                $msg = "Los codigos ya fueron generados";
            } else {
                $canceladoAux = array();
                if ($item->Id_servicio != 3) {
                    $phTemp = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->get();
                    foreach ($phTemp as $phItem) {
                        if ($phItem->Activo == 1) {
                            array_push($canceladoAux, 0);
                        } else {
                            array_push($canceladoAux, 1);
                        }
                    }
                } else {
                    for ($i = 0; $i < $item->Num_tomas; $i++) {
                        array_push($canceladoAux, 0);
                    }
                }


                $parametros = SolicitudParametro::where('Id_solicitud', $item->Id_solicitud)->get();
                $cont = 0;
                foreach ($parametros as $item2) {
                    switch ($item2->Id_subnorma) {
                        case 13: // G&A
                            for ($i = 0; $i < $item->Num_tomas; $i++) {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio . "-G-" . ($i + 1) . "",
                                    'Num_muestra' => $i + 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => $canceladoAux[$i], 
                                ]);
                            }
                            break;
                        case 12: //Coliformes
                        case 137: //Coliformes Totales
                            for ($i = 0; $i < $item->Num_tomas; $i++) {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio . "-C-" . ($i + 1) . "",
                                    'Num_muestra' => $i + 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => $canceladoAux[$i],
                                ]);
                            }
                            break;
                        case 78: // Ecoli alimentos
                            for ($i = 0; $i < $item->Num_tomas; $i++) {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio . "-EC-" . ($i + 1) . "",
                                    'Num_muestra' => $i + 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => $canceladoAux[$i],
                                ]);
                            }
                        
                        $codTemp = CodigoParametros::where('Id_parametro',134)->where('Id_solicitud',$item->Id_solicitud)->get();
                            if ($codTemp->count()) {
                              
                            }else{
                                for ($i = 0; $i < $item->Num_tomas; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => 134,
                                        'Codigo' => $item->Folio_servicio . "-C-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => 0,
                                        'Cadena' => 0,
                                        'Cancelado' => $canceladoAux[$i],
                                    ]);
                                }
                            }
                            break;
                        case 35: //E.Coli
                            if ($model[0]->Id_norma == "27") {
                                if ($res->condiciones == "true") {
                                    for ($i = 0; $i < $item->Num_tomas; $i++) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $item->Id_solicitud,
                                            'Id_parametro' => $item2->Id_subnorma,
                                            'Codigo' => $item->Folio_servicio . "-EC-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => 1,
                                            'Cadena' => 1,
                                            'Cancelado' => $canceladoAux[$i],
                                        ]);
                                    }
                                }else{
                                    if ($res->conductividad[$contP] < 3500) {
                                        for ($i = 0; $i < $item->Num_tomas; $i++) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $item->Id_solicitud,
                                                'Id_parametro' => $item2->Id_subnorma,
                                                'Codigo' => $item->Folio_servicio . "-EC-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => 1,
                                                'Cadena' => 1,
                                                'Cancelado' => $canceladoAux[$i],
                                            ]);
                                        }
                                    }
                                }
                                
                            } else {
                                for ($i = 0; $i < $item->Num_tomas; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => $item2->Id_subnorma,
                                        'Codigo' => $item->Folio_servicio . "-EC-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => 1,
                                        'Cadena' => 1,
                                        'Cancelado' => $canceladoAux[$i],
                                    ]);
                                }
                            }
                            break;
                        case 253: //Enterococos
                            if ($model[0]->Id_norma == "27") {
                                if ($res->condiciones == "true") {
                                    for ($i = 0; $i < $item->Num_tomas; $i++) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $item->Id_solicitud,
                                            'Id_parametro' => $item2->Id_subnorma,
                                            'Codigo' => $item->Folio_servicio . "-EF-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => 1,
                                            'Cadena' => 1,
                                            'Cancelado' => $canceladoAux[$i],
                                        ]);
                                    }
                                }else{
                                    if ($res->conductividad[$contP] >= 3500) {
                                        for ($i = 0; $i < $item->Num_tomas; $i++) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $item->Id_solicitud,
                                                'Id_parametro' => $item2->Id_subnorma,
                                                'Codigo' => $item->Folio_servicio . "-EF-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => 1,
                                                'Cadena' => 1,
                                                'Cancelado' => $canceladoAux[$i],
                                            ]);
                                        }
                                    }
                                }
                               
                            } else {
                                for ($i = 0; $i < $item->Num_tomas; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => $item2->Id_subnorma,
                                        'Codigo' => $item->Folio_servicio . "-EF-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => 1,
                                        'Cadena' => 1,
                                        'Cancelado' => $canceladoAux[$i],
                                    ]);
                                }
                            }
                            break;
                        case 5:
                        case 71:
                        case 70:
                            // DBO
                            for ($i = 0; $i < 3; $i++) {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio . "-D-" . ($i + 1) . "",
                                    'Num_muestra' => $i + 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => 0,
                                ]);
                            }
                            break;
                        case 6: // DQO
                         
                            if ($model[0]->Id_norma == "27") {
                                if ($res->cloruros[$contP] < 1000 ) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => $item2->Id_subnorma,
                                        'Codigo' => $item->Folio_servicio,
                                        'Num_muestra' => 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => 1,
                                        'Cadena' => 1,
                                        'Cancelado' => 0,
                                    ]);
                                }
                            }else{
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => 0,
                                ]);
                            }

                            break;
                        case 152: // COT
                            if ($model[0]->Id_norma == "27") {
                                if ($res->condiciones == "true") {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => $item2->Id_subnorma,
                                        'Codigo' => $item->Folio_servicio,
                                        'Num_muestra' => 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => 1,
                                        'Cadena' => 1,
                                        'Cancelado' => 0,
                                    ]);
                                }else{
                                    if ($res->cloruros[$contP] > 1000) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $item->Id_solicitud,
                                            'Id_parametro' => $item2->Id_subnorma,
                                            'Codigo' => $item->Folio_servicio,
                                            'Num_muestra' => 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => 1,
                                            'Cadena' => 1,
                                            'Cancelado' => 0,
                                        ]);
                                    }
                                }
                               
                            } else {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => 0,
                                ]);
                            }
                        break;
                        case 30:
                            CodigoParametros::create([
                                'Id_solicitud' => $item->Id_solicitud,
                                'Id_parametro' => $item2->Id_subnorma,
                                'Codigo' => $item->Folio_servicio,
                                'Num_muestra' => 1,
                                'Asignado' => 0,
                                'Analizo' => 1,
                                'Reporte' => 1,
                                'Cadena' => 1,
                                'Cancelado' => 0,
                            ]);
                            $codTemp = CodigoParametros::where('Id_parametro',28)->where('Id_solicitud',$item->Id_solicitud)->get();
                            if ($codTemp->count()) {
                                
                            }else{
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => 28,
                                    'Codigo' => $item->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => 0,
                                ]);
                            }
                        
                            $codTemp = CodigoParametros::where('Id_parametro',29)->where('Id_solicitud',$item->Id_solicitud)->get();
                           
                            if ($codTemp->count()) {
                                
                            }else{
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => 29,
                                    'Codigo' => $item->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Cancelado' => 0,
                                ]);
                            }
                            break;
                        default:
                            

                            $codTemp = CodigoParametros::where('Id_parametro',$item2->Id_subnorma)->where('Id_solicitud',$item->Id_solicitud)->get();
                           
                            if ($codTemp->count()) {
                                
                            }else{
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
    
                                    'Cancelado' => 0,
                                ]);
                            }
                            break;
                    }
                    $cont++;
                }
                $msg = "Codigos creados correctamente";
            }
            $contP++;
        }







        $data = array(
            'model' => $model,
            'msg' => $msg,
        );
        return response()->json($data);
    }

    public function setIngresar(Request $res)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $res->idSol)->get();
        $puntoModel = SolicitudPuntos::where('Id_solPadre', $res->idSol)->get();
        $sw = true;
        $msg = "";
        //cambio de hora de recepción
        $addMinute = "";
        $now = Carbon::now();
        $timeProce = "";
        foreach ($puntoModel as $item) {
            $codigoParametro = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->get();
            if ($codigoParametro->count()) {
            } else {
                $sw = false;
            }
        }
        if ($sw == true) {
            $seguimiento = SeguimientoAnalisis::where('Id_servicio', $res->idSol)->first();
            $muestra2 = DB::table('ViewSolicitud2')->where('Hijo', $res->idSol)->get();
            $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $res->idSol)->first();
            $muestra = PhMuestra::where('Id_solicitud', $muestra2[0]->Id_solicitud)->first();
            $sw = false;
            $fecha_muestreo = new Carbon();
            $fecha_ingreso = new Carbon();
            if ($solModel->Id_servicio == 3) {
                $fecha_muestreo->toDateString(date('d/m/y'));
            } else {
                $fecha_muestreo->toDateString(@$muestra->Fecha);
            }
            if ($res->historial == true) {
                $resultadoHistorial = 1;
            } else {
                $resultadoHistorial = 0;
            }
            $fecha_ingreso->toDateString($res->horaRecepcion);
            $date1 = new DateTime($res->horaRecepcion);
            $date2 = new DateTime($fecha_muestreo);
            $diff = $date1->diff($date2);
            $valProce = ProcesoAnalisis::where('Id_solicitud', $res->idSol)->get();
            
            if ($valProce->count() > 0) {
                // aqui se crea la condición para la "ventana" de 10 min despues de ingresar la muestra
                
                $timeProce = $valProce[0]->created_at;
                $addMinute = $timeProce->addMinute(15);

                //ProcesoAnalisis::where('Id_solicitud', $res->idSol)->WhereDate('created_at', '<=', $addMinute->toDateTimeString())->get();
                if ($addMinute >= $now) {
                    switch ($solModel->Id_norma) {
                        case 1:
                        case 27:
                            $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(11)->format('Y-m-d');
                            break;
                        case 5:
                        case 30:
                            $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(14)->format('Y-m-d');
                            break;
                        default:
                            $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(11)->format('Y-m-d');
                            break;
                    }
                  
                        $valProce[0]->Hora_recepcion = $res->horaRecepcion;
                        // $valProce->Hora_entrada = $res->horaEntrada;
                         $valProce[0]->save();
         
                         $solModel = Solicitud::where('Hijo', $res->idSol)->get();
         
                         foreach ($solModel as $itme){
                             $upd = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
                             $upd->Hora_recepcion = $res->horaRecepcion;
                             $upd->Emision_informe = @$fechaEmision;
                             $upd->save();
                         }
                         $msg = "Esta muestra ha sido actializada";
                } else {
                    $msg = "Esta muestra ya fue ingresada hace mas de 10min";
                }
               // $msg = "Esta muestra ya fue ingresada hace mas de 10min";
            } else {
                switch ($solModel->Id_norma) {
                    case 1:
                    case 27:
                        $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(11)->format('Y-m-d');
                        break;
                    case 5:
                    case 30:
                        $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(14)->format('Y-m-d');
                        break;
                    default:
                        $fechaEmision = \Carbon\Carbon::parse(@$res->horaRecepcion)->addDays(11)->format('Y-m-d');
                        break;
                }
                $solModel = Solicitud::where('Hijo', $res->idSol)->get();

                ProcesoAnalisis::create([
                    'Id_solicitud' => $res->idSol,
                    'Folio' => $res->folio,
                    'Descarga' => $res->descarga,
                    'Cliente' => $res->cliente,
                    'Empresa' => $res->empresa,
                    'Ingreso' => 1,
                    'Hora_recepcion' => $res->horaRecepcion,
                    'Hora_entrada' => $res->horaEntrada,
                    'Emision_informe' => @$fechaEmision,
                    'Liberado' => 0,
                    'Id_user_c' => $res->idUsuario,
                    'Historial' => $resultadoHistorial,
                ]);
                foreach ($solModel as $item) {
                    ProcesoAnalisis::create([
                        'Id_solicitud' => $item->Id_solicitud,
                        'Folio' => $item->Folio_servicio,
                        'Descarga' => $res->descarga,
                        'Cliente' => $res->cliente,
                        'Empresa' => $res->empresa,
                        'Ingreso' => 1,
                        'Hora_recepcion' => $res->horaRecepcion,
                        'Hora_entrada' => $res->horaEntrada,
                        'Emision_informe' => @$fechaEmision,
                        'Liberado' => 0,
                        'Id_user_c' => $res->idUsuario,
                        'Historial' => $resultadoHistorial,
                    ]);
                }
                $sw = true;
                $msg = "Muestra ingresada";

            }
        } else {
            $msg = "Hace falta generar codigos para la muestra antes de darle ingreso";
        }
        $data = array(
            'fechaEmision' => $fechaEmision,
            'model' => $model,
            'sw' => $sw,
            'msg' => $msg,
            'puntoModel' => $puntoModel,
            'now' => $now->toDayDateTimeString(),
            'addMinute' => $addMinute,
        );
        return response()->json($data);
    }
}