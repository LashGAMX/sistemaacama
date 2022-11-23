<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetallePotable;
use App\Models\Parametro;
use App\Models\PlantillaPotable;
use App\Models\ValoracionDureza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PotableController extends Controller
{
    //
    public function lote()
    {
        $parametro = DB::table('ViewParametros')->where('Id_area', 8)->orWhere('Id_tecnica', 4)->get();
        return view('laboratorio.potable.lote', compact('parametro'));
    }
    public function getLote(request $res)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $res->id)->where('Fecha', $res->fecha)->get();
        if ($model->count()) {
            $sw = true;
        }
        $data = array(
            'sw' => $sw,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setLote(Request $res)
    {
        $model = LoteAnalisis::create([
            'Id_area' => 8,
            'Id_tecnica' => $res->id,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
        ]);
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function loteDetalle($id)
    {
        return view('laboratorio.potable.asignarMuestraLote', compact('id'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $lote = LoteAnalisis::find($request->idLote);
        $model = DB::table('ViewCodigoParametro')
            ->where('Id_parametro', $lote->Id_tecnica)
            ->where('Asignado', '!=', 1)
            ->get();
        $data = array(
            'model' => $model,
            'lote' => $lote,
        );
        return response()->json($data);
    }
    public function valoracionDureza(Request $res)
    {
        $result = (($res->d1 / $res->solA)  + ($res->d2 / $res->solA)  + ($res->d3 / $res->solA)) / 3;
        $model = ValoracionDureza::where('Id_lote', $res->idLote)->get();
        if ($model->count()) {
            $model = ValoracionDureza::where('Id_lote', $res->idLote)->first();
            $model->Solucion = $res->solA;
            $model->Disolucion1 = $res->d1;
            $model->Disolucion2 = $res->d2;
            $model->Disolucion3 = $res->d3;
            $model->Resultado = $result;
            $model->save();
        } else {
            $model = ValoracionDureza::create([
                'Id_lote' => $res->idLote,
                'Id_parametro' => $res->idParametro,
                'Solucion' => $res->solA,
                'Disolucion1' => $res->d1,
                'Disolucion2' => $res->d2,
                'Disolucion3' => $res->d3,
                'Resultado' => $result,
            ]);
        }
        $data = array(
            'res' => $result,
        );
        return response()->json($data);
    }
    public function getMuestraAsignada(Request $res)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $res->idLote)->first();
        switch ($loteModel->Id_tecnica) {
                //Dureza
            case 77:
            case 251:
            case 252:
            case 103:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $res->idLote)->where('Id_control', 1)->get();
                break;
            default:
                # code...
                $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $res->idLote)->where('Id_control', 1)->get();
                break;
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra1 
    public function delMuestraLote(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
                //Dureza
            case 77:
            case 251:
            case 252:
            case 103:
                $detModel = DB::table('lote_detalle_directos')->where('Id_detalle', $request->idDetalle)->delete();
                $detModel = LoteDetalleDureza::where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                $detModel = DB::table('lote_detalle_potable')->where('Id_detalle', $request->idDetalle)->delete();
                $detModel = LoteDetallePotable::where('Id_lote', $request->idLote)->get();
                break;
        }

        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();


        $solModel = CodigoParametros::where('Id_solicitud', $request->idSol)->where('Id_parametro', $request->idParametro)->first();
        $solModel->Asignado = 0;
        $solModel->save();

        $data = array(
            'idDetalle' => $request->idDetalle,
        );

        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $sw = true;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        switch ($paraModel->Id_parametro) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                $model = LoteDetalleDureza::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                    'Analizo' => 1,
                ]);
                $detModel = LoteDetalleDureza::where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                $model = LoteDetallePotable::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                    'Analizo' => 1,
                ]);
                $detModel = LoteDetallePotable::where('Id_lote', $request->idLote)->get();
                break;
        }

        $solModel = CodigoParametros::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();


        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idArea' => $paraModel->Id_area,
            'sw' => $sw,
            'model' => $paraModel,
        );
        return response()->json($data);
    }
    public function sendMuestrasLote(Request $res)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $res->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        switch ($paraModel->Id_parametro) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                for ($i = 0; $i < sizeof($res->idCodigos); $i++) {
                    $sol = CodigoParametros::where('Id_codigo', $res->idCodigos[$i])->first();
                    $model = LoteDetalleDureza::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $sol->Id_solicitud,
                        'Id_codigo' => $res->idCodigos[$i],
                        'Id_parametro' => $loteModel->Id_tecnica,
                        'Id_control' => 1,
                        'Analizo' => 1,
                    ]);
                    $solModel = CodigoParametros::find($sol->Id_codigo);
                    $solModel->Asignado = 1;
                    $solModel->save();
                }
                $detModel = LoteDetalleDureza::where('Id_lote', $res->idLote)->get();
                break;
            default:
                # code...
                for ($i = 0; $i < sizeof($res->idCodigos); $i++) {
                    $sol = CodigoParametros::where('Id_codigo', $res->idCodigos[$i])->first();
                    $model = LoteDetallePotable::create([
                        'Id_lote' => $res->idLote,
                        'Id_analisis' => $sol->Id_solicitud,
                        'Id_codigo' => $res->idCodigos[$i],
                        'Id_parametro' => $loteModel->Id_tecnica,
                        'Id_control' => 1,
                        'Analizo' => 1,
                    ]);
                    $solModel = CodigoParametros::find($sol->Id_codigo);
                    $solModel->Asignado = 1;
                    $solModel->save();
                }
                $detModel = LoteDetallePotable::where('Id_lote', $res->idLote)->get();
                break;
        }

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idArea' => $paraModel->Id_area,
            'sw' => $sw,
            'model' => $paraModel,
        );
        return response()->json($data);
    }
    public function captura()
    {
        $parametro = DB::table('ViewParametros')->where('Id_area', '=', 8)->get();


        $controlModel = ControlCalidad::all();
        return view('laboratorio.potable.captura', compact('parametro', 'controlModel'));
    }

    public function getDetallePotable(Request $res)
    {
        $d1 = "";
        $d2 = "";
        $valoracion = "";
        $detalle = array();
        switch ($res->formulaTipo) {
            case 77: //Dureza 
            case 103:
            case 251:
                $model = LoteDetalleDureza::where("Id_detalle", $res->idDetalle)->first();
                $valoracion = ValoracionDureza::where('Id_lote',$model->Id_lote)->first();
                break;
            case 252:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_detalle', $res->idDetalle)->first();
                $d1 = DB::table('ViewLoteDetalleDureza')->where('Codigo', $model->Folio_servicio)->where('Id_parametro', 251)->first();
                $d2 = DB::table('ViewLoteDetalleDureza')->where('Codigo', $model->Folio_servicio)->where('Id_parametro', 77)->first();
                break;
            default:
                # code...
                $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $res->idLote)->get();
                break;
        }
        $data = array(
            'model' => $model,
            'd1' => $d1,
            'd2' => $d2,
            'valoracion' => $valoracion,
        );
        return response()->json($data);
    }
    public function getLoteCapturaPotable(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $detalle = array();
        switch ($loteModel->Id_tecnica) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                $detalle = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                $detalle = DB::table('ViewLoteDetallePotable')->where('Id_lote', $request->idLote)->get();
                break;
        }

        $data = array(
            'detalle' => $detalle,
        );
        return response()->json($data);
    }
    public function operacion(Request $res)
    {
        $resultado = "";

        switch ($res->id) {
            case 77: //Dureza
            case 103:
            case 251:
                $resultado = (($res->edta * $res->conversion * $res->real) / $res->vol);
                $model = LoteDetalleDureza::find($res->idDetalle);
                $model->Resultado = $resultado;
                $model->Edta = $res->edta;
                $model->Ph_muestra = $res->ph;
                $model->Vol_muestra = $res->vol;
                $model->Factor_real = $res->real;
                $model->Factor_conversion = $res->conversion;
                $model->save();
                break;
            case 58:
            case 98:
                $resultado = (($res->lectura1 + $res->lectura2 + $res->lectura3) / 3);

                if ($resultado >= 0 || $resultado <= 1) {
                    $resultado = $resultado + 0.05;
                } else if ($resultado > 1 || $resultado <= 10) {
                    $resultado = $resultado + 0.1;
                } else if ($resultado > 10 || $resultado <= 40) {
                    $resultado = $resultado + 1;
                } else if ($resultado > 40 || $resultado <= 100) {
                    $resultado = $resultado + 5;
                } else if ($resultado > 100 || $resultado <= 400) {
                    $resultado = $resultado + 10;
                } else if ($resultado > 400 || $resultado <= 1000) {
                    $resultado = $resultado + 50;
                } else if ($resultado > 1000) {
                    $resultado = $resultado + 100;
                }

                $model = LoteDetallePotable::find($res->idDetalle);
                $model->Factor_dilucion = $res->dilucion;
                $model->Lectura1 = $res->lectura1;
                $model->Lectura2 = $res->lectura2;
                $model->Lectura3 = $res->lectura3;
                $model->Vol_muestra = $res->vol;
                $model->Promedio = $res->promedion;
                $model->Resultado = $resultado;
                $model->save();
                break;
            case 252:
                $resultado = ($res->durezaT - $res->durezaC);
                $model = LoteDetalleDureza::find($res->idDetalle);
                $model->Resultado = $resultado;
                $model->Factor_real = $res->durezaT;
                $model->Factor_conversion = $res->durezaC;
                $model->save();
                break;
            default:
                # code...
                $resultado = (($res->lectura1 + $res->lectura2 + $res->lectura3) / 3);
                $model = LoteDetallePotable::find($res->idDetalle);
                $model->Factor_dilucion = $res->dilucion;
                $model->Lectura1 = $res->lectura1;
                $model->Lectura2 = $res->lectura2;
                $model->Lectura3 = $res->lectura3;
                $model->Vol_muestra = $res->vol;
                $model->Promedio = $res->promedion;
                $model->Resultado = $resultado;
                $model->save();
                break;
        }
        $data = array(
            'resultado' => $resultado,
            'model' =>  $model,
        );
        return response()->json($data);
    }
    public function enviarObsGeneral(Request $res)
    {
        switch ($res->idParametro) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:

                $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->get();
                foreach ($model as $item) {
                    $update = LoteDetalleDureza::find($item->Id_detalle);
                    $update->Observacion = $res->observacion;
                    $update->save();
                }

                break;
            default:
                # code...

                break;
        }

        $data = array(
            'model' => $update
        );
        return response()->json($data);
    }
    public function updateObsMuestra(Request $res)
    {

        switch ($res->idParametro) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                $update = LoteDetalleDureza::find($res->idDetalle);
                $update->Observacion = $res->observacion;
                $update->save();
                break;
            default:
                # code...

                break;
        }


        $data = array(
            'model' => $update
        );
        return response()->json($data);
    }
    public function liberarMuestra(Request $res)
    {
        $sw = false;
        $mensaje = "";
        switch ($res->idParametro) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                $model = LoteDetalleDureza::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $temp = $model;
                $model = LoteDetalleDureza::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
            default:
                # code...
                $model = LoteDetallePotable::find($res->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $temp = $model;
                $model = LoteDetallePotable::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
                break;
        }

        $modelCod = CodigoParametros::find($temp->Id_codigo);
        $modelCod->Resultado = $temp->Resultado;
        $modelCod->Analizo = Auth::user()->id;
        $modelCod->save();

        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'sw' => $sw,
            'mensaje' => $mensaje,
        );
        return response()->json($data);
    }
    public function exportPdfPotable($idLote)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 31,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $plantilla = PlantillaPotable::where('Id_parametro', $lote->Id_tecnica)->first();
        switch ($lote->Id_tecnica) {
            case 77: //Dureza
            case 103:
            case 251:
            case 252:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_lote', $idLote)->get();
                // $textoProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.potable.durezaTotal.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.potable.durezaTotal.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.potable.durezaTotal.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 98:
                $model = DB::table('ViewLoteDetallePotable')->where('Id_lote', $idLote)->get();
                // $textoProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                $data = array(
                    'lote' => $lote,
                    'model' => $model,
                    'plantilla' => $plantilla
                );

                $htmlHeader = view('exports.laboratorio.potable.turbiedad.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.potable.turbiedad.bitacoraBody', $data);
                $htmlFooter = view('exports.laboratorio.potable.turbiedad.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            default:
                # code...
                break;
        }

        // var_dump($model);

        $mpdf->Output();
    }
}
