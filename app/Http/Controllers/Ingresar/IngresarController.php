<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Historial\Campo;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\FotoRecepcion;
use App\Models\FotoRecepcionDB2;

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
use PhpParser\Node\Stmt\TryCatch;

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
        $obs = array();
        $obsInf = array();
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
            $proceModel = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->get();
            if ($proceModel->count()) {
                array_push($obs, @$proceModel[0]->Obs_recepcion);
                array_push($obsInf, @$proceModel[0]->Obs_proceso);
            } else {
                array_push($obs, '');
                array_push($obsInf, '');
            }
        }
        $data = array(
            'obsInf' => $obsInf,
            'obs' => $obs,
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
        $fecha2 = new \Carbon\Carbon(@$model->Fecha);
        $procedencia = SucursalCliente::where('Id_sucursal', $sol->Id_sucursal)->first();
        // $fotos = FotoRecepcion::where('Id_solicitud', '=', $res->idSol)->get();

         $fotos = FotoRecepcion::where('Id_solicitud', '=', $res->idSol)->get();
        if ($fotos->Count()) {
        }else{
            $fotos = FotoRecepcionDB2::where('Id_solicitud', '=', $res->idSol)->get();
        }


        // $fotos = FotoRecepcionDB2::where('Id_solicitud', '=', $res->idSol)->get();

        $data = array(
            'procedencia' => $procedencia,
            'fecha2' => $fecha2->addMinutes(30)->format('Y-m-d H:i:s'),
            'sol' => $sol,
            'model' => @$model,
            'fotos' => $fotos,
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
            } else {
                $puntoMuestra->Condiciones = 0;
            }
            $puntoMuestra->save();

            $campo = CampoCompuesto::where('Id_solicitud', $item->Id_solicitud)->first();
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

    public function getFotos(Request $res)
    {
        // $model = FotoRecepcion::where('Id_solicitud', '=', $res->idSolicitud)->get();

        $model = FotoRecepcion::where('Id_solicitud', '=', $res->idSolicitud)->get();
        if ($model->Count()) {
        }else{
            $model = FotoRecepcionDB2::where('Id_solicitud', '=', $res->idSolicitud)->get();
        }


        $data = array("model" => $model);
        return response()->json($data);
    }

    public function delFoto(Request $res)
    {
        $data = array(
            "estado" => "exito",
            "mensaje" => "imagen eliminada"
        );
        // $modelFotoRecepcion = FotoRecepcionDB2::where('Id_foto_recepcion', '=', $res->idFoto)->first();
        $modelFotoRecepcion = FotoRecepcion::where('Id_foto_recepcion', '=', $res->idFoto)->first();

        if (!empty($modelFotoRecepcion)) {
            $modelFotoRecepcion->delete();
        } else {
            $data["estado"] = "error";
            $data["mensaje"] = "imagen no encontrada";
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
            } else {
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
                        case 173: //Toxicidad Aguda (Vidrio  Fischeri)
                        case 172:
                        case 180:    
                            for ($i = 0; $i < $item->Num_tomas; $i++) {
                                CodigoParametros::create([
                                    'Id_solicitud' => $item->Id_solicitud,
                                    'Id_parametro' => $item2->Id_subnorma,
                                    'Codigo' => $item->Folio_servicio . "-V-" . ($i + 1) . "",
                                    'Num_muestra' => $i + 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => 1,
                                    'Cadena' => 1,
                                    'Mensual'=>1,
                                    'Cancelado' => $canceladoAux[$i],
                                ]);
                            }
                            break;
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

                            $codTemp = CodigoParametros::where('Id_parametro', 134)->where('Id_solicitud', $item->Id_solicitud)->get();
                            if ($codTemp->count()) {
                            } else {
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
                                } else {
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
                                } else {
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
                                if ($res->cloruros[$contP] < 1000) {
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
                        case 152: // COT
                        case 619:
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
                                } else {
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
                            $codTemp = CodigoParametros::where('Id_parametro', 28)->where('Id_solicitud', $item->Id_solicitud)->get();
                            if ($codTemp->count()) {
                            } else {
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

                            $codTemp = CodigoParametros::where('Id_parametro', 29)->where('Id_solicitud', $item->Id_solicitud)->get();

                            if ($codTemp->count()) {
                            } else {
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


                            $codTemp = CodigoParametros::where('Id_parametro', $item2->Id_subnorma)->where('Id_solicitud', $item->Id_solicitud)->get();

                            if ($codTemp->count()) {
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
        $fechaEmision = null;
        $sw = true;
        $msg = "";
        //cambio de hora de recepción
        $addMinute = "";
        $timeProce = "";
        foreach ($puntoModel as $item) {
            $codigoParametro = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->get();
            if ($codigoParametro->count()) {
            } else {
                $sw = false;
                $msg = "Hace falta generar codigos para la muestra antes de darle ingreso";
            }
        }
        if ($model[0]->Id_servicio != 3) {
            $modelViewSolicitud = DB::table('ViewSolicitud2')->where('Hijo', '=', $res->idSol)->where('Cancelado','!=',1)->get();
            $fechaMayor = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '1980-01-01 01:01:01');
            foreach ($modelViewSolicitud as $fila) {
                $modelPhMuestra = PhMuestra::where('Id_solicitud', '=', $fila->Id_solicitud)->orderBy('Id_ph', 'DESC')->first();
                $fechaAuxiliarConformacion = new \Carbon\Carbon(@$modelPhMuestra->Fecha);
                if ($fechaAuxiliarConformacion->gt($fechaMayor)) {
                    $fechaMayor = $fechaAuxiliarConformacion;
                }
            }
            if ($fechaMayor->diffInHours(\Carbon\Carbon::parse($res->horaRecepcion)->format('Y-m-d H:i:s')) > 48) {
                $sw = false;
                $msg = "Ya pasaron mas de 48 horas desde la fecha de conformación, no se puede ingresar";
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
                // aqui se crea la condición para la "ventana" de 10 min despues de ingresar la muestra
                $now = Carbon::now();
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

                    foreach ($solModel as $itme) {
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
                        'Emision_informe' => @$fechaEmision,
                        'Liberado' => 0,
                        'Id_user_c' => Auth::user()->id,
                        'Historial' => $resultadoHistorial,
                    ]);
                }
                $sw = true;
                $msg = "Muestra ingresada";
            }
        }
        $dif = "";
        if (Auth::user()->id == 65) {
            $dif = "| Te vamos a extrañar!!! 🥺";
        }
        $data = array(
            'fechaEmision' => $fechaEmision,
            'model' => $model,
            'sw' => $sw,
            'msg' => $msg . " " . $dif,
            'puntoModel' => $puntoModel,
            'now' => null,
            'timeProceso' => null,
            'addMinute' => $addMinute,
        );
        if (isset($valProce[0]->created_at)) {
            $data['timeProceso'] = $valProce[0]->created_at;
        }
        if (isset($now)) {
            $data['now'] = $now->toDayDateTimeString();
        }
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

    public function setEmision(Request $res)
    {
        $msg = "";
        try {
            $model = ProcesoAnalisis::where('Id_solicitud', $res->idSol)->first();
            $model->Emision_informe = $res->fecha;
            $model->save();
            $solModel = Solicitud::where('Hijo', $res->idSol)->get();
            foreach ($solModel as $item) {
                $model = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
                $model->Emision_informe = $res->fecha;
                $model->save();
            }
            $msg = "Fecha modificada correctamente";
        } catch (\Throwable $th) {
            $msg = $th;
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
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
    public function setObsRecepcion(Request $res)
    {
        $msg = "";

        try {
            $model = ProcesoAnalisis::where('Id_solicitud', $res->id)->first();
            $model->Obs_recepcion = $res->obs;
            $model->Obs_proceso = $res->obsInf;
            $model->save();
            // $temp = SolicitudPuntos::where('Id_solicitud',$res->id)->first();
            // $temp->Obs_recepcion = $res->obsInf;
            // $temp->save();
            $msg = "Observacion asignada";
        } catch (\Throwable $th) {
            $msg = $th;
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }

    public function ConsultarFoto()
    {
        // // $fotos = FotoRecepcionDB2::select('Id_foto_recepcion', 'Id_solicitud')->get();
         
     $fotos = FotoRecepcion::select('Id_foto_recepcion', 'Id_solicitud')->get();

        return response()->json($fotos);
    }
    
}
