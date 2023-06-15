<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\Bitacoras;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\CrisolesGA;
use App\Models\CurvaConstantes;
use App\Models\GrasasDetalle;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetalleSolidos;
use App\Models\MatrazGA;
use App\Models\Parametro;
use App\Models\PlantillaBitacora;
use App\Models\ProcesoAnalisis;
use App\Models\SolicitudPuntos;
use App\Models\User;
use App\Models\ValoracionCloro;
use App\Models\ValoracionDqo;
use App\Models\ValoracionNitrogeno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabAnalisisController extends Controller
{
    //
    public function captura()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $control = ControlCalidad::all();
        $data = array(
            'control' => $control,
            'model' => $parametro,
        );
        return view('laboratorio.analisis.captura', $data);
    }
    public function getPendientes(Request $res)
    {
        $model = array();
        $temp = array();
        $codigo = DB::table('ViewCodigoParametro')->where('Asignado', 0)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp, $item->Codigo);
                    array_push($temp, "(" . $item->Id_parametro . ") " . $item->Parametro);
                    array_push($temp, $item->Hora_recepcion);
                    array_push($model, $temp);
                    break;
                }
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getLote(Request $res)
    {
        if ($res->folio != "") {
            $temp = DB::table('ViewCodigoParametro')->where('Codigo', $res->folio)->where('Id_parametro', $res->id)->first();
            $model = DB::table('ViewLoteAnalisis')->where('Id_lote', $temp->Id_lote)->get();
        } else {
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLote(Request $res)
    {
        $parametro = Parametro::where('Id_parametro', $res->id)->first();
        $model = LoteAnalisis::create([
            'Id_area' => $parametro->Id_area,
            'Id_tecnica' => $res->id,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id,
        ]);
        if ($parametro->Id_parametro == 13) {
            GrasasDetalle::create([
                'Id_lote' => $model->Id_lote,
            ]);
        }
        $data = array(
            'model' => $model
        );
        return response()->json($data);
    }
    public function getMuestraSinAsignar(Request $res)
    {
        $folio = array();
        $norma = array();
        $punto = array();
        $fecha = array();
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->idLote)->first();
        if ($res->fecha != "") {
        } else {
            $model = DB::table('ViewCodigoParametro')->where('Asignado', 0)->where('Id_parametro', $res->idParametro)->get();
            for ($i = 0; $i < $model->count(); $i++) {
                $puntoModel = SolicitudPuntos::where('Id_solicitud', $model[$i]->Id_solicitud)->first();
                $proceso = ProcesoAnalisis::where('Id_solicitud', $model[$i]->Id_solicitud)->first();
                array_push($folio, $model[$i]->Codigo);
                array_push($norma, $model[$i]->Norma);
                array_push($punto, $puntoModel->Punto);
                array_push($fecha, $proceso->Hora_recepcion);
            }
        }

        $data = array(
            'model' => $model,
            'folio' => $folio,
            'norma' => $norma,
            'fecha' => $fecha,
            'punto' => $punto,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function setMuestraLote(Request $res)
    {
        // $lote = DB::table('ViewLoteAnalisis')->where('Id_lote',$res->idLote)->first();
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->first();

        for ($i = 0; $i < sizeof($res->codigos); $i++) {
            $model = CodigoParametros::where('Id_codigo', $res->codigos[$i])->first();
            $model->Id_lote = $res->idLote;
            $model->Asignado = 1;
            $model->save();
            switch ($lote->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($model->Id_parametro) {
                        case 152: // COT
                            $temp = LoteDetalleEspectro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Vol_muestra' => 40,
                                'Liberado' => 0,
                                'Analizo' => 1,
                            ]);
                            $tempModel = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $temp = LoteDetalleEspectro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Vol_muestra' => 50,
                                'Liberado' => 0,
                                'Analizo' => 1,
                            ]);
                            $tempModel = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 13: // G&A
                    $temp = LoteDetalleGA::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Vol_muestra' => 50,
                        'Liberado' => 0,
                        'Analizo' => 1,
                    ]);
                    $tempModel = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15://Solidos
                    $temp = LoteDetalleSolidos::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Vol_muestra' => 100,
                        'Factor_conversion' => 1000000,
                        'Liberado' => 0,
                        'Analizo' => 1,
                    ]);
                    $tempModel = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                    break;
                case 14://volumetria
                    switch ($model->Id_parametro) {
                        case 6:
                            $temp = LoteDetalleDqo::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 33:
                        case 218:
                        case 64:
                            $temp = LoteDetalleCloro::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleCloro::where('Id_lote', $res->idLote)->get();
                            break;
                        case 9:
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $temp = LoteDetalleNitrogeno::create([
                                'Id_lote' => $res->idLote,
                                'Id_analisis' => $model->Id_solicitud,
                                'Id_codigo' => $model->Id_codigo,
                                'Id_parametro' => $model->Id_parametro,
                                'Id_control' => 1,
                                'Analizo' => 1,
                                'Liberado' => 0,
                            ]);
                            $tempModel = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 7: //Campo
                case 19: // Directos
                    $temp = LoteDetalleDirectos::create([ 
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Analizo' => 1,
                        'Liberado' => 0,
                    ]);
                    $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
                default:
                    $temp = LoteDetalleDirectos::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $model->Id_solicitud,
                        'Id_codigo' => $model->Id_codigo,
                        'Id_parametro' => $model->Id_parametro,
                        'Id_control' => 1,
                        'Analizo' => 1,
                        'Liberado' => 0,
                    ]);
                    $tempModel = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
            } 
            $lote->Asignado = $tempModel->count();
            $lote->save();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getCapturaLote(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $res->idLote)->get();
                    break;
                    break;
                case 13: // G&A
                    $model = DB::table('ViewLoteDetalleGA')->where('Id_lote', $res->idLote)->get();
                    break;
                case 15: // Solidos
                    $model = DB::table('ViewLoteDetalleSolidos')->where('Id_lote', $res->idLote)->get();
                    break;
                case 14://Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                            $model = DB::table('ViewLoteDetalleDqo')->where('Id_lote', $res->idLote)->get();
                            break;
                        case 33: // Cloro
                        case 64:
                            $model = DB::table('ViewLoteDetalleCloro')->where('Id_lote', $res->idLote)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 7: // Campo
                case 19: //Directos
                    $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();
                    break;
                default:
                    $model = DB::table('ViewLoteDetalleDirectos')->where('Id_lote', $res->idLote)->get();
                    break;
            }
        } else {
            $model = array();
        }

        $data = array(
            'model' => $model,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function getDetalleMuestra(Request $res)
    {
        $model = array();
        $curva = array();
        $blanco = array();
        $valoracion = array();
        $dif1 = "Sin datos";
        $dif2 = "Sin datos";
        $nom1 = 'sin nombre';
        $nom2 = 'sin nombre';

        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    $fecha = new Carbon($lote[0]->Fecha);
                    $today = $fecha->toDateString();
                    $model = DB::table("ViewLoteDetalleEspectro")->where('Id_detalle', $res->id)->first();
                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
                        ->where('Id_parametro', $lote[0]->Id_tecnica)->first();
                    $blanco = DB::table("ViewLoteDetalleEspectro")->where('Id_codigo', $model->Id_codigo)->where('Id_control', 5)->first();
                    break;
                case 13: //G&A
                    $model = DB::table("ViewLoteDetalleGA")->where('Id_detalle', $res->id)->first();
                    break;
                case 15:// Solidos
                    $model = DB::table('ViewLoteDetalleSolidos')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                    switch ($lote[0]->Id_tecnica) {
                        case 112: // SDT 
                            $nom1 = "ST";
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 91)->first();
                            $nom2 = "SST";
                            $dif2 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 93)->first();
                            break;
                        case 44: // SDV
                            $nom1 = "STV";
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 49)->first();
                            $nom2 = "SSV";
                            $dif2 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 47)->first();
                            break;
                        case 43: // SDF
                            $nom1 = "SDT";
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 89)->first();
                            $nom2 = "SDV";
                            $dif2 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 45)->first();
                            break;
                        case 45: // SSF
                            $nom1 = "SST";
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 93)->first();
                            $nom2 = "SSV";
                            $dif2 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 47)->first();
                            break;
                        case 47: // STF
                            $nom1 = "ST";
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 91)->first();
                            $nom2 = "STV";
                            $dif2 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 49)->first();
                            break;
                        case 3: // SS
                            $dif1 = DB::table("ViewLoteDetalleSolidos")->where("Folio_servicio", $model->Folio_servicio)->where('Id_parametro', 3)->first();
                            break;
                        default:
                            $dif1 = "Sin datos";
                            $dif2 = "Sin datos";
                            $nom1 = 'sin nombre';
                            $nom2 = 'sin nombre';
                            break;
                    }
                    break;
                case 14://Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 218: // Cloro
                        case 33:
                        case 64:
                            $model = DB::table('ViewLoteDetalleCloro')->where('Id_detalle', $res->id)->first(); // Asi se hara con las otras
                            $valoracion = ValoracionCloro::where('Id_lote', $model->Id_lote)->first();
                            break;
                        case 6: // Dqo
                            $fecha = new Carbon($request->fechaAnalisis);
                            $today = $fecha->toDateString();
                            $model = DB::table("ViewLoteDetalleDqo")->where('Id_detalle', $res->id)->first();
                            if ($model->Tecnica == 1) {
                                if ($model->Tipo == 3) {
                                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)->where('Id_area', 16)->where('Id_parametro', 74)->first();
                                } else {
                                    $curva = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)->where('Id_area', 16)->where('Id_parametro', 75)->first();
                                }
                            } else {
                                $valoracion = ValoracionDqo::where('Id_lote', $model->Id_lote)->first();
                            }
                            break;
                        case 9: // Nitrogeno
                        case 287:
                        case 10:
                        case 11:
                        case 108:// Nitrogeno Amon
                            $model = DB::table("ViewLoteDetalleNitrogeno")->where('Id_detalle', $res->id)->first();
                            $valoracion = ValoracionNitrogeno::where('Id_lote', $model->Id_lote)->first();
                            break;
                        default: // Default Directos
                            // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                            break;
                    }
                    break; 
                case 7://Campo
                case 19://Directo
                    $model = DB::table("ViewLoteDetalleDirectos")->where('Id_detalle', $res->id)->first();
                    break;
                default:
                    $model = array();
                    break;
            }
        }

        $data = array(
            'model' => $model,
            'valoracion' => $valoracion,
            'curva' => $curva,
            'lote' => $lote,
            'blanco' => $blanco,
            'nom1' => $nom1,
            'nom2' => $nom2,
            'dif1' => $dif1,
            'dif2' => $dif2,
        );
        return response()->json($data);
    }
    public function setDetalleMuestra(Request $res)
    {
        $std = true;
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 152: // COT
                            $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $dilucion = 40 / $res->E;
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            switch ($model->Id_control) {
                                case 14: //estandar de verificación
                                    $resultado = ((($promedio - $res->CB) / $res->CM) * $dilucion);
                                    break;
                                case 5: // blanco
                                    $resultado = ($res->X + $res->Y + $res->Z) / 3;
                                    break;
                                default:
                                    $resultado = ((($promedio - $res->CA) / $res->CM) * $dilucion);
                            }
                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 69:
                            # Cromo Hexavalente
                            $d =  $res->CM;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $d;
                            $r2 = 100 / $res->E;
                            $resultado = $r1 * $r2;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 19:
                        case 99:
                            # Cianuros
                            $d = 500 * $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $rse->CM;
                            $resultado = ($r1 * 12500) / $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 114:
                        case 96:
                            # Sustancias activas al Azul de Metileno
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $r1 = ($x - $res->CB) / $res->CM;
                            $r2 = 1000 / $res->E;
                            $resultado = $r1 * $r2;
                            $d = 0;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 15:
                            # Fosforo-Total 
                            $d = 100 / $res->E;
                            $x = ($rse->X + $res->Y + $rse->Z) / 3;
                            $resultado = (($x - $rse->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 117:
                        case 222:
                            # Boro (B) 
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = (($x - $res->CB) / $res->CM) * 1;
                            $d = 0;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 7:
                            # N-Nitratos
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = (($x - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 8:
                        case 107:
                            # N-nitritos
                            $d = 50 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $resultado = ((($x - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 106:
                            # N-nitratos (potable)
                            $d = 10 / $res->E;
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $xround = round($x, 3);
                            $resultado = ((($xround - $res->CB) / $res->CM) * $d);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 103: //Dureza
                            $x = $res->A - $res->B;
                            $d = ($x * $res->RE) * 1000;
                            $resultado = $d / $res->D;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 105: //Fluoruros (potable)
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d =  50 / $res->E;
                            $xround = round($x, 3);
                            $resultado = (($xround - $res->CB) / $res->CM) * $d;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;

                        case 113:
                            // Sulfatos Residual
                            $x = ($res->X + $res->Y + $res->Z + $res->ABS4 + $res->ABS5 + $res->ABS6 + $res->ABS7 + $res->ABS8) / 8;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = $res2 * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->Abs4 = $res->ABS4;
                            $model->Abs5 = $res->ABS5;
                            $model->Abs6 = $res->ABS6;
                            $model->Abs7 = $res->ABS7;
                            $model->Abs8 = $res->ABS8;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 95:
                            // Sulfatos Potable
                            $x = ($res->X + $res->Y + $res->Z) / 3;
                            $d =   100  / $res->E;
                            $res1 = round($x, 3) - ($res->CB);
                            $res2 = $res1 / $res->CM;
                            $resultado = $res2 * round($d, 3);

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $x;
                            $model->Vol_dilucion = $d;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                        default:
                            $promedio = ($res->X + $res->Y + $res->Z) / 3;
                            $dilucion =  $res->E / $res->E;
                            $resultado = (($promedio - $res->CB) / $res->CM) * $dilucion;

                            $model = LoteDetalleEspectro::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Abs1 = $res->X;
                            $model->Abs2 = $res->Y;
                            $model->Abs3 = $res->Z;
                            $model->B = $res->CB;
                            $model->M = $res->CM;
                            $model->R = $res->CR;
                            $model->Promedio = $promedio;
                            $model->Vol_dilucion = $dilucion;
                            $model->Vol_muestra = $res->E;
                            $model->Blanco = $res->CA;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                            break;
                            break;
                    }
                    break;
                case 13://G&A
                    if ($res->R != '') {
                        $matraz = MatrazGA::where('Estado', 0)->get();
                        if ($matraz->count()) {
                            $mat = rand(0, $matraz->count());
                            $matraz[$mat]->Estado = 1;
                            $matraz[$mat]->save();
                        } else {
                            $std = false;
                        }
                
                        //$m3 = mt_rand($matraz->Min, $matraz->Max);
                        $dif = ($matraz[$mat]->Max - $matraz[$mat]->Min);
                        $ran = (round($dif, 4)) / 10;
                        $m3 = $matraz[$mat]->Max - $ran;
                
                
                        $mf = ((($res->R / $res->E) * $res->I) + $m3);
                        $m1 = ($m3 - 0.0002);
                        $m2 = ($m3 - 0.0001);
                
                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->Id_matraz = $matraz[$mat]->Id_matraz;
                        $model->Matraz = $matraz[$mat]->Num_serie;
                        $model->M_final = $mf;
                        $model->M_inicial1 = $m1;
                        $model->M_inicial2 = $m2;
                        $model->M_inicial3 = $m3;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = ($res->R - $res->G);
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    }else{
                        $res1 = $res->H - $res->C;
                        $res2 = $res1 / $res->I;
                        $res3 = $res2 * $res->E;
                        $resultado = $res3 - $res->G;
                
                        $matraz = MatrazGA::where('Num_serie', $res->P)->first(); 
                 
                        $model = LoteDetalleGA::find($res->idMuestra);
                        $model->M_final = $res->H; 
                        $model->Id_matraz = $matraz->Id_matraz;
                        $model->Matraz = $matraz->Num_serie;
                        $model->M_inicial1 = $res->J;
                        $model->M_inicial2 = $res->K;
                        $model->M_inicial3 = $res->C;
                        $model->Ph = $res->L;
                        $model->Blanco = $res->G;
                        $model->F_conversion = $res->E;
                        $model->Vol_muestra = $res->I;
                        $model->Resultado = $resultado;
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    }
    
                    break;
                case 15: // Solidos
                    switch ($lote[0]->Id_tecnica) {
                        case 3: // Directos
                    
                            break;
                        case 47: // Por diferencia
                        case 88:
                        case 44:
                        case 45:
                            $model = LoteDetalleSolidos::find($res->idMuestra);
                            $model->Resultado = $res->resultado;
                            $model->Masa1 = $res->val1;
                            $model->Masa2 = $res->val2;
                            $model->Analizo = Auth::user()->id;
                            $model->save();
                    
                            break;
                        default: // Default
                            if ($res->R != "") {

                                $modelCrisol = CrisolesGA::all();
                                //? Aplica la busqueda de crisol hasta encontrar un crisol desocupado
                                $cont = $modelCrisol->count();
                
                                for ($i = 0; $i < $cont; $i++) {
                                    # code...
                                    $id = rand(0, $modelCrisol->count());
                                    $crisol = CrisolesGA::where('Id_crisol', $id)->first();
                                    if ($crisol->Estado == 0) {
                                        break;
                                    }
                                }
                                
                                
                                $mf = ((($res->R / $res->factor) * $res->volumen) + $crisol->Peso);

                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Id_crisol = $crisol->Id_crisol;
                                $model->Crisol = $crisol->Num_serie;
                                $model->Masa1 = $crisol->Peso;
                                $model->Masa2 = $mf;
                                $model->Peso_muestra1 = ($crisol->Peso + 0.0001);
                                $model->Peso_muestra2 = ($crisol->Peso + 0.0002);
                                $model->Peso_constante1 = $mf + 0.0001;
                                $model->Peso_constante2 = $mf + 0.0002;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $res->R;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            } else {
                                $res1 = $res->masa2 - $res->masa1;
                                $res2 = $res1 / $res->volumen;
                                $resultado = $res2 * $res->factor;
                        
                        
                                $model = LoteDetalleSolidos::find($res->idMuestra);
                                $model->Crisol = $res->crisol;
                                $model->Masa1 = $res->masa1;
                                $model->Masa2 = $res->masa2;
                                $model->Peso_muestra1 = $res->pesoConMuestra1;
                                $model->Peso_muestra2 = $res->pesoConMuestra2;
                                $model->Peso_constante1 = $res->pesoC1;
                                $model->Peso_constante2 = $res->pesoC2;
                                $model->Vol_muestra = $res->volumen;
                                $model->Factor_conversion = $res->factor;
                                $model->Resultado = $resultado;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                        
                            }
                            
                            break;
                    } 
                    break;
                case 14: // volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                            $x = 0;
                            $d = 0;
                            if ($res->sw == 1) {
                                $res1 = ($res->CA - $res->B);
                                $res2 = ($res1 * $res->C);
                                $res3 = ($res2 * $res->D);
                                $resultado = ($res3 / $res->E);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Titulo_muestra = $res->B;
                                $model->Molaridad = $res->C;
                                $model->Titulo_blanco = $res->CA;
                                $model->Equivalencia = $res->D;
                                $model->Vol_muestra = $res->E;
                                $model->Resultado = $resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            } else {
                                $d = 2 / $res->E;
                                $x = ($res->X + $res->Y + $res->Z) / 3;
                                $resultado = ((($x - $res->CB) / $res->CM) * $d);

                                $model = LoteDetalleDqo::find($res->idMuestra);
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs_prom = $res->ABS;
                                $model->Blanco = $res->CA;
                                $model->Factor_dilucion = $res->D;
                                $model->Vol_muestra = $res->Vol_muestra;
                                $model->Abs1 = $res;
                                $model->Abs2 = $res->Y;
                                $model->Abs3 = $res->Z;
                                $model->Resultado = $res->resultado;
                                $model->Tecnica = $res->radio;
                                $model->Analizo = Auth::user()->id;
                                $model->save();
                            }
                    
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $resultado = $res3 / $res->E;

                            $model = LoteDetalleCloro::find($res->idMuestra);
                            $model->Vol_muestra = $res->A;
                            $model->Ml_muestra = $res->E;
                            $model->Vol_blanco = $res->B;
                            $model->Normalidad = $res->C;
                            $model->Ph_inicial = $res->G;
                            $model->Ph_final = $res->H;
                            $model->Factor_conversion = $res->D;
                            $model->Resultado = round($resultado);
                            $model->analizo = Auth::user()->id;
                            $model->save();
                    
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                            $res1 = $res->A - $res->B;
                            $res2 = $res1 * $res->C;
                            $res3 = $res2 * $res->D;
                            $res4 = $res3 * $res->E;
                            $resultado = $res4 / $res->G;
                    
                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A;
                            $model->Titulado_blanco = $res->B;
                            $model->Molaridad = $res->C;
                            $model->Factor_equivalencia = $res->D;
                            $model->Factor_conversion = $res->E;
                            $model->Vol_muestra = $res->G;
                            $model->Resultado = $resultado;
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        case 108:
                            $a = $res->A * $res->B;
                            $d = 100 + $res->D;
                            $c = 100 + $res->C;

                            $resultado = $a * ($d / $c);

                            $model = LoteDetalleNitrogeno::find($res->idMuestra);
                            $model->Titulado_muestra = $res->A; //Facor de dilución
                            $model->Titulado_blanco = $res->B; //Concentracion de NH3 en mg/L
                            $model->Molaridad = $res->C; //Volumen Añadido al std
                            $model->Factor_equivalencia = $res->D; //Volumen añadido a la muestra
                            $model->Vol_muestra = $res->V; //Volumen de la muestra en mL
                            $model->Resultado = $resultado; //Resultado
                            $model->Observacion = $res->O; //observacion
                            $model->analizo = Auth::user()->id;
                            $model->save();
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 7://Muestreo
                case 19://Directos
                    switch ($lote[0]->Id_tecnica) {
                        case 14:
                        case 110:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 1);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();
                    
                            break;
                        case 67:
                        case 68:
                            $resultado = "";
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 0);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Temperatura = $res->temp;
                            $model->Promedio = $res->promedio;
                            $model->save();
                    
                            break;
                        case 119:
                            $resultado = 0;
                            $dilusion = $res->dilucion;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio * $dilusion, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Factor_dilucion = $res->dilucion;
                            $model->Resultado = $resultado;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                            break;
                        case 98:
                            $resultado = 0;
                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 2);
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Factor_dilucion = $res->factor;
                            $model->Vol_muestra = $res->volumen;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $promedio;
                            $model->save();
                    
                            break;
                        case 97:
                        case 33:
                            $resultado = "";

                            $promedio = ($res->l1 + $res->l2 + $res->l3) / 3;
                            $resultado = round($promedio, 3);
                    
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Lectura1 = $res->l1;
                            $model->Lectura2 = $res->l2;
                            $model->Lectura3 = $res->l3;
                            $model->Promedio = $res->promedio;
                            $model->save();
                            break;
                        case 102:
                        case 66:
                        case 65:
                        case 120:
                            $resultado = 0;
                            //$factor = 0;
                            $dilusion = 50 / $res->volumen;
                            $promedio = ($res->aparente + $res->verdadero) * $res->dilusion;

                            $resultado = $promedio + $res->factor;
                    
                            $model = LoteDetalleDirectos::find($res->idMuestra);
                            $model->Resultado = $resultado;
                            $model->Color_a = $res->aparente;
                            $model->Color_v = $res->verdadero;
                            $model->Factor_dilucion = $dilusion;
                            $model->Vol_muestra = $res->volumen;
                            $model->Ph = $res->ph;
                            $model->Factor_correcion = $res->factor;
                            $model->save();
                            break;
                    default: // Default Directos
                        // tab += '<td><input hidden id="idMuestra' + item.Id_detalle + '" value="' + item.Id_detalle + '"><button '+status+' type="button" class="btn btn-'+color+'" onclick="getDetalleMuestra(' + item.Id_detalle + ',1);" data-toggle="modal" data-target="#modalCapturaSolidos">Capturar</button>';
                        break;
                }
                    break;
                default:
                    $model = array();
                    break;
            }
        }
        $data = array(
            'model' => $model,
            'std' => $std,
        );
        return response()->json($data);
    }
    public function getDetalleLote(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->get();
        $model = array();

        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos

                    break;
                case 13: //G&A
                    $model = GrasasDetalle::where('Id_lote', $res->id)->first();
                    break;
                default:
                    $model = array();
                    break;
            }
        }

        $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote[0]->Id_tecnica)->get();
        }
        $data = array(
            'model' => $model,
            'plantilla' => $plantilla,
            'lote' => $lote[0],
        );
        return response()->json($data);
    }
    public function setControlCalidad(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 0:

                            break;
                        default:
                            $muestra = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = "";
                            $model->Liberado = 0;
                            $model->save();

                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 13://G&A
                        $muestra = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                        $model = $muestra->replicate();
                        $model->Id_control = $res->idControl;
                        $model->Resultado = NULL;
                        $model->Liberado = 0;
                        $model->save(); 

                        $model = LoteDetalleGA::where('Id_lote', $res->idLote)->get();
                    break;
                case 15://Solidos
                    $muestra = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->get();
                    break;
                case 14://Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                            $muestra = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();
        
                            $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->get();
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:
                            $muestra = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();
        
                            $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:
                            $muestra = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();
        
                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->get();
                            break;
                        default:
                            
                            $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                            $model = $muestra->replicate();
                            $model->Id_control = $res->idControl;
                            $model->Resultado = NULL;
                            $model->Liberado = 0;
                            $model->save();
        
                            $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                            break;
                    }
                    break;
                case 7://Campo
                case 19://Directos
                    $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                    $model = $muestra->replicate();
                    $model->Id_control = $res->idControl;
                    $model->Resultado = NULL;
                    $model->Liberado = 0;
                    $model->save();

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
                default:
                $muestra = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                $model = $muestra->replicate();
                $model->Id_control = $res->idControl;
                $model->Resultado = NULL;
                $model->Liberado = 0;
                $model->save();

                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->get();
                    break;
            }
        }

        $lote = LoteAnalisis::find($res->idLote);
        $lote->Asignado = $model->count();
        $lote->save();

        $data = array(
            'lote' => $lote,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLiberarTodo(Request $res)
    {
        $sw = false;
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        if ($lote->count()) {
            switch ($lote[0]->Id_area) {
                case 16: // Espectrofotometria
                case 5: // Fisicoquimicos
                    switch ($lote[0]->Id_tecnica) {
                        case 0:

                            break;
                        default:
                            $muestras = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleEspectro::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
                            $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 13:// G&A
                    $muestras = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleGA::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
                    $model = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
                case 15://Solidos
                    $muestras = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleSolidos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
                    $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
                case 14://Volumetria
                    switch ($lote[0]->Id_tecnica) {
                        case 6: // Dqo
                            $muestras = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDqo::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
    
                            $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 33: // Cloro
                        case 218:
                        case 64:

                            $muestras = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleCloro::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
    
                            $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        case 9: // Nitrogeno
                        case 10:
                        case 11:
                        case 287:
                        case 83:
                        case 108:

                            $muestras = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleNitrogeno::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
    
                            $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                        default:
                            $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                            foreach ($muestras as $item) {
                                $model = LoteDetalleDirectos::find($item->Id_detalle);
                                $model->Liberado = 1;
                                if ($model->Resultado != null) {
                                    $sw = true;
                                    $model->save();
                                }
                                if ($item->Id_control == 1) {
                                    $modelCod = CodigoParametros::find($model->Id_codigo);
                                    $modelCod->Resultado = $model->Resultado;
                                    $modelCod->Resultado2 = $model->Resultado;
                                    $modelCod->Analizo = Auth::user()->id;
                                    $modelCod->save();
                                }
                            }
            
                            $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                            break;
                    }
                    break;
                case 7://Campo
                case 19://Directos

                    $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleDirectos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }
    
                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
                default:
                    $muestras = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 0)->get();
                    foreach ($muestras as $item) {
                        $model = LoteDetalleDirectos::find($item->Id_detalle);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }
                        if ($item->Id_control == 1) {
                            $modelCod = CodigoParametros::find($model->Id_codigo);
                            $modelCod->Resultado = $model->Resultado;
                            $modelCod->Resultado2 = $model->Resultado;
                            $modelCod->Analizo = Auth::user()->id;
                            $modelCod->save();
                        }
                    }

                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                    break;
            }
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setLiberar(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:

                        break;
                    default:
                        $model = LoteDetalleEspectro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        $modelCod = CodigoParametros::find($model->Id_codigo);
                        $modelCod->Resultado = $model->Resultado;
                        $modelCod->Resultado2 = $model->Resultado;
                        $modelCod->Analizo = Auth::user()->id;
                        $modelCod->save();

                        $model = LoteDetalleEspectro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            case 13: // G&A
                $model = LoteDetalleGA::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Resultado2 = $model->Resultado;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();

                $model = LoteDetalleGA::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            case 15://Solidos
                $model = LoteDetalleSolidos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Resultado2 = $model->Resultado;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();

                $model = LoteDetalleSolidos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            case 14://Volumetria
                switch ($lote[0]->Id_tecnica) {
                    case 6: // Dqo
                        $model = LoteDetalleDqo::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        $modelCod = CodigoParametros::find($model->Id_codigo);
                        $modelCod->Resultado = $model->Resultado;
                        $modelCod->Resultado2 = $model->Resultado;
                        $modelCod->Analizo = Auth::user()->id;
                        $modelCod->save();

                        $model = LoteDetalleDqo::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 33: // Cloro
                    case 218:
                    case 64:
                        $model = LoteDetalleCloro::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        $modelCod = CodigoParametros::find($model->Id_codigo);
                        $modelCod->Resultado = $model->Resultado;
                        $modelCod->Resultado2 = $model->Resultado;
                        $modelCod->Analizo = Auth::user()->id;
                        $modelCod->save();

                        $model = LoteDetalleCloro::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    case 9: // Nitrogeno
                    case 10:
                    case 11:
                    case 287:
                    case 83:
                    case 108:
                        $model = LoteDetalleNitrogeno::find($res->idMuestra);
                        $model->Liberado = 1;
                        if ($model->Resultado != null) {
                            $sw = true;
                            $model->save();
                        }

                        $modelCod = CodigoParametros::find($model->Id_codigo);
                        $modelCod->Resultado = $model->Resultado;
                        $modelCod->Resultado2 = $model->Resultado;
                        $modelCod->Analizo = Auth::user()->id;
                        $modelCod->save();

                        $model = LoteDetalleNitrogeno::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                    default:
                    $model = LoteDetalleDirectos::find($res->idMuestra);
                    $model->Liberado = 1;
                    if ($model->Resultado != null) {
                        $sw = true;
                        $model->save();
                    }
    
                    $modelCod = CodigoParametros::find($model->Id_codigo);
                    $modelCod->Resultado = $model->Resultado;
                    $modelCod->Resultado2 = $model->Resultado;
                    $modelCod->Analizo = Auth::user()->id;
                    $modelCod->save();
    
                    $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                        break;
                }
                break;
            case 7://Campo
            case 19://directos
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Resultado2 = $model->Resultado;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();

                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            default:
                $model = LoteDetalleDirectos::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }

                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Resultado2 = $model->Resultado;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();

                $model = LoteDetalleDirectos::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();

        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setObservacion(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote', $res->idLote)->get();
        switch ($lote[0]->Id_area) {
            case 16: // Espectrofotometria 
            case 5: // Fisicoquimicos
                switch ($lote[0]->Id_tecnica) {
                    case 0:
                        break;
                    default:
                        $model = LoteDetalleEspectro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                }
                break;
            case 13://G&A
                    $model = LoteDetalleGA::where('Id_detalle', $res->idMuestra)->first();
                    $model->Observacion = $res->observacion;
                    $model->save();
                break;
            case 15://Solidos
                    $model = LoteDetalleSolidos::where('Id_detalle', $res->idMuestra)->first();
                    $model->Observacion = $res->observacion;
                    $model->save();
                break;
            case 14://Volumetria
                switch ($lote[0]->Id_tecnica) {
                    case 218: // Cloro
                    case 33:
                    case 64:
                        $model = LoteDetalleCloro::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 6: // Dqo
                        $model = LoteDetalleDqo::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    case 9: // Nitrogeno
                    case 287:
                    case 10:
                    case 11:
                    case 108:// Nitrogeno Amon
                        $model = LoteDetalleNitrogeno::where('Id_detalle', $res->idMuestra)->first();
                        $model->Observacion = $res->observacion;
                        $model->save();
                        break;
                    default: // Default Directos
                    $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                    $model->Observacion = $res->observacion;
                    $model->save();
                        break;
                }
                break;
            case 7://Campo
            case 19://Directos
                $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
                $model->Observacion = $res->observacion;
                $model->save();
                break;
            default:
            $model = LoteDetalleDirectos::where('Id_detalle', $res->idMuestra)->first();
            $model->Observacion = $res->observacion;
            $model->save();
                break;
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setBitacora(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $temp = Bitacoras::where('Id_lote', $res->id)->get();
        if ($temp->count()) {
            $model = Bitacoras::where('Id_lote', $res->id)->first();
            $model->Titulo = $res->titulo;
            $model->Texto = $res->texto;
            $model->Rev = $res->rev;
            $model->save();
        } else {
            $model = Bitacoras::create([
                'Id_lote' => $res->id,
                'Id_parametro' => $lote->Id_tecnica,
                'Titulo' => $res->titulo,
                'Texto' => $res->texto,
                'Rev' => $res->rev,
            ]);
        }
        $data = array(
            'lote' => $lote,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function exportBitacora($id)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;
        $mpdf->CSSselectMedia = 'mpdf';

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $id)->first();
        switch ($lote->Id_area) {
            case 16: // Espectrofotometria
            case 5: // Fisicoquimicos
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $id)->get();
                $plantilla = Bitacoras::where('Id_lote', $id)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                $curva = CurvaConstantes::where('Id_parametro', $lote->Id_tecnica)->where('Fecha_inicio', '<=', $lote->Fecha)->where('Fecha_fin', '>=', $lote->Fecha)->first();
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $id)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    $analizo = User::where('id', $model[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'curva' => $curva,
                    'plantilla' => $plantilla,
                    'procedimiento' => $procedimiento,
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );

                switch ($lote->Id_tecnica) {
                    case 152: // COT
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cot.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cot.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cot.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 103: // Dureza potable
                        // $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.capturaFooter', $data);
                        $htmlHeader = view('exports.laboratorio.potable.durezaTotal.127.bitacoraHeader', $data);
                        $htmlCaptura = view('exports.laboratorio.potable.durezaTotal.127.bitacoraBody', $data);
                        // $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 7: //Nitratos residual
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitratos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 8: //Nitritos residual
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitritos.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitritos.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 107: // Nitritos Potable
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitritos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitritos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitritos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 106: // Nitritos Potable
                        $htmlFooter = view('exports.laboratorio.fq.espectro.nitratos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.nitratos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.nitratos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 99: //cianuros
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cianuros.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cianuros.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 95: // Sulfatos
                        $htmlFooter = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.sulfatos.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 105: // Fluoruros
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fluoruros.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 96: // SAAM 
                    case 114:
                        $htmlFooter = view('exports.laboratorio.fq.espectro.saam.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.saam.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.saam.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 116: // Yodo
                        $htmlFooter = view('exports.laboratorio.fq.espectro.yodo.127.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.yodo.127.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.yodo.127.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 69: //Cromo Hexa
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cromoHex.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cromoHex.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 19: // Cianuros
                        $htmlFooter = view('exports.laboratorio.fq.espectro.cianuros.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.cianuros.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.cianuros.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    case 15: // Fosforo
                        $htmlFooter = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaFooter', $data);
                        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                        $htmlHeader = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaHeader', $data);
                        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                        $htmlCaptura = view('exports.laboratorio.fq.espectro.fosforoTotal.capturaBody', $data);
                        $mpdf->CSSselectMedia = 'mpdf';
                        $mpdf->WriteHTML($htmlCaptura);
                        break;
                    default:

                        break;
                }
                break;
            default:
                break;
        }
        $mpdf->Output();
    }
    public function setDetalleGrasas(Request $request)
    {
        $model = GrasasDetalle::where('Id_lote', $request->id)->first();
        $model->Calentamiento_temp1 = $request->temp1;
        $model->Calentamiento_entrada1 = $request->entrada1;
        $model->Calentamiento_salida1 = $request->salida1;
        $model->Calentamiento_temp2 = $request->temp2;
        $model->Calentamiento_entrada2 = $request->entrada2;
        $model->Calentamiento_salida2 = $request->salida2;
        $model->Calentamiento_temp3 = $request->temp3;
        $model->Calentamiento_entrada3 = $request->entrada3;
        $model->Calentamiento_salida3 = $request->salida3;
        $model->Enfriado_entrada1 = $request->dosentrada1;
        $model->Enfriado_salida1 = $request->dosalida1;
        $model->Enfriado_pesado1 = $request->dospesado1;
        $model->Enfriado_entrada2 = $request->dosentrada2;
        $model->Enfriado_salida2 = $request->dosalida2;
        $model->Enfriado_pesado2 = $request->dospesado2;
        $model->Enfriado_entrada3 = $request->dosentrada3;
        $model->Enfriado_salida3 = $request->dosalida3;
        $model->Enfriado_pesado3 = $request->dospesado3;
        $model->Secado_temp = $request->trestemperatura;
        $model->Secado_entrada = $request->tresentrada;
        $model->Secado_salida = $request->tressalida;
        $model->Reflujo_entrada = $request->cuatroentrada;
        $model->Reflujo_salida = $request->cuatrosalida;
        $model->Enfriado_matraces_entrada = $request->cincoentrada;
        $model->Enfriado_matraces_salida = $request->cincosalida;
        $model->save();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
}
