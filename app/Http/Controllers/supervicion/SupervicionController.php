<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use App\Models\CadenaGenerales;
use App\Models\CodigoParametros;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleAlcalinidad;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleIcp;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetalleSolidos;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudesGeneradas;
use App\Models\MatrazGA;
use App\Models\CrisolesGA;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Break_;

class SupervicionController extends Controller
{
    public function analisis()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $tipo = DB::table('tipo_formulas')
            ->where('Id_tipo_formula', 20)
            ->orWhere('Id_tipo_formula', 21)
            ->orWhere('Id_tipo_formula', 22)
            ->orWhere('Id_tipo_formula', 23)
            ->orWhere('Id_tipo_formula', 24)
            ->orWhere('Id_tipo_formula', 58)
            ->orWhere('Id_tipo_formula', 59)
            ->get();
        $data  = array(
            'parametro' => $parametro,
            'tipo' => $tipo,
        );
        return view('supervicion.analisis.analisis', $data);
    }
    public function getLotes(Request $res)
    {
        // $model = DB::table('viewlotedetalle')->where('Id_parametro',$res->parametro)->where('Fecha','LIKE','%'.$res->mes.'%')->get();
        $parametro = Parametro::find($res->parametro);
        if ($res->tipo != 0) {
            $model = DB::table('ViewLoteAnalisis')->where('Id_tipo_formula', $res->tipo)->where('Fecha', 'LIKE', '%' . $res->mes . '%')->get();
        } else {
            switch ($parametro->Id_area) {
                case 17:
                    $model = DB::table('ViewLoteAnalisis')->where('Id_area', 17)->where('Fecha', 'LIKE', '%' . $res->mes . '%')->get();
                    break;
                case 2:
                    if ($res->tipo == 0) {
                        $model = DB::table('ViewLoteAnalisis')->where('Id_area', 2)->where('Id_tecnica', $res->parametro)->where('Fecha', 'LIKE', '%' . $res->mes . '%')->get();
                    } else {
                        $model = DB::table('ViewLoteAnalisis')->where('Id_tipo_formula', $res->tipo)->where('Fecha', 'LIKE', '%' . $res->mes . '%')->get();
                    }
                    break;
                default:
                    $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->parametro)->where('Fecha', 'LIKE', '%' . $res->mes . '%')->get();
                    break;
            }
        }

        $data = array(
            'parametro' => $parametro,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function supervisarBitacora(Request $res)
    {
        $sw = false;
        $msg = "Error al liberar";
        $parametro = Parametro::find($res->parametro);
        switch ($parametro->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                break;
            case 17:
                // $sw = true;
                // $msg = "Muestra liberada"  ;
                switch ($res->parametro) {
                    case 207:
                    case 209:
                    case 211:
                    case 212:
                    case 213:
                    case 214:
                    case 217:
                    case 227:
                    case  231:
                    case 233:
                    case 264:
                    case 295:
                    case 296:
                    case 299:
                    case 300:
                    case 301:
                        $model = LoteDetalleIcp::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                }
                break;

            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                $model = LoteDetalleEspectro::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                break;
            case 13: // G&A
                $model = LoteDetalleGA::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                break;
            case 15: // Solidos
                $model = LoteDetalleSolidos::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                break;
            case 14: //Volumetria
                switch ($res->parametro) {
                    case 6: // Dqo
                    case 161:
                        $model = LoteDetalleDqo::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 33: // Cloro
                    case 64:
                    case 119:
                    case 218:
                        $model = LoteDetalleCloro::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 9: // Nitrogeno
                    case 10:
                    case 11:
                    case 287:
                    case 83:
                    case 108:
                        $model = LoteDetalleNitrogeno::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 28: //Alcalinidad
                    case 29:
                    case 30:
                        $model = LoteDetalleAlcalinidad::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                }
                break;
            case 7: // Campo
            case 19: //Directos
                $model = LoteDetalleDirectos::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                break;
            case 8: //Potable
                switch ($res->parametro) {
                    case 77: //Dureza
                    case 103:
                    case 251:
                    case 252:
                        // $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $res->idLote)->where('Liberado',0)->get();
                        $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $res->idLote)->get();
                        break;
                    default:
                        $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                        break;
                }
                break;
            case 6: // Mb
            case 12:
            case 3:
                switch ($res->parametro) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // E COLI
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                        $model = LoteDetalleColiformes::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 253: //todo  ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5)  
                    case 71:
                        $model = LoteDetalleDbo::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 70:
                        $model = LoteDetalleDboIno::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 16: //todo Huevos de Helminto 
                        $model = LoteDetalleHH::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    case 78:
                        $model = LoteDetalleEcoli::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_lote', $res->id)->where('Liberado', 0)->get();
                        break;
                }
                break;
            default:
                break;
        }
        if ($model->count()) {
            $sw = false;
            $msg = "Hay muestras sin liberar";
        } else {
            $model = LoteAnalisis::where('Id_lote', $res->id)->first();
            if ($model->Supervisado == 0) {
                $model->Supervisado = 1;
                $msg = "Lote supervisado";
            } else {
                $model->Supervisado = 0;
                $msg = "Lote desliberada";
            }
            if ($res->user != 0) {
                $model->Id_superviso = $res->user;
            } else {
                $model->Id_superviso = Auth::user()->id;
            }
            $model->save();
        }

        $data = array(
            'msg' => $msg,
            'sw' => $sw,
        );
        return response()->json($data);
    }

    public function campo()
    {
        //Adicion de intermediarios
        $muestreador = DB::table('users')->where('role_id', 8)->orWhere('role_id', 15)->orWhere('role_id', 13)->get();
        $data  = array(
            'muestreador' => $muestreador,
        );
        return view('supervicion.campo.campo', $data);
    }
    public function getMuestreos(Request $res)
    {
        if ($res->muestreador != 0) {
            $model = DB::table('ViewSolicitudGeneradaSupervicion')->where('Id_muestreador', $res->muestreador)->where('Fecha_muestreo', 'LIKE', '%' . $res->mes . '%')->get();
        } else {
            $model = DB::table('ViewSolicitudGeneradaSupervicion')->where('Fecha_muestreo', 'LIKE', '%' . $res->mes . '%')->get();
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function supervisarBitacoraCampo(Request $res)
    {
        $model = SolicitudesGeneradas::where('Id_solicitud', $res->id)->first();
        $msg = "Supervisado";

        if ($res->user != 0) {
            $model->Id_superviso = $res->user;
        } else {
            $model->Id_superviso = Auth::user()->id;
        }
        $model->Estado = 4;
        $model->save();


        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function setLiberarTodoCampo(Request $res)
    {
        $sw = true;
        $msg = "Error al liberar";
        if (sizeof($res->ids) > 0) {
            for ($i = 0; $i <  sizeof($res->ids); $i++) {
                $model = SolicitudesGeneradas::where('Id_solicitud', $res->ids[$i])->first();
                if ($res->user != 0) {
                    $model->Id_superviso = $res->user;
                } else {
                    $model->Id_superviso = Auth::user()->id;
                }
                $model->Estado = 4;
                $model->save();
            }
        } else {
            $msg = "No hay muestra seleccionada";
        }
        $data = array(
            'msg' => $msg,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setLiberarTodo(Request $res)
    {
        $sw = true;
        $msg = "Error al liberar";
        if (sizeof($res->ids) > 0) {
            for ($i = 0; $i <  sizeof($res->ids); $i++) {
                $model = LoteAnalisis::where('Id_lote', $res->ids[$i])->first();
                if ($model->Supervisado == 0) {
                    $model->Supervisado = 1;
                    $msg = "Lote supervisado";
                } else {
                    $model->Supervisado = 0;
                    $msg = "Lote desliberada";
                }
                if ($res->user != 0) {
                    $model->Id_superviso = $res->user;
                } else {
                    $model->Id_superviso = Auth::user()->id;
                }
                $model->save();
            }
        } else {
            $msg = "No hay muestra seleccionada";
        }
        $data = array(
            'msg' => $msg,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    // public function setSupervicion(Request $res)
    // {
    //     $msg = '';
    //     $std = 0;

    //     $solModel = Solicitud::where('Hijo', $res->idSol)->get();
    //     if ($res->std == "true") {
    //         $std = 1;
    //         $msg = "Folio supervisado";
    //     } else {
    //         $msg = "folio sin supervicion";
    //     }

    //     $temp = ProcesoAnalisis::where('Id_solicitud', $res->idSol)->first();
    //     $temp->Supervicion = $std;
    //     $temp->save();



    //     foreach ($solModel as $item) {
    //         $temp = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
    //         $temp->Supervicion = $std;
    //         $temp->save();
    //     }

    //     $data = array(
    //         'msg' => $msg,
    //     );
    //     return response()->json($data);
    // }

    public function setSupervicion(Request $res)
    {
        $msg = '';
        $std = $res->std == "true" ? 1 : 0;
        $msg = $std ? "Folio supervisado" : "Folio sin supervisión";

        $solModel = Solicitud::where('Hijo', $res->idSol)->get();
        $idsActualizados = $solModel->pluck('Id_solicitud')->toArray();

        // Actualiza la supervisión de cada solicitud relacionada
       
            $analisis = ProcesoAnalisis::where('Id_solicitud', $res->idSol)->first();
            $analisis-> Supervicion = $std;
            $analisis->save();
           
        
        // Obtener IDs de matraces y crisoles relacionados
        $gaIds = LoteDetalleGA::whereIn('Id_analisis', $idsActualizados)->pluck('Id_matraz');
        $solIds = LoteDetalleSolidos::whereIn('Id_analisis', $idsActualizados)->pluck('Id_crisol');

        // Cambiar estado de matraces
        $matraces = MatrazGA::whereIn('Id_matraz', $gaIds)->get();
        foreach ($matraces as $matraz) {
            $matraz->Estado = 0;
            $matraz->save();
        }

        // Cambiar estado de crisoles
        $crisoles = CrisolesGA::whereIn('Id_crisol', $solIds)->get();
        foreach ($crisoles as $crisol) {
            $crisol->Estado = 0;
            $crisol->save();
        }

        return response()->json(['msg' => $msg]);
    }

    public function setLiberar(Request $res)
    {
        $msg = '';
        $std = 0;
        $solModel = Solicitud::where('Hijo', $res->idSol)->get();
        if ($res->std == "true") {
            $std = 1;
            $msg = "Folio supervisado";
        } else {
            $msg = "folio sin supervicion";
        }

        $temp = ProcesoAnalisis::where('Id_solicitud', $res->idSol)->first();
        $temp->Liberado = $std;
        $temp->save();

        foreach ($solModel as $item) {
            $temp = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
            $temp->Liberado = $std;
            $temp->save();
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function liberarTodo()
    {
        $model = SolicitudesGeneradas::all();
        foreach ($model as $item) {
            switch ($item->Id_muestreador) {
                case 15:
                    $temp = SolicitudesGeneradas::find($item->Id_solicitudGen);
                    $temp->Estado = 4;
                    $temp->Id_superviso = 97;
                    $temp->save();
                    break;

                default:
                    $temp = SolicitudesGeneradas::find($item->Id_solicitudGen);
                    $temp->Estado = 4;
                    $temp->Id_superviso = 15;
                    $temp->save();
                    break;
            }
        }
    }
    public function setHistorialCadena()
    {
        $msg = '';
        $std = 0;
        //Datos generales
        $area = '';
        $idArea = '';
        $responsable = '';
        $numRecipientes = 0;
        $fechasSalidas = '';
        $stdArea = '';
        $firmas = '';
        $idParametro = '';
        $contAux = 0;
        $tempArea = array();
        $sw = true;
        $user = 1;
        $fechaTemp = '';
        $hisModel = ProcesoAnalisis::whereDate('Hora_recepcion', '<=', '2024-04-29')->orderBy('Id_solicitud', 'DESC')->limit(100)->get();
        foreach ($hisModel as $item) {
            echo "<br> Id_sol: " . $item->Id_solicitud;
            echo "<br>-----------------------------------";
            $idSol = $item->Id_solicitud;
            $solModel = Solicitud::where('Id_solicitud', $item->Id_solicitud)->first();


            $temp = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
            $areaParam = DB::table('viewcodigoinforme')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', '!=', 64)->where('Cancelado', 0)->get();
            $phMuestra = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();
            $detalleTemp = DB::table('cadena_generales')->where('Id_solicitud', $item->Id_solicitud)->delete();

            foreach ($areaParam as $item2) {
                $contAux = 0;
                $auxEnv = DB::table('ViewEnvaseParametro')->where('Id_parametro', $item2->Id_parametro)->where('Reportes', 1)->where('stdArea', '=', NULL)->get();
                $sw = false;
                // $valParametro = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item2->Id_parametro)->where('Cancelado',0)->get();

                if ($auxEnv->count()) {
                    $sw = false;
                    for ($i = 0; $i < sizeof($tempArea); $i++) {
                        if ($auxEnv[0]->Id_area == $tempArea[$i]) {
                            $sw = true;
                        }
                        switch ($item2->Id_parametro) {
                            case 11:
                                $sw = true;
                                break;

                            default:
                                # code...
                                break;
                        }
                    }

                    if ($sw != true) {



                        $user = DB::table('users')->where('id', $auxEnv[0]->Id_responsable)->first();
                        if (@$item2->Id_area == 12 || @$item2->Id_area == 6 || @$item2->Id_area == 13 || @$item2->Id_area == 3) {
                            if (@$item2->Id_parametro != 16) {
                                if ($solModel->Id_servicio != 3) {
                                    switch ($auxEnv[0]->Id_area) {

                                        default:
                                            $numRecipientes = $phMuestra->count();
                                            break;
                                    }
                                } else {
                                    switch ($auxEnv[0]->Id_area) {
                                        default:
                                            $numRecipientes = $solModel->Num_tomas;
                                            break;
                                    }
                                }
                            } else {
                                $numRecipientes = 1;
                            }

                            $stdArea = 1;
                        } else {
                            $numRecipientes = 1;
                            $stdArea = 0;
                        }
                        $idSol = $item->Id_solicitud;
                        echo "<br> idSol Entro: " . $idSol;
                        echo "<br> ::::::::::::::::::::::";
                        switch ($item2->Id_area) {
                            case 2: // Metales
                                $modelDet = DB::table('lote_detalle')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 17: // Metales ICP
                                // $modelDet = DB::table('lote_detalle_icp')->where('Id_codigo', $model->Folio_servicio)->where('Id_control', 1)->where('Id_parametro', $item->Parametro)->get();
                                $modelDet = DB::table('lote_detalle_icp')->where('Id_control', 1)->where('Id_codigo', $solModel->Folio_servicio)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    // $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $modelDet[0]->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 6: // MB Residual
                            case 12: // MB Alimentos
                            case 3:
                                switch ($item2->Id_parametro) {
                                    case 5: // DBO
                                    case 71:
                                        $modelDet = DB::table('lote_detalle_dbo')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 12: // Coliformes  
                                    case 134:
                                    case 135:
                                    case 133:
                                    case 35:
                                    case 137:
                                    case 51:
                                        $modelDet = DB::table('lote_detalle_coliformes')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 16: // H.H
                                        $modelDet = DB::table('lote_detalle_hh')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 78: // E.Coli
                                        $modelDet = DB::table('lote_detalle_ecoli')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 253:
                                        $modelDet = DB::table('lote_detalle_enterococos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    default:
                                        $modelDet = DB::table('lote_detalle_ecoli')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                }
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }

                                break;

                            case 14: // volumetria 
                                switch ($item2->Id_parametro) {
                                    case 6: // DQO
                                        $modelDet = DB::table('lote_detalle_dqo')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case "218":
                                        $modelDet = DB::table('lote_detalle_directos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 9:
                                    case 10:
                                    case 11:
                                    case 108:
                                        $modelDet = DB::table('lote_detalle_nitrogeno')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 28:
                                    case 29:
                                    case 30:
                                        $modelDet = DB::table('lote_detalle_alcalinidad')->where('Id_analisis', $idSol)->where('Id_control', 1)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    default:
                                        $modelDet = DB::table('lote_detalle_potable')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                }
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 13: // GA
                                $modelDet = DB::table('lote_detalle_ga')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 15: // Solidos
                                $modelDet = DB::table('lote_detalle_solidos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 16: // Espectrofotonetria
                                $modelDet = DB::table('lote_detalle_espectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }

                                break;
                            case 5: // FQ
                                switch ($item2->Id_parametro) {
                                    case 5: // DBO
                                    case 71:
                                        $modelDet = DB::table('lote_detalle_dbo')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 11:
                                        break;
                                    default:
                                        $modelDet = DB::table('lote_detalle_espectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                }
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }

                                break;
                            case 8: // potable
                                switch ($item2->Id_parametro) {
                                    case 108: // N Amoniacal
                                        $modelDet = DB::table('lote_detalle_nitrogeno')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 14:
                                    case 110:
                                    case 98:
                                        $modelDet = DB::table('lote_detalle_directos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                    case 103:
                                    case 77:
                                    case 251:
                                        $modelDet = DB::table('lote_detalle_dureza')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        echo "Entro dure";
                                        break;
                                    case 64:
                                    case 358:

                                        break;
                                    default:
                                        $modelDet = DB::table('lote_detalle_potable')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        break;
                                }
                                // var_dump($modelDet[0]->Id_lote);
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            case 19:
                            case 7:
                                $modelDet = DB::table('lote_detalle_directos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                if ($modelDet->count()) {
                                    $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                    $fechaTemp = $loteTemp->Fecha;
                                } else {
                                    $fechaTemp = "";
                                }
                                break;
                            default:
                                $fechaTemp = "";
                                break;
                        }
                        array_push($tempArea, $auxEnv[0]->Id_area);


                        $fechaEntrada = "";
                        $fechaSalidaEli = "";
                        $fechaEmision = "";
                        $firma =  $user->firma;

                        if ($stdArea == 1) {
                            $fechaEntrada = "---------------";
                            $fechaSalidaEli = "---------------";
                            $fechaEmision = "---------------";
                        } else {
                            if ($fechaTemp != "") {
                                if ($item->Id_area == 12 || $item->Id_area == 6 || $item->Id_area == 13 || $item->Id_area == 3) {
                                    $fechaEntrada = "---------------";
                                    $fechaSalidaEli = "---------------";
                                    $fechaEmision = "---------------";
                                } else {
                                    $fechaEntrada = \Carbon\Carbon::parse(@$fechaTemp)->format('d/m/Y');
                                    $fechaEmision = \Carbon\Carbon::parse(@$fechaTemp)->format('d/m/Y');
                                    switch ($item->Id_norma) {
                                        case 1:
                                        case 27:
                                        case 33:
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                            // $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                            break;
                                        case 5:
                                        case 30:
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(14)->format('d/m/Y');
                                            // $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(14)->format('d/m/Y');
                                            break;
                                        default:
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                            // $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                            break;
                                    }
                                }
                            } else {
                                $fechaEntrada =  "Sin capturar";
                                $fechaSalidaEli =  "Sin capturar";
                                $fechaEmision =  "Sin capturar";
                            }
                        }
                        if (\Carbon\Carbon::parse(@$fechaTemp)->format('d/m/Y') != "") {
                            switch ($solModel->Id_norma) {
                                case 1:
                                case 27:
                                case 33:
                                    // $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                    $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                    break;
                                case 5:
                                case 30:
                                    // $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(14)->format('d/m/Y');
                                    $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(14)->format('d/m/Y');
                                    break;
                                default:
                                    // $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                    $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                    break;
                            }
                        } else {
                            $fechaSalidaEli = "---------------";
                            $fechaEmision = "---------------";
                        }


                        // $detalle = CadenaGenerales::create([
                        //     'Id_solicitud' => $item->Id_solicitud,
                        //     'Area' => $auxEnv[0]->Area,
                        //     'Responsable' => $user->name,
                        //     'Recipientes' => $numRecipientes,
                        //     'Fecha_salida' => \Carbon\Carbon::parse(@$fechaTemp)->format('d/m/Y'),
                        //     'Fecha_entrada' => $fechaEntrada,
                        //     'Fecha_salidaEli' => $fechaSalidaEli,
                        //     'Fecha_emision' => $fechaEmision,
                        //     'Firma' => $firma,
                        // ]); 

                    }
                }
            }
        }
    }
    public function valInforme()
    {
        return view('supervicion.informe.valInforme');
    }
    public function getFirmaEncriptada(Request $res)
    {
        $std = false;
        $clave  = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
         Encripta el contenido de la variable, enviada como parametro.
          */
        $folioEncript = openssl_decrypt($res->codigo, $method, $clave, false, $iv);
        $content = explode($folioEncript, '|');


        $data = array(
            'content' => $content,
            'folioEncript' => $folioEncript,
        );
        return response()->json($data);
    }
    public function setfirmaPad(Request $res)
    {
        $msg = "Firma Autorizada";

        $temp = ProcesoAnalisis::where('Id_solicitud', $res->id)->first();
        $temp->Firma_superviso = $res->firma;
        $temp->save();

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
}
