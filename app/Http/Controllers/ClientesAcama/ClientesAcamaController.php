<?php

namespace App\Http\Controllers\ClientesAcama;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesAcamaController extends Controller
{
    //
    public function cadenacustodiainterna($code)
    {
        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
         Encripta el contenido de la variable, enviada como parametro.
          */
        $folioEncript = openssl_decrypt($code, $method, $clave, false, $iv);

        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $model = DB::table('ViewSolicitud2')->where('Folio_servicio', $folioEncript)->first();
        $idSol = $model->Id_solicitud;
        $norma = Norma::where('Id_norma', $model->Id_norma)->first(); 

        $areaParam = DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $idSol)->get();
        $phMuestra = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();

        $tempArea = array();
        $temp = 0;
        $sw = false;

        //Datos geerales
        $area = array();
        $responsable = array();
        $numRecipientes = array();
        $fechasSalidas = array();
        $stdArea = array();
        $firmas = array();
        foreach ($areaParam as $item) {
            for ($i = 0; $i < sizeof($tempArea); $i++) {
                if ($item->Id_area == $tempArea[$i]) {
                    $sw = true;
                }
            }
            if ($sw != true) {
                $user = DB::table('users')->where('id', $item->Id_responsable)->first();
                if (@$item->Id_area == 2 || @$item->Id_area == 7 || @$item->Id_area == 16 || @$item->Id_area == 17 || @$item->Id_area == 45 || @$item->Id_area == 34 || @$item->Id_area == 33) {
                    array_push($numRecipientes, $phMuestra->count());
                    array_push($stdArea, 1);
                } else {
                    array_push($numRecipientes, 1);
                    array_push($stdArea, 0);
                }

                $paramTemp = Parametro::find($item->Id_parametro);
                // var_dump($temp->Id_area);
                $fechaTemp = '';
                switch (@$paramTemp->Id_area) {
                    case 2: // Metales
                        $modelDet = DB::table('ViewLoteDetalle')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                        if ($modelDet->count()) {
                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = date("d-m-Y", strtotime($loteTemp->Fecha . "- 1 days"));
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 17: // Metales ICP
                        $modelDet = DB::table('lote_detalle_icp')->where('Id_codigo', $model->Folio_servicio)->where('Id_control', 1)->where('Id_parametro', $item->Parametro)->get();
                        if ($modelDet->count()) {
                            // $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = date("d-m-Y", strtotime($modelDet[0]->Fecha . "- 1 days"));
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 6: // MB
                    case 12:
                        switch ($item->Parametro) {
                            case 5: // DBO
                                $modelDet = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                            case 12: // Coliformes
                                $modelDet = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                            case 16: // H.H
                                $modelDet = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                            case 78: // E.Coli
                                $modelDet = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
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
                        $modelDet = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                        if ($modelDet->count()) {
                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = $loteTemp->Fecha;
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 13:
                        $modelDet = DB::table('ViewLoteDetalleGA')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                        if ($modelDet->count()) {
                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = $loteTemp->Fecha;
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 16:
                        $modelDet = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                        if ($modelDet->count()) {
                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = $loteTemp->Fecha;
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 5:
                        switch ($item->Parametro) {
                            case 19: // DBO
                                $modelDet = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                        }
                        if ($modelDet->count()) {
                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                            $fechaTemp = $loteTemp->Fecha;
                        } else {
                            $fechaTemp = "";
                        }
                        break;
                    case 8:
                        switch ($item->Parametro) {
                            case 108: // N Amoniacal
                                $modelDet = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                            default:
                                $modelDet = DB::table('ViewLoteDetallePotable')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
                                break;
                        }
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

                array_push($fechasSalidas, $fechaTemp);
                array_push($tempArea, $item->Id_area);
                array_push($area, $item->Area);
                array_push($responsable, $user->name);
                array_push($firmas, $user->firma);
            }
        }


        $paramResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Cadena', 1)->orderBy('Parametro', 'ASC')->get();
        $resInfo = array();
        $resTemp = 0;
        foreach ($paramResultado as $item) {
            $resTemp = 0;
            switch ($item->Id_parametro) {
                case 12:
                case 13:
                    if ($item->Resultado >= $item->Limite) {
                        $resTemp = $item->Resultado;
                    } else {
                        $resTemp = "< " . $item->Limite;
                    }
                    break;
                case 2:
                    if ($item->Resultado2 == 1) {
                        $resTemp = "PRESENTE";
                    } else {
                        $resTemp = "AUSENTE";
                    }
                    break;
                case 14:
                    $resTemp = $item->Resultado2;
                    break;
                default:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        if ($item->Resultado2 >= $item->Limite) {
                            $resTemp = $item->Resultado2;
                        } else {
                            $resTemp = "< " . $item->Limite;
                        }
                    }
                    break;
            }
            array_push($resInfo, $resTemp);
        }

        $recepcion = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        $firmaRes = User::where('id', 17)->first();
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first(); //Condición de busqueda para las configuraciones(Historicos)

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
                         Encripta el contenido de la variable, enviada como parametro.
                          */
        $folioSer = $model->Folio_servicio;
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);



        $mpdf->showWatermarkImage = true;
        $data = array(
            'folioEncript' => $folioEncript,
            'firmaRes' => $firmaRes,
            'resInfo' => $resInfo,
            'paramResultado' => $paramResultado,
            'firmas' => $firmas,
            'recepcion' => $recepcion,
            'stdArea' => $tempArea,
            'fechasSalidas' => $fechasSalidas,
            'numRecipientes' => $numRecipientes,
            'responsable' => $responsable,
            'area' => $area,
            'phMuestra' => $phMuestra,
            'areaParam' => $areaParam,
            'norma' => $norma,
            'model' => $model,
            'reportesCadena' => $reportesCadena,
        );

        $htmlFooter = view('exports.campo.cadenaCustodiaInterna.footerCadena', $data);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $htmlInforme = view('exports.campo.cadenaCustodiaInterna.bodyCadena', $data);
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'I');
    }
}
