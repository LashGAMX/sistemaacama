<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\PhMuestra; 
use App\Models\ProcedimientoAnalisis;
use App\Models\ProcesoAnalisis;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class IngresarController extends Controller
{

    public function index()
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.ingresar', compact('idUser', 'model'));
    }

    public function recepcion()
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.recepcion', compact('idUser', 'model'));
    }

    public function buscarFolio(Request $request)
    {
        $tempCli = DB::table('ViewSolicitud2')->where('Folio_servicio', $request->folioSol)->get();
        $std = false;
        if ($tempCli->count()) {
            $cliente = DB::table('ViewSolicitud2')->where('Folio_servicio', $request->folioSol)->first();
            $model = DB::table('ViewSolicitud2')->where('Hijo', $cliente->Id_solicitud)->get();
            $proceso = ProcesoAnalisis::where('Id_solicitud', $cliente->Id_solicitud)->get();

            if ($proceso->count()) {
                $std = true;
            }
        } else {
            $proceso = array();
            $cliente = "";
        }

        $array = array(
            'proceso' => $proceso,
            'std' => $std,
            'cliente' => $cliente,
            'sw' => $tempCli->count(),
        );
        return response()->json($array);
    }
    public function getPuntoMuestreo(Request $res)
    {
        $model = SolicitudPuntos::where('Id_solPadre', $res->id)->get();
        $cloruro = array();
        // $cloruro = 0;

        $conductividad = array();
        $temp = 0;
        $aux = 0;
        foreach ($model as $item) {
            $condModel = ConductividadMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();
            if ($condModel->count()) {
                $aux = 0;
                $temp = 0;
                foreach ($condModel as $item2) {
                    $temp = $temp + $item2->Promedio;
                    $aux++;
                }
                $temp = $temp / $aux;
                array_push($conductividad, round($temp));
            } else {
                array_push($conductividad, '');
            }

            $campoModel = CampoCompuesto::where('Id_solicitud', $item->Id_solicitud)->get();
            if ($campoModel->count()) {
                array_push($cloruro, $campoModel[0]->Cloruros);  
            } else {
                array_push($cloruro, '');
            } 
        }
        $data = array(
            'cloruro' => $cloruro,
            'conductividad' => $conductividad,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getCodigoRecepcion(Request $res)
    {
        $model = DB::table('ViewCodigoRecepcion')->where('Id_solicitud', $res->idSol)->get();
        $data = array(
            'model' => $model, 
            'idSol' => $res->idSol,
        );
        return response()->json($data);
    }
    public function getDataPuntoMuestreo(Request $res)
    {
        $sol = Solicitud::where('Id_solicitud', $res->idSol)->first();
        $model = PhMuestra::where('Id_solicitud', $res->idSol)->orderBy('Id_ph', 'DESC')->first();
        $fecha2 = new \Carbon\Carbon($model->Fecha);
        $procedencia = SucursalCliente::where('Id_sucursal', $sol->Id_sucursal)->first();

        $data = array(
            'procedencia' => $procedencia,
            'fecha2' => $fecha2->addMinutes(30)->format('Y-m-d H:i:s'),
            'sol' => $sol,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setActCC(Request $res)
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

            $campo = CampoCompuesto::where('Id_solicitud',$item->Id_solicitud)->first();
            $campo->Cloruros = $res->cloruros[$contP];
            $campo->save();

            $contP++;
        }

        $msg = "Conductividad y cloruros modificados";
        $data = array(
            'model' => $model,
            'msg' => $msg,
        );
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
                                if($item->Siralab == "0"){
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
    //pp 
    public function fechaFinSiralab(Request $request)
    {
        $siralab = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal', $request->sucursal)->first();
        return response()->json(compact('siralab'));
    }

    public function setIngresar(Request $res)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $res->idSol)->get();
        $puntoModel = SolicitudPuntos::where('Id_solPadre', $res->idSol)->get();
        $sw = true;
        $msg = "";
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

            if ($valProce->count()) {
                $msg = "Esta muestra ya fue ingresada";
            } else {
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
                    'Liberado' => 0,
                    'Id_user_c' => Auth::user()->id,
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
                        'Liberado' => 0,
                        'Id_user_c' => Auth::user()->id,
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
            'model' => $model,
            'sw' => $sw,
            'msg' => $msg,
            'puntoModel' => $puntoModel,
        );
        return response()->json($data);
    }

    //Método para obtener la fecha de conformación
    public function fechaConformacion(Request $request)
    {
        $fechaC = DB::table('ph_muestra')->where('Id_solicitud', $request->idSolicitud)->get();

        return response()->json(compact('fechaC'));
    }

    //Método para obtener la procedencia con previa cotización
    public function procedencia(Request $request)
    {
        $cotizacion_muestreos = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $request->idCotizacion)->first();

        if ($cotizacion_muestreos !== null) {
            $estado = DB::table('estados')->where('Id_estado', $cotizacion_muestreos->Estado)->first();
        }

        return response()->json(compact('estado'));
    }


    //----------------------------------MÓDULO GENERAR-------------------------------------
    public function genera2()
    {
        $idUser = Auth::user()->id;
        $model = DB::table('ViewSolicitud')->get();

        return view('ingresar.solicitud', compact('idUser', 'model'));
    }

    public function buscadorGen(Request $request)
    {
        $model = ProcesoAnalisis::where('Folio', $request->busquedaIn)->first();

        return response()->json(compact('model'));
    }
}
