<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\Bitacoras;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\CurvaConstantes;
use App\Models\GrasasDetalle;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleEspectro;
use App\Models\Parametro;
use App\Models\PlantillaBitacora;
use App\Models\ProcesoAnalisis;
use App\Models\SolicitudPuntos;
use App\Models\User;
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
        if ($res->tipo == 13) {
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

                default:
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
                default:
                    $model = array();
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
                default:
                    $model = array();
                    break;
            }
        }

        $data = array(
            'model' => $model,
            'curva' => $curva,
            'lote' => $lote,
            'blanco' => $blanco,
        );
        return response()->json($data);
    }
    public function setDetalleMuestra(Request $res)
    {
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
                default:
                    $model = array();
                    break;
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleLote(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();

        $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
        }
        $data = array(
            'plantilla' => $plantilla,
            'lote' => $lote,
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
                default:
                    $model = array();
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
                default:
                    $model = array();
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
            default:
                $model = array();
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
            default:
                $model = array();
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
                    case 103:// Dureza potable
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
                    case 95:// Sulfatos
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
}
