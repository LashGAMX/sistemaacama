<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Config\CrisolGA;
use App\Models\CampoCompuesto;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleColor;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEspectro;
use App\Models\FotoRecepcion;
use App\Models\LoteDetalleAlcalinidad;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetallePotable;
use App\Models\LoteDetalleSolidos;
use App\Models\MatrazGA;
use App\Models\CrisolesGA;
use App\Models\FotoRecepcionDB2;
use App\Models\LoteDetalleVidrio;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudPuntos;
use App\Models\TemperaturaMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mpdf\Tag\Tr;
use Psy\Command\WhereamiCommand;

class CadenaController extends Controller
{
    public function cadenaCustodia()
    {

        $model2 = DB::table('solicitudes as sol')
            ->join('intermediarios as inter', 'inter.Id_intermediario', '=', 'sol.Id_intermediario')
            ->join('clientes as cli', 'inter.Id_cliente', '=', 'cli.Id_cliente')
            ->join('sucursales_cliente as suc', 'suc.Id_sucursal', '=', 'sol.Id_sucursal')
            ->join('tipo_servicios as ser', 'sol.Id_servicio', '=', 'ser.Id_tipo')
            ->join('tipo_descargas as des', 'des.Id_tipo', '=', 'sol.Id_descarga')
            ->join('normas as nor', 'nor.Id_norma', '=', 'sol.Id_norma')
            ->join('sub_normas as sub', 'sub.Id_subnorma', '=', 'sol.Id_subnorma')
            ->join('proceso_analisis as pro', 'pro.Id_solicitud', '=', 'sol.Id_solicitud')
            ->join('users as creador', 'creador.id', '=', 'pro.Id_user_c')
            ->select(
                'sol.Id_solicitud',
                'sol.Folio_servicio',
                'sol.Fecha_muestreo',
                'pro.Hora_recepcion as Fecha_recepcion',
                'suc.Empresa as Empresa_suc',
                'sol.Id_intermediario',
                'sub.Norma as Nor_sub',
                'suc.Estado',
                'sol.Padre',
                'sol.created_at',
                'sol.updated_at',
                'sol.Padre',
                'creador.name as Id_user_c'
            )
            ->where('sol.Padre', '!=', 0)
            // ->whereIn(DB::raw('YEAR(sol.created_at)'), [2024, 2025])
            ->orderBy('sol.Id_solicitud', 'desc')
            ->get();

        return view('supervicion.cadena.cadena', compact('model2'));
    }
    public function detalleCadena($id)
    {
        $swSir = false;
        $model = DB::table('ViewSolicitud3')->where('Id_solicitud', $id)->where('Padre', 1)->where('Padre', '!=', 0)->first();
        $intermediario = DB::table('ViewIntermediarios')->where('Id_intermediario', $model->Id_intermediario)->first();
        $proceso = ProcesoAnalisis::where('Id_solicitud', $id)->first();
        $direccion = DireccionReporte::where('Id_direccion', $model->Id_direccion)->first();
        if ($model->Siralab == 1) {
            $swSir = true;
        } else {
        }
        $puntos = SolicitudPuntos::where('Id_solPadre', $id)->get();
        return view('supervicion.cadena.detalleCadena', compact('model', 'puntos', 'swSir', 'intermediario', 'proceso', 'direccion'));
    }

    public function getFotos(Request $res)
    {
        $model = FotoRecepcion::where('Id_solicitud', '=', $res->id)->get();
        if ($model->Count()) {
        }else{
            $model = FotoRecepcionDB2::where('Id_solicitud', '=', $res->id)->get();
        }

        $data = array("model" => $model);
        return response()->json($data);
    }

    public function getParametroCadena(Request $res)
    {
        $porcentaje = array();
        $porcentajeCom = array();
        try {
            $proceso = ProcesoAnalisis::where('Id_solicitud', $res->idPunto)->first();
            $model = DB::table('ViewCodigoRecepcion')
                ->where('Id_solicitud', $res->idPunto)
                ->where('Num_muestra', 1)
                ->orderByRaw('CASE WHEN Orden IS NOT NULL THEN 0 ELSE 1 END, Orden ASC')
                ->get();

            $models = DB::table('solicitudes')
                ->where('Id_solicitud', '=', $res->idPunto)
                ->whereNull('deleted_at')
                ->first();

            switch ($models->Id_norma) {
                case 27:
                    switch ($models->Id_reporte2) {
                        case 0:
                            foreach ($model as $fila) {
                                $fila->Limite = 'N/A';
                            }
                            break;
                        default:
                            foreach ($model as $fila) {
                                $modelLim = DB::table('limite001_2021')
                                    ->where('Id_parametro', '=', $fila->Id_parametro)
                                    ->where('Id_categoria', '=', $models->Id_reporte2)
                                    ->whereNull('deleted_at')
                                    ->first();

                                if (!empty($modelLim)) {
                                    $fila->Limite = $modelLim->Pm;
                                } else {
                                    $fila->Limite = 'N/A';
                                }
                            }
                            break;
                    }
                    break;
                default:
                    foreach ($model as $fila) {
                        $fila->Limite = 'N/A';
                    }
                    break;
            }

            $tempData = '';
            $tempPa = '';
            $contPa = 0;
            foreach ($model as $item) {
                switch ($item->Id_parametro) {
                    case 6:
                    case 11:
                    case 90:
                        // $temp = CodigoParametros::where('Codigo', $res->folio)->where('Id_parametro', )->get();      
                        $tempData = "100%";
                        $tempPa = "success";
                        break;
                    case 5:
                    case 4:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 6)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = ($temp[0]->Resultado * 60) / 100;
                                $tempData = "60% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 15:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 11)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado2 != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = ($temp[0]->Resultado2 * 20) / 100;
                                $tempData = "20% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 88:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 90)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = ($temp[0]->Resultado * 75) / 100;
                                $tempData = "75% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 8:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 11)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado2 != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = ($temp[0]->Resultado2 * 10) / 100;
                                $tempData = "10% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 88:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 67)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado2 != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = $temp[0]->Resultado2;
                                $tempData = "Conductividad% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 77:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 88)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = $temp[0]->Resultado;
                                $tempData = "SDT% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 46:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 4)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = $temp[0]->Resultado;
                                $tempData = "SST% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    case 96:
                    case 114:
                        $temp = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 15)->get();
                        if ($temp->count()) {
                            if ($temp[0]->Resultado2 != null) {
                                $tempAux = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', $item->Id_parametro)->first();
                                $aux = ($temp[0]->Resultado2 * 33.3) / 100;
                                $tempData = "33.3% | < " . $aux;
                                if ($tempAux->Resultado < $aux) {
                                    $tempPa = "success";
                                } else {
                                    $tempPa = "danger";
                                    $contPa++;
                                }
                            } else {
                                $tempData = "Aun no hay datos de comparacion";
                                $tempPa = "success";
                            }
                        } else {
                            $tempData = "No hay parametro para comparacion";
                            $tempPa = "success";
                        }
                        break;
                    default:
                        $tempData = "100%";
                        $tempPa = "success";
                        break;
                }
                array_push($porcentaje, $tempData);
                array_push($porcentajeCom, $tempPa);
            }

            $data = [
                'proceso' => $proceso,
                'model' => $model,
                'contPa' => $contPa,
                'porcentajeCom' => $porcentajeCom,
                'porcentaje' => $porcentaje,
            ];

            return response()->json($data);
        } catch (\Exception $e) {

            return response()->json(['error' => 'No carga los datos'], 500);
        }
    }


    public function liberarMuestra(Request $res)
    {
        $sw = true;
        $model = CodigoParametros::where('Id_codigo', $res->idCod)->first();
        switch ($model->Id_parametro) {
            case 5:
                $aux = CodigoParametros::where('Id_parametro', $model->Id_parametro)->where('Id_solicitud', $model->Id_solicitud)->get();
                for ($i = 0; $i < $aux->count(); $i++) {
                    $aux[$i]->Cadena = 0;
                    $aux[$i]->Reporte = 0;
                    $aux[$i]->save();
                }
                break;

            default:

                break;
        }

        $solModel = Solicitud::where('Id_solicitud', $model->Id_solicitud)->where('Id_servicio', '!=', 3)->get();
        switch ($model->Id_parametro) {
            case 14:
            case 31:
            case 97:
            case 100:
            case 67:
            case 68:
            case 26:
            case 64:
            case 358:
            case 110:
            case 2:
            case 361:
                if ($solModel->count()) {
                    $solGen = SolicitudesGeneradas::where('Id_solicitud', $model->Id_solicitud)->first();
                    $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                    $model2->Resultado2 = $res->resLiberado;
                    $model2->Cadena = 1;
                    $model2->Analizo = $solGen->Id_muestreador;
                    $model2->Reporte = 1;
                } else {
                    $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                    $model2->Resultado2 = $res->resLiberado;
                    $model2->Cadena = 1;
                    $model2->Reporte = 1;
                }
                break;
            case 11:
            case 83:
                $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                $model2->Resultado2 = $res->resLiberado;
                $model2->Cadena = 1;
                $model2->Reporte = 1;
                // $model2->Analizo = Auth::user()->id;
                $model2->Analizo = 14;
                break;
            default:
                $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                $model2->Resultado2 = $res->resLiberado;
                $model2->Cadena = 1;
                $model2->Reporte = 1;
                break;
        }


        // $model2->Analizo = Auth::user()->id;
        $model2->Liberado = 1;
        $model2->Asignado = 1;
        $model2->save();


        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }


    public function regresarMuestra(Request $res)
    {
        $codigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        switch ($codigoParametro->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item) {
                    $item->Liberado = 0;
                    $item->save();
                    $asignado = LoteDetalle::where('Id_lote', $item->Id_lote)->count();
                    $liberado = LoteDetalle::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                }
                break;
            case 16: //Espectrofotometria
            case 5: //Fisicoquimicos
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item) {
                    $item->Liberado = 0;
                    $item->save();
                    $asignado = LoteDetalleEspectro::where('Id_lote', $item->Id_lote)->count();
                    $liberado = LoteDetalleEspectro::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                }
            case 13: //Grasas (G&A)
                $model = LoteDetalleGA::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item) {
                    $codigo = LoteDetalleGA::where('Id_codigo', $item->Id_codigo)->first();
                    $codigo->Liberado = 0;
                    $codigo->save();
                    $asignado = LoteDetalleGA::where('Id_lote', $item->Id_lote)->count();
                    $liberado = LoteDetalleGA::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                }
                break;
            case 15: //Solidos
                $model = LoteDetalleSolidos::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item) {
                    $item->Liberado = 0;
                    $item->save();
                    $asignado = LoteDetalleSolidos::where('Id_lote', $item->Id_lote)->count();
                    $liberado = LoteDetalleSolidos::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                }

                break;
            case 14: // Volumetria
                switch ($codigoParametro->Id_parametro) {
                    case 6:
                    case 161:
                        $model = LoteDetalleDqo::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleDqo::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleDqo::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 33:
                    case 218:
                    case 119:
                    case 64:
                        $model = LoteDetalleCloro::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleCloro::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleCloro::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 10:
                    case 9:
                    case 11:
                    case 287:
                    case 83:
                    case 108:
                        $model = LoteDetalleNitrogeno::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleNitrogeno::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleNitrogeno::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;

                    case 28:
                    case 29:
                    case 30:
                    case 27:
                        $model = LoteDetalleAlcalinidad::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleAlcalinidad::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleAlcalinidad::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                }
                break;
            case 7: //Campo
            case 19: // Directos
                switch ($codigoParametro->Id_parametro) {
                    case 102:
                        $model = LoteDetalleColor::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleColor::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleColor::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 173:
                        $model = LoteDetalleVidrio::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleVidrio::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleVidrio::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    default:
                        $model = LoteDetalleDirectos::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleDirectos::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleDirectos::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                }
                break;
            case 8: //Potable
                switch ($codigoParametro->Id_parametro) {
                    case 77: //Dureza
                    case 103:
                    case 251:
                    case 252:
                        $model = LoteDetalleDureza::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleDureza::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleDureza::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    default:
                        $model = LoteDetallePotable::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetallePotable::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetallePotable::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                }
                break;
            case 6: // Microbiología Residual
            case 12: // Microbiología Alimentos
            case 3: // Alimentos
                switch ($codigoParametro->Id_parametro) {
                    case 135: // Coliformes fecales
                    case 132:
                    case 133:
                    case 12:
                    case 134: // termotolerantes
                    case 35:
                    case 51: // Coliformes totales
                    case 137:
                    case 350:
                        $model = LoteDetalleColiformes::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleColiformes::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleColiformes::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 253: // ENTEROCOCO FECAL
                        $model = LoteDetalleEnterococos::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleEnterococos::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleEnterococos::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 5: // DEMANDA BIOQUIMICA DE OXIGENO (DBOS) 
                    case 71:
                        $model = LoteDetalleDbo::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();

                        foreach ($model as $item) {
                            //dd("EjEMPLO", $item->Id_lote);
                            $item->Liberado = 0;
                            $item->save();

                            $asignado = LoteDetalleDbo::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleDbo::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                            //  dd("Asignado", $asignado, "Liberado", $liberado);
                        }

                        break;
                    case 70:
                        $model = LoteDetalleDboIno::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleDboIno::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleDboIno::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 16:
                        $model = LoteDetalleHH::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleHH::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleHH::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                    case 78:
                        $model = LoteDetalleEcoli::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                        foreach ($model as $item) {
                            $item->Liberado = 0;
                            $item->save();
                            $asignado = LoteDetalleEcoli::where('Id_lote', $item->Id_lote)->count();
                            $liberado = LoteDetalleEcoli::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                        }
                        break;
                }
                break;
            default:
                $model = LoteDetalleDirectos::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item) {
                    $item->Liberado = 0;
                    $item->save();
                    $asignado = LoteDetalleDirectos::where('Id_lote', $item->Id_lote)->count();
                    $liberado = LoteDetalleDirectos::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->count();
                }

                break;
        } //fin del switch area 

        $lote = LoteAnalisis::find($codigoParametro->Id_lote);
        $lote->Asignado = $asignado;
        $lote->Liberado = $liberado;
        $lote->save();

        $temp = CodigoParametros::find($res->idCodigo);
        $temp->Resultado = "";
        $temp->Resultado2 = "";
        $temp->Liberado = 0;
        $temp->save();




        $data = array(

            'idCodigo' => $res->idCodigo,
            'model' => $model,

        );

        return response()->json($data);
    }

    public function reasignarMuestra(Request $res)
    {
        $msg = "";
        try {
            $detalle = DB::table('viewcodigoinforme')->where('Id_codigo', $res->idCodigo)->first();

            if (!$detalle) {
                return response()->json(['msg' => 'No se encontró el detalle con ese Id_codigo']);
            }
            switch ($detalle->Id_area) {
                case 2:
                    $model = DB::table('lote_detalle')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalle::where('Id_lote', $detalle->Id_lote)->get();
                    $liberado = LoteDetalle::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                    break;

                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $model = DB::table('lote_detalle_espectro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->get();
                    $liberado = LoteDetalleEspectro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                    break;
                case 13: // G&A
                    $model = DB::table('lote_detalle_ga')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->get();
                    $liberado = LoteDetalleGA::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                    break;
                case 15: //Solidos
                    $model = DB::table('lote_detalle_solidos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->get();
                    $liberado = LoteDetalleSolidos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                    break;
                case 14: //volumetria
                    switch ($detalle->Id_parametro) {
                        case 6:
                        case 161:
                            $model = DB::table('lote_detalle_dqo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDqo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 33:
                        case 218:
                        case 119:
                        case 64:
                            $model = DB::table('lote_detalle_cloro')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleCloro::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 9:
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $model = DB::table('lote_detalle_nitrogeno')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleNitrogeno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 28:
                        case 29:
                        case 30:
                        case 27:
                            $model = DB::table('lote_detalle_alcalinidad')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleAlcalinidad::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $model = DB::table('lote_detalle_directos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: // Directos
                    switch ($detalle->Id_parametro) {
                        case 102:
                            $model = DB::table('lote_detalle_color')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleColor::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                        case 14:
                            $model = DB::table('lote_detalle_directos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();

                            break;
                        default:
                            $model = DB::table('lote_detalle_directos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->get()->count();
                            $liberado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get()->count();
                            break;
                    }
                    break;
                case 8: //Potable
                    switch ($detalle->Id_parametro) {
                        case 77: //Dureza
                        case 103:
                        case 251:
                        case 252:
                            $model = DB::table('lote_detalle_dureza')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDureza::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $model = DB::table('lote_detalle_potable')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetallePotable::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 6: // Mb
                case 12:
                case 3:
                    switch ($detalle->Id_parametro) {
                        case 135: // Coliformes fecales
                        case 132:
                        case 133:
                        case 12:
                        case 134: // termotolerantes
                        case 35:
                        case 51: // Coliformes totales
                        case 137:
                        case 350:
                            $model = DB::table('lote_detalle_coliformes')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleColiformes::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 253: //todo  ENTEROCOCO FECAL
                            $model = DB::table('lote_detalle_enterococos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleEnterococos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        case 71:
                            $model = DB::table('lote_detalle_dbo')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDbo::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 70:
                            $model = DB::table('lote_detalle_dboino')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDboIno::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 16: //todo Huevos de Helminto 
                            $model = DB::table('lote_detalle_hh')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleHH::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        case 78:
                            $model = DB::table('lote_detalle_ecoli')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleEcoli::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $model = DB::table('lote_detalle_directos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                            $asignado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->get();
                            $liberado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                default:
                    $model = DB::table('lote_detalle_directos')->where('Id_analisis', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)->delete();
                    $asignado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->get();
                    $liberado = LoteDetalleDirectos::where('Id_lote', $detalle->Id_lote)->where('Liberado', 1)->get();
                    break;
            }

            $asignado = DB::table('codigo_parametro')
                ->where('Id_lote', $detalle->Id_lote)
                ->where('Asignado', 1)
                ->count();

            $liberado = DB::table('codigo_parametro')
                ->where('Id_lote', $detalle->Id_lote)
                ->where('Liberado', 1)
                ->count();

            $lote = LoteAnalisis::where('Id_lote', $detalle->Id_lote)->first();
            if ($lote) {
                $lote->Asignado = $asignado;
                $lote->Liberado = $liberado;
                $lote->save();
            }

            DB::table('codigo_parametro')
                ->where('Id_solicitud', $detalle->Id_solicitud)->where('Id_parametro', $detalle->Id_parametro)
                ->update([
                    'Asignado' => 0,
                    'Resultado' => "",
                    'Resultado2' => "",
                    'Liberado' => 0,
                    'Id_lote' => null,
                ]);

            $msg = "Muestra Reasignada correctamente";
        } catch (\Throwable $th) {
            $msg = "Error: " . $th->getMessage();
        }

        return response()->json(['msg' => $msg]);
    }


    public function desactivarMuestra(Request $res)
    {
        $codigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $model = CodigoParametros::where('Id_codigo', $res->idCodigo)->first();
        $model->Cadena = 0;
        $model->Reporte = 0;
        $model->Mensual = 0;
        $model->save();

        $data = array(
            "model" => $model,

        );
        return response()->json($data);
    }

    public function getDetalleAnalisis(Request $res)
    {
        $aux = 0;

        $model = array();
        $solModel = Solicitud::where('Id_solicitud', $res->idSol)->first();
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_parametro) {
            case 28:
            case 29:
            case 30:
                $model = DB::table('viewlotedetallealcalinidad')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)->get();
                break;

            case 102:
                $model = LoteDetalleColor::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            // Metales
            case 17: // Arsenico
            case 231:
            case 208:
            case 207:
            case 20: // Cobre
            case 22: //Mercurio
            case 215:
            case 25: //Zinc
            case 227:
            case 24: //Plomo
            case 216:
            case 21: //Cromoa
            case 264:
            case 18: //Cadmio
            case 210:
            case 300: //Niquel
            case 233: // Seleneio
            case 213: //Fierro 
            case 197:
            case 188:
            case 189:
            case 190:
            case 191:
            case 192:
            case 194:
            case 195:
            case 196:
            case 204:
            case 219:
            case 230:
            case 23:
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)
                    ->get();

                break;
            case 15: // fosforo
            case 19: // Cianuros
            case 7: //Nitrats 
            case 8: //Nitritos
            case 152: //Cot
            case 99: //Cianuros 127
            case 105: //floururos 127
            case 106:
            case 107:
            case 96:
            case 95: // Sulfatos
            case 87:
            case 222:
            case 79:
            case 80:
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                break;
            case 11:
                $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 83)->first();
                $aux = DB::table('ViewLoteDetalleEspectro')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case 6:
                $model = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)->get();
                break;
            case 9:
            case 10:
            case 108:
                $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 83:
                $model = DB::table('ViewLoteDetalleNitrogeno')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 9)
                    ->where('Id_control', 1)
                    ->get();
                $aux = DB::table('ViewLoteDetalleNitrogeno')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 10)
                    ->where('Id_control', 1)
                    ->get();
                break;
            // case 218: //Cloro
            case 64:
                $model = DB::table('ViewLoteDetalleCloro')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 64)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case 358:
                $model = DB::table('campo_compuesto')
                    ->where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->get();
                break;
            case "13": // Grasas y Aceites
                // $model = DB::table('ViewLoteDetalleGA')
                //     ->where('Id_analisis', $codigoModel->Id_solicitud)
                //     ->where('Id_control', 1)->get();
                $model = DB::table('ViewLoteDetalleGA')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)->Where('Cancelado',0)
                    ->groupBy('Id_detalle', 'Id_analisis', 'Id_control') // Incluye todas las columnas relevantes
                    ->get();



                if ($solModel->Num_tomas > 1) {
                    $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();

                    $sumGasto = 0;
                    $aux = array();
                    foreach ($gasto as $item) {
                        $sumGasto = $sumGasto + $item->Promedio;
                    }
                    foreach ($gasto as $item) {
                        array_push($aux, ($item->Promedio / $sumGasto));
                    }
                } else {
                    //    $model = DB::table('ViewLoteDetalleGA')
                    //         ->where('Id_analisis', $codigoModel->Id_solicitud)
                    //         ->where('Id_control', 1)->get();
                    $model = DB::table('ViewLoteDetalleGA')
                        ->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)
                        ->groupBy('Id_detalle', 'Id_analisis', 'Id_control') // Incluye todas las columnas relevantes
                        ->get();
                }

                break;
            case 5:
            case 71:
                // $model = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_control', 1)
                //     ->where('Id_parametro', $codigoModel->Id_parametro)->get();

                $model = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_control', 1)->where('Id_parametro', $codigoModel->Id_parametro)->groupBy('Id_detalle')->get();

                $name = "LoteDetalleDbo";
                break;
            case 70:
                $model = DB::table('viewlotedetalledboino')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                $name = "LoteDetalleDbo";
                break;
            case 12:
            case 134:
            case 133:
            case 137:
            case 51:
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();

                break;
            case 253:
                if ($solModel->Id_norma == 27) {
                    $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->get();
                    $sumGasto = 0;
                    $aux = array();
                    foreach ($gasto as $item) {
                        $sumGasto = $sumGasto + $item->Promedio;
                    }
                    foreach ($gasto as $item) {
                        array_push($aux, ($item->Promedio / $sumGasto));
                    }
                }
                $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 35:
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 16:
                $model = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 78:
                $model = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 3: // Solidos
            case 4:
            case 112:
            case 90:
                // $model = DB::table('ViewLoteDetalleSolidos')->where('Id_analisis', $codigoModel->Id_solicitud)
                //     ->where('Id_control', 1)
                //     ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                $model = DB::table('ViewLoteDetalleSolidos')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->groupBy('Id_detalle') // Agrupa por el campo que deseas que sea único
                    ->get();



                break;
            case 26: //Gasto
                if ($solModel->Id_servicio != 3) {
                    $model = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                } else {
                    $model = LoteDetalleDirectos::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $paraModel->Id_parametro)->get();
                }

                break;
            case "67": //Conductividad
            case "68":
                if ($solModel->Id_norma == 27) {
                    if ($solModel->Num_toma > 1) {
                        $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->get();
                        $sumGasto = 0;
                        $aux = array();
                        foreach ($gasto as $item) {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach ($gasto as $item) {
                            array_push($aux, ($item->Promedio / $sumGasto));
                        }
                    } else {
                        $model = ConductividadMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    }
                }
                if ($solModel->Id_servicio != 3) {
                    $model = ConductividadMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                } else {
                    $model = LoteDetalleDirectos::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $paraModel->Id_parametro)->get();
                }
                break;
            case "2": //Materia flotante
                if ($solModel->Id_servicio != 3) {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                } else {
                    $model = LoteDetalleDirectos::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $paraModel->Id_parametro)->get();
                }
                break;
            case 361: //Materia flotante
                $model = CampoCompuesto::where('Id_solicitud', $codigoModel->Id_solicitud)->get();
                break;

            case 14: //ph
            case 110:
                if ($solModel->Id_norma == 27) {
                    if ($solModel->Num_toma > 1) {
                        $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->get();
                        $sumGasto = 0;
                        $aux = array();
                        foreach ($gasto as $item) {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach ($gasto as $item) {
                            array_push($aux, ($item->Promedio / $sumGasto));
                        }
                        $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    } else {
                        $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    }
                }
                if ($solModel->Id_servicio != 3) {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                } else {
                    $model = LoteDetalleDirectos::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $paraModel->Id_parametro)->where('Id_control', 1)->get();
                }
                break;
            case "97": //Temperatura
                if ($solModel->Id_servicio != 3) {
                    if ($solModel->Id_norma == 27) {
                        $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->get();
                        $sumGasto = 0;
                        $aux = array();
                        foreach ($gasto as $item) {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach ($gasto as $item) {
                            array_push($aux, ($item->Promedio / $sumGasto));
                        }
                    }
                    $model = TemperaturaMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();
                } else {
                    $model = LoteDetalleDirectos::where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_parametro', $paraModel->Id_parametro)->get();
                }
                break;

            //Potable
            case 95: // Sulfatos
            case 116:
                $model = DB::table('ViewLoteDetallePotable')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            //Dureza
            case 77:
            case 251:
            case 252:
            case 119:
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $codigoModel->Id_solicitud)->where('Id_control', 1)->where('Id_parametro', $paraModel->Id_parametro)->get();
                break;
            case 103:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 66: // Color verdadero
            case 65:
            case 98: // Turbiedad
            case 89: // Turbiedad
            case 218: //Cloro
            case 84: // Olor
            case 86: // Sabor
            case 365:
            case 370:
            case 372:
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 114:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 69:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 173:
                $model = DB::table('ViewLoteDetalleVidrio')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            default:
                break;
        }
        $data = array(
            'solModel' => $solModel,
            'aux' => $aux,
            'paraModel' => $paraModel,
            'codigoModel' => $codigoModel,

            'model' => $model,

        );
        return response()->json($data);
    }
    public function regresarRes(Request $res)
    {
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalle::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            case 16:
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalleEspectro::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            default:
                # code... 
                $model = "Default";
                break;
        }
        $data = array(
            'paraModel' => $paraModel,
            'model' => $model,
        );
        return response()->json($data);
    }

    public function liberarSolicitud(Request $res)
    {
        $sw = false;
        $idsActualizados = [];
        // Convertir $liberado a booleano por que asi normal no jala 
        $liberado = filter_var($res->liberado, FILTER_VALIDATE_BOOLEAN);

        $solicitudes = Solicitud::where('Id_solicitud', $res->idSol)->orWhere('Hijo', $res->idSol)->get();

        if ($solicitudes->isNotEmpty()) {
            foreach ($solicitudes as $solicitud) {
                $solicitud->Liberado = $liberado ? 1 : 0;
                $solicitud->save();

                // Almacenar el ID de la solicitud actualizada
                $idsActualizados[] = $solicitud->Id_solicitud;
            }

            // Obtener los IDs de matraz y crisol relacionados
            $gaIds = LoteDetalleGA::whereIn('Id_analisis', $idsActualizados)->pluck('Id_matraz');
            $solIds = LoteDetalleSolidos::whereIn('Id_analisis', $idsActualizados)->pluck('Id_crisol');


            $gas = MatrazGA::whereIn('Id_matraz', $gaIds)->get();
            foreach ($gas as $matraz) {
                $matraz->Estado = $liberado = 0;
                $matraz->save();
            }


            $sols = CrisolesGA::whereIn('Id_matraz', $solIds)->get();
            foreach ($sols as $crisol) {
                $crisol->Estado = $liberado = 0;
                $crisol->save();
            }

            if ($liberado) {
                $sw = true;
            }
        }

        return response()->json([
            'sw' => $sw,
            'idsActualizados' => $idsActualizados,
            'matracesActualizados' => $gas,
            'crisolesActualizados' => $sols
        ]);
    }


    public function setHistorial(Request $res)
    {
        $solTemp = Solicitud::where('Id_solicitud', $res->idSol)->first();
        $sw = true;
        $model = CodigoParametros::where('Codigo', 'LIKE', '%' . $solTemp->Folio_servicio . '%')->get();
        foreach ($model as $item) {
            if ($res->historial == true) {
                $item->Historial = 1;
                $sw = true;
            } else {
                $item->Historial = 0;
                $sw = false;
            }
            $item->save();
        }

        $data = array(
            'sw' => $sw,
        );


        $model = ProcesoAnalisis::where('Folio', 'LIKE', '%' . $solTemp->Folio_servicio . '%')->get();
        foreach ($model as $item) {
            if ($res->historial == true) {
                $item->Historial_resultado = 1;
                $sw = true;
            } else {
                $item->Historial_resultado = 0;
                $sw = false;
            }
            $item->save();
        }

        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }


    public function actualizarHistorial(Request $request)
    {
        $idSol = $request->input('idSol');
        $historialValor = $request->input('historialValor');

        // Encuentra todos los registros que corresponden a la solicitud
        $parametros = CodigoParametros::where('Id_solicitud', $idSol)->get();

        // Actualiza el campo Historial según el valor recibido
        foreach ($parametros as $parametro) {
            $parametro->Historial = $historialValor;
            $parametro->save();
        }

        return response()->json(['success' => true]);
    }
    //sirve para saber en dbo que resukta es el correcto 
    public function sugerido(Request $request)
    {
        $id_codigo = $request->input('Id_codigo');
        $sugerido_sup = $request->input('sugerido_sup');
        $registro = LoteDetalleDbo::where('Id_codigo', $id_codigo)->first();

        if ($registro) {
            $registro->Sugerido_sup = $sugerido_sup;
            $registro->save();
            return response()->json(['success' => true, 'message' => 'Actualización exitosa']);
        } else {

            return response()->json(['success' => false, 'message' => 'No se encontró el registro']);
        }
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

    public function getHistorial(Request $res)
    {
        $idHistorial = array();
        $idsLotes = array();
        $fechaLote = array();
        $Codigohist = array();
        $parametrohist = array();
        $resultadoHist = array();
        $historialHist = array();

        $sw = 0;

        $codigo = CodigoParametros::where('Id_codigo', $res->idCodigo)->first();
        $solicitud = Solicitud::where('Id_solicitud', $codigo->Id_solicitud)->first();
        $punto = SolicitudPuntos::where('Id_solicitud', $codigo->Id_solicitud)->first();

        $histSol = Solicitud::where('Padre', 0)->where('Id_sucursal', $solicitud->Id_sucursal)->orderBy('Id_solicitud', 'DESC')->get();

        try {
            foreach ($histSol as $item) {
                $histPunto = SolicitudPuntos::where('Id_solicitud', $item->Id_solicitud)->where('Id_muestreo', $punto->Id_muestreo)->get();
                if ($histPunto->count()) {
                    $res = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->where('Liberado', 1)->get();
                    if ($res->count()) {
                        array_push($idHistorial, $item->Id_solicitud);
                        $sw++;
                    }
                }
                if ($sw == 3) {
                    break;
                }
            }
            for ($i = 0; $i < sizeof($histPunto); $i++) {
                $temp = DB::table('viewcodigoparametro')->where('Id_solicitud', $histPunto[$i]->Id_solicitud)->where('Id_parametro', $codigo->Id_parametro)->first();
                $tempLote = LoteAnalisis::where('Id_lote', @$temp->Id_lote)->first();

                array_push($idsLotes, $tempLote->Id_lote);
                array_push($fechaLote, @$tempLote->Fecha);
                array_push($parametrohist, @$temp->Parametro . "(" . @$temp->Tipo_formula . ")");
                array_push($Codigohist, @$temp->Codigo);
                array_push($resultadoHist, @$temp->Resultado2);
                array_push($historialHist, @$temp->Historial);
            }
        } catch (\Throwable $th) {
            $idsLotes = array();
            $fechaLote = array();
            $Codigohist = array();
            $parametrohist = array();
            $resultadoHist = array();
            $historialHist = array();
        }

        $data = array(
            'idsLotes' => $idsLotes,
            'fechaLote' => $fechaLote,
            'Codigohist' => $Codigohist,
            'resultadoHist' => $resultadoHist,
            'parametrohist' => $parametrohist,
            'historialHist' => $historialHist,
        );
        return response()->json($data);
    }
    public function liberarTodoCampo()
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
}
