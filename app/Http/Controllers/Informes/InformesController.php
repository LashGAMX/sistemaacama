<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\AreaLab;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\Clientes;
use App\Models\ClienteSiralab;
use App\Models\CodigoParametros;
use App\Models\Cotizacion;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\InformesRelacion;
use App\Models\Limite001;
use App\Models\Limite002;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalleCloro;
use App\Models\LoteDetalleColiformes;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\TemperaturaMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\ReportesInformes;
use App\Models\RfcSiralab;
use App\Models\RfcSucursal;
use App\Models\SimbologiaParametros;
use App\Models\Solicitud;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SucursalCliente;
use App\Models\TemperaturaAmbiente;
use App\Models\TipoCuerpo;
use App\Models\TipoReporte;
use App\Models\TituloConsecionSir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\Select;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;

class InformesController extends Controller
{
    //
    public function index()
    {
        $tipoReporte = TipoReporte::all();
        $model = DB::table('ViewSolicitud2')->orderBy('Id_solicitud', 'desc')->where('Padre', 1)->get();
        return view('informes.informes', compact('tipoReporte', 'model'));
    }
    public function getPuntoMuestro(Request $request)
    {
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $request->id)->first();
        $siralab = false;
        if ($solModel->Siralab != 0) {
            $siralab = true;
        }
        $model = SolicitudPuntos::where('Id_solPadre', $request->id)->get();
        $data = array(
            'model' => $model,
            'siralab' => $siralab,
        );
        return response()->json($data);
    }
    public function getSolParametro(Request $request)
    {
        $model = DB::table('ViewCodigoParametroSol')->where('Id_solicitud', $request->idPunto)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function mensual()
    {
        $model = DB::table('ViewSolicitud2')->OrderBy('Id_solicitud', 'DESC')->where('Padre', 0)->get();
        return view('informes.mensual', compact('model'));
    }
    public function getPreReporteMensual(Request $res)
    {
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $res->id1)->first();
        $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $res->id2)->first();
        $sw = false;
        if ($solModel->Siralab == 1 && $solModel2->Siralab == 1) {
            $punto1 = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $res->id1)->first();
            $punto2 = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $res->id2)->first();

            if ($punto1->Id_punto == $punto2->Id_punto) {
                $sw = true;
            }
        } else {
            if ($solModel->Siralab == 0 && $solModel2->Siralab == 0) {
                $punto1 = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $res->id1)->first();
                $punto2 = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $res->id2)->first();

                if ($punto1->Id_muestreo == $punto2->Id_muestreo) {
                    $sw = true;
                }
            }
        }
        if ($sw == true) {
            $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $res->id1)->get();
            $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $res->id2)->get();
        } else {
            $model = '';
            $model2 = '';
        }

        $data = array(
            'sw' => $sw,
            'solModel' => $solModel,
            'solModel2' => $solModel2,
            'model' => $model,
            'model2' => $model2,
        );
        return response()->json($data);
    }

    //todo Seccio de pdf
    public function exportPdfInforme($idSol, $idPunto, $tipo)
    {
        $today = carbon::now()->toDateString();
        $reportesInformes = array();
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 32,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->get();
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        @$tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);

        $relacion = InformesRelacion::where('Id_solicitud', $idPunto)->get();


        $reportesInformes = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first(); //Historicos (Informe)
        $aux = true;

        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $idSol = $idPunto;
        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        //Recupera los datos de la temperatura de la muestra compuesta
        $tempCompuesta = CampoCompuesto::where('Id_solicitud', $idSol);

        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();

        $cliente = SucursalCliente::where('Id_sucursal', $solModel->Id_sucursal)->first();
        $rfc = RfcSucursal::where('Id_sucursal', $solModel->Id_sucursal)->first();

        $tituloConsecion = "";
        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $idSol)->first();
        if ($solModel->Siralab == 1) {
            $auxPunto = PuntoMuestreoSir::where('Id_punto',$puntoMuestreo->Id_muestreo)->first();
            $titTemp = TituloConsecionSir::where('Id_titulo',$auxPunto->Titulo_consecion)->first();
            $tituloConsecion = $titTemp->Titulo;
        }
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area','!=',9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        // $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;
        foreach ($auxAmbienteProm as $item) {
            $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
            $auxTem++;
        }
        @$tempAmbienteProm = round($tempAmbienteProm / $auxTem);

        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo',1)->get();
        $numOrden =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel->Hijo)->first();
        if ($solModel->Id_servicio != 3) {
            $horaMuestreo = \Carbon\Carbon::parse($phCampo[0]->Fecha)->format('H:i');
        } else {
            $horaMuestreo = '';
        }

        $temp = DB::table('ph_muestra')
            ->where('Id_solicitud', $idSol)
            ->selectRaw('count(Color) as numColor,Color')
            ->groupBy('Color')
            ->get();
        $swPh = false;
        $swOlor = false;
        foreach ($phCampo as $item) {
            if ($item->Olor == "Si") {
                $swOlor = true;
            }
        }
        $colorTemp = 0;
        $color = "";
        foreach ($temp as $item) {
            if ($item->numColor >= $colorTemp) {
                $color = $item->Color;
                $colorTemp = $item->numColor;
            }
        }
        $limitesN = array();
        $limitesC = array();
        $aux = 0;
        $limC = 0;
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
                switch ($item->Id_parametro) {
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                        if ($item->Resultado2 == 1) {
                            $limC = "PRESENTE";
                        } else {
                            $limC = "AUSENTE";
                        }
                        break;
                    case 14:
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                            case 2:
                            case 4:
                                $limC = number_format(@$item->Resultado2, 2, ".", "");
                                break;
                            default:
                                $limC = number_format(@$item->Resultado2, 1, ".", "");        
                                break;
                        }
                        break;
                    case 110:
                    case 125:
                        $limC = number_format(@$item->Resultado2, 1, ".", "");
                        break;
                    case 26:
                        $limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    // case 67:
                    //     $limC = number_format(@$item->Resultado2, 0, ".", "");
                    //     break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109: 
                   // case 67:
                    case 68:
                    case 57:
                    case 59:
                        $limC = $item->Resultado2;
                        break;

                    case 78:
                    case 350:
                        if ($item->Resultado2 > 0) {
                            $limC = $item->Resultado;
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                    case 132:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";       
                            }else{
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";       
                            }else{
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 30:
                            if ($item->Resultado2 < $item->Limite) {
                                $limC = "< " . $item->Limite;
                            } else {
                                $limC = number_format(@$item->Resultado2, 3, ".", "");
                            }
                            break;
                    case 65:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 5:
                    case 11:
                    case 6:
                    case 70:
                    case 12:
                    case 35:
                    case 13:
                    case 15:
                    case 9:
                    case 10:
                    case 83:
                    case 4:
                    case 3:
                    case 103:
                    case 98:
                    case 112:
                    case 218:
                    case 253: 
                    case 51: 
                    case 98:
                    case 89:
                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 38: //ortofosfato
                    case 46: //ssv
                    case 251:
                    case 77:
                        if ($item->Resultado2 < $item->Limite) { 
                            $limC = "< " . $item->Limite; 
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 227:
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 64:
                    case 358:
                        switch ($item->Id_norma) {
                            case 1:
                            case 27:
                                switch ($item->Resultado2) {
                                    case 499:
                                        $limC = "< 500";
                                        break;
                                    case 500:
                                        $limC = "500";
                                        break;
                                    case 1000:
                                        $limC = "1000";
                                        break;
                                    case 1500:
                                        $limC = "> 1000";
                                        break;
                                    default:
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                        break;
                                }
                                
                                break;
                            default:
                                if ($item->Resultado2 < $item->Limite) {
                                    $limC = "< " . $item->Limite;
                                }else{
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 358:
                        $limC =  round(@$item->Resultado2);
                        break;
                    case 67: //conductividad
                        switch ($item->Id_norma) {
                            case 1:
                            case 27:
                                if ($puntoMuestreo->Condiciones != 1) {
                                    if ($item->Resultado2 >= 3500) {
                                        $limC = "> 3500";
                                    } else {
                                        $limC = round($item->Resultado2);
                                    }
                                }else{
                                    $limC = round($item->Resultado2);
                                }
                                break;
                            default:
                                $limC = round($item->Resultado2);
                                break;
                        }
                        break;
                       
                    default:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;
    
                            $Resultado =  floatval($item->Resultado2);
                          
                            $limC = number_format(@$Resultado, 3, ".", "");
                        }
                        break;
                }
                switch ($item->Id_norma) {
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->PromD;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Per_max;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud->Id_promedio)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    default:

                        break;
                }
            } else {
                $aux = "------";
                $limC = "------";
            }
            array_push($limitesN, $aux);
            array_push($limitesC, $limC);
        }
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        switch ($solModel->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                $firma1 = User::find(14);
                $firma2 = User::find(4);
                break;

            default:
            //Residual
                $firma1 = User::find(14);
                $firma2 = User::find(12);
                //$firma2 = User::find(4);
                break;
        }
        //Proceso de Reporte Informe

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
                 Encripta el contenido de la variable, enviada como parametro.
                  */
        $folioSer = $solicitud->Folio_servicio;
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);



        $data = array(
            'tituloConsecion' => $tituloConsecion,
            'numTomas' => @$numTomas,
            'tipoReporte2' => $tipoReporte2,
            'folioEncript' => $folioEncript,
            'campoCompuesto' => $campoCompuesto,
            'swOlor' => $swOlor,
            'color' => $color,
            'tempAmbienteProm' => $tempAmbienteProm,
            'limitesC' => $limitesC,
            'horaMuestreo' => $horaMuestreo,
            'numOrden' => $numOrden,
            'model' => $model,
            'cotModel' => $cotModel,
            'tipoReporte' => $tipoReporte,
            'solModel' => $solModel,
            'fechaAnalisis' => $fechaAnalisis,
            'swPh' => $swPh,
            'firma1' => $firma1,
            'firma2' => $firma2,
            'phCampo' => $phCampo,
            'modelProcesoAnalisis' => $modelProcesoAnalisis,
            'campoGeneral' => $campoGeneral,
            'obsCampo' => $obsCampo,
            'temperaturaC' => $temperaturaC,
            'puntoMuestreo' => $puntoMuestreo,
            'cliente' => $cliente,
            'direccion' => $direccion,
            'solicitud' => $solicitud,
            'tempCompuesta' => $tempCompuesta,
            'limitesN' => $limitesN,
            'tipo' => $tipo,
            'rfc' => $rfc,
            'reportesInformes' => $reportesInformes,
        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.diario.bodyInforme', $data);
        $htmlHeader = view('exports.informes.diario.headerInforme', $data);
        $htmlFooter = view('exports.informes.diario.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }

    public function exportPdfInformeCampo($idSol, $idPunto)
    {
        $today = carbon::now()->toDateString();
        $reportesInformes = array();
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        $model = DB::table('ViewSolicitud2')->where('Hijo', $idSol)->get();
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        $tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        $informesModel = InformesRelacion::where('Id_solicitud', $idSol)->where('Tipo', 3)->orderBy('Id_relacion', 'desc')->get();
        if ($informesModel->count()) {
            $reportesInformesCampo = DB::table('ViewReportesInformes')->where('Id_reporte', $informesModel[0]->Id_reporte)->first(); //historicos (Campo)
        } else {
            $reportesInformesCampo = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first();
            InformesRelacion::create([
                'Id_solicitud' => $idSol,
                'Tipo' => 3,
                'Id_reporte' => $reportesInformesCampo->Id_reporte,
            ]);
        }

        $aux = true;
        foreach ($model as $item) {
            if ($aux == true) {
                if ($item->Siralab == 1) {
                    $model2 = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $item->Id_solicitud)->where('Id_muestreo', $idPunto)->get();
                    if ($item->Id_solicitud == $model2[0]->Id_solicitud) {
                        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $item->Id_solicitud)->first();
                        $aux = false;
                    }
                } else {
                    $model2 = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud', $idPunto)->get();
                    if ($model2[0]->Id_solicitud == $item->Id_solicitud) {
                        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $item->Id_solicitud)->first();
                        $aux = false;
                    }
                }
            }
        }
        $idSol = $solModel->Id_solicitud;
        $compuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        //Recupera los datos de la temperatura de la muestra compuesta
        $tempCompuesta = CampoCompuesto::where('Id_solicitud', $idSol);

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();

        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();
        $rfc = RfcSucursal::where('Id_sucursal', $solModel->Id_sucursal)->first();

        if ($solicitud->Siralab == 1) { //Es cliente Siralab
            $puntoMuestreo = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $idSol)->first();
        } else {
            $puntoMuestreo = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $idSol)->first();
        }
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = $temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numOrden =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel->Hijo)->first();
        if ($solModel->Id_muestra == 1) {
            $horaMuestreo = \Carbon\Carbon::parse($phCampo[0]->Fecha)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        $temp = DB::table('ph_muestra')
            ->where('Id_solicitud', $idSol)
            ->selectRaw('count(Color) as numColor,Color')
            ->groupBy('Color')
            ->get();
        $swPh = false;
        $swOlor = false;
        foreach ($phCampo as $item) {
            if ($item->Olor == "Si") {
                $swOlor = true;
            }
        }
        $colorTemp = 0;
        $color = "";
        foreach ($temp as $item) {
            if ($item->numColor >= $colorTemp) {
                $color = $item->Color;
                $colorTemp = $item->numColor;
            }
        }

        $limitesN = array();
        $limitesC = array();
        $aux = 0;
        $limC = 0;
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL) {
                switch ($item->Id_parametro) {
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                        if ($item->Resultado2 == 1) {
                            $limC = "PRESENTE";
                        } else {
                            $limC = "AUSENTE";
                        }
                        break;
                    case 14:
                        $limC = round($item->Resultado2, 2);
                        break;
                    case 5:
                        if ($item->Resultado2 <= $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {

                            $limC = round($item->Resultado2, 2);
                        }
                        break;
                    default:
                        if ($item->Resultado2 <= $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                }
                switch ($item->Id_norma) {
                    case 1:
                        $limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->PromD;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Per_max;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Per_max;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    default:

                        break;
                }
            } else {
                $aux = "------";
                $limC = "------";
            }


            array_push($limitesN, $aux);
            array_push($limitesC, $limC);
        }
        $firma1 = User::find(14);
        $firma2 = User::find(12);
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $conducCampo = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', 67)->first();

        //Proceso de Reporte Informe
        $gasto = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $phMuestra = PhMuestra::where('Id_solicitud', $idSol)->get();
        $tempMuestra = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $grasasModel = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        @$ecoliModel = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', 35)->get();
        @$enteModel = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', 253)->get();
        @$colModel = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', 12)->get();
        $sumGasto = 0;
        $gastoProm = array();
        $promPh = 0;
        $promTemp = 0;
        $promGa = 0;
        $promEcoli = 0;
        $promEnt = 0;
        $promCol = 0;
        $aux = 0;

        foreach ($gasto as $item) {
            if ($item->Activo == 1) {
                $sumGasto = $sumGasto + $item->Promedio;
            }
        }
        foreach ($gasto as $item) {
            array_push($gastoProm, ($item->Promedio / $sumGasto));
        }

        foreach ($phMuestra as $item) {
            if ($item->Activo == 1) {
                $promPh = $promPh + ($item->Promedio * $gastoProm[$aux]);
                $promGa = $promGa + ($grasasModel[$aux]->Resultado * $gastoProm[$aux]);
                @$promEcoli = $promEcoli + (@$ecoliModel[$aux]->Resultado * $gastoProm[$aux]);
                @$promEnt = $promEnt + (@$enteModel[$aux]->Resultado * $gastoProm[$aux]);
                @$promCol = $promCol + (@$colModel[$aux]->Resultado * $gastoProm[$aux]);
                $promTemp = $promTemp + ($tempMuestra[$aux]->Promedio * $gastoProm[$aux]);
                $aux++;
            }
        }
        // $promPh = $promPh / $aux;
        // $promGa = $promGa / $aux;
        // @$promEcoli = @$promEcoli / $aux;
        // $promTemp = $promTemp / $aux;

        $limPh = DB::table('limite001_2021')->where('Id_parametro', 14)->first();
        $limTemp = DB::table('limite001_2021')->where('Id_parametro', 97)->first();
        $limCol = DB::table('limite001_2021')->where('Id_parametro', 35)->first();
        $limEnt = DB::table('limite001_2021')->where('Id_parametro', 253)->first();
        $limColiformes = DB::table('limite001_2021')->where('Id_parametro', 12)->first();
        $limGa = DB::table('limite001_2021')->where('Id_parametro', 13)->first();

        $data = array(
            'conducCampo' => $conducCampo,
            'limPh' => $limPh,
            'limTemp' => $limTemp,
            'limCol' => $limCol,
            'limColiformes' => $limColiformes,
            'limEnt' => $limEnt,
            'limGa' => $limGa,
            'compuesto' => $compuesto,
            'promEnt' => $promEnt,
            'promCol' => $promCol,
            'enteModel' => $enteModel,
            'ecoliModel' => @$ecoliModel,
            'promEcoli' => @$promEcoli,
            'colModel' => $colModel,
            'grasasModel' => $grasasModel,
            'promGa' => $promGa,
            'tempMuestra' => $tempMuestra,
            'promTemp' => $promTemp,
            'promPh' => $promPh,
            'gasto' => $gasto,
            'sumGasto' => $sumGasto,
            'gastoProm' => $gastoProm,
            'phMuestra' => $phMuestra,
            'campoCompuesto' => $campoCompuesto,
            'swOlor' => $swOlor,
            'color' => $color,
            'tempAmbienteProm' => $tempAmbienteProm,
            'limitesC' => $limitesC,
            'horaMuestreo' => $horaMuestreo,
            'numOrden' => $numOrden,
            'model' => $model,
            'cotModel' => $cotModel,
            'tipoReporte' => $tipoReporte,
            'solModel' => $solModel,
            'fechaAnalisis' => $fechaAnalisis,
            'swPh' => $swPh,
            'firma1' => $firma1,
            'firma2' => $firma2,
            'phCampo' => $phCampo,
            'modelProcesoAnalisis' => $modelProcesoAnalisis,
            'campoGeneral' => $campoGeneral,
            'obsCampo' => $obsCampo,
            'temperaturaC' => $temperaturaC,
            'puntoMuestreo' => $puntoMuestreo,
            'cliente' => $cliente,
            'direccion' => $direccion,
            'solicitud' => $solicitud,
            'tempCompuesta' => $tempCompuesta,
            'limitesN' => $limitesN,
            'rfc' => $rfc,
            'reportesInformes' => $reportesInformesCampo,

        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.diario.campo.bodyInforme', $data);
        $htmlHeader = view('exports.informes.diario.campo.headerInforme', $data);
        $htmlFooter = view('exports.informes.diario.campo.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }

    //****************ESTAS FUNCIONES SE LLAMAN A TRAVÉS DE LA RUTA PÚBLICA HACIENDO USO DEL CÓDIGO QR
    //todo Seccio de pdf
    public function pdfSinComparacionCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 120,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        //$solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        /* $cont = (sizeof($solModel2) - 1);
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->get(); */

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach ($simbolParam as $item) {
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Recupera los datos de la temperatura de la muestra compuesta
        $tempCompuesta = CampoCompuesto::where('Id_solicitud', $idSol);

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $horaMuestreo = null;

        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        // $cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        //$solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if ($solicitud->Siralab == 1) { //Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        } else {
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();

        foreach ($solicitudParametros as $item) {
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            //***************
            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = $item->Promedio / $sumaCaudales;

                    $div = round($div, 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo de nitrógeno total
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Orgánico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if (!is_null($gModel)) {
            foreach ($gModel as $item) {
                if ($item->Promedio == null) {
                    $gastosProm += 0;
                } else {
                    $gastosProm += $item->Promedio;
                }
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if (!is_null($tModel)) {
            foreach ($tModel as $item) {
                if ($item->Promedio == null) {
                    $tProm += 0;
                } else {
                    $tProm += $item->Promedio;
                }
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if (!is_null($phModel)) {
            foreach ($phModel as $item) {
                if ($item->Promedio == null) {
                    $phProm += 0;
                } else {
                    $phProm += $item->Promedio;
                }
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            } else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        //Recupera la obs de campo
        $modelComp = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $obsCampo = $modelComp->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'tempCompuesta', 'sumaCaudalesFinal', 'resColi', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.sinComparacion.headerInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'modelProcesoAnalisis', 'horaMuestreo'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInforme', compact('solicitud', 'simbologiaParam', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'D');
    }

    public function pdfConComparacionCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 76,
            'margin_bottom' => 125,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Recupera el nombre de usuario y firma
        /* $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma; */

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //$fechaEmision = \Carbon\Carbon::now();
        $solicitud = Solicitud::where('Id_solicitud', $idSol)->first();
        $direccion = DireccionReporte::where('Id_direccion', $solicitud->Id_direccion)->first();
        $folio = explode("-", $solicitud->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);



        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', $folio[0])->first();
        //$cotizacion = Cotizacion::where('Folio_servicio', 'LIKE', "%{$solicitud->Folio_servicio}%")->get();
        $cliente = Clientes::where('Id_cliente', $solicitud->Id_cliente)->first();
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solicitud->Id_solicitud)->first();
        $puntoMuestreo = null;

        if ($solicitud->Siralab == 1) { //Es cliente Siralab
            $puntoMuestreo = PuntoMuestreoSir::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        } else {
            $puntoMuestreo = PuntoMuestreoGen::where('Id_sucursal', $solicitud->Id_sucursal)->get();
            // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',$idSolicitud)->get();
            $puntos = $puntoMuestreo->count();
        }

        /* $solicitudParametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $solicitud->Id_solicitud)->get();
        $solicitudParametrosLength = $solicitudParametros->count(); */

        //Sirve para recuperar los límites de cuantificación de la norma
        //$promSol = $solicitud->Id_promedio; //Si es mensual o diario

        //Recupera los parámetros
        $solicitudParametros = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $solicitudParametrosLength = $solicitudParametros->count();
        $sParam = array();
        //$limNorm = array();

        foreach ($solicitudParametros as $item) {
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);

            /* if($solicitud->Id_norma == 1){ //Norma 001
                Limite001::where('Id_parametro', $item->Id_parametro)->where('Id_')->first();
            }else if($solicitud->Id_norma == 2){ //Norma 002
                $param = Limite002::where('Id_parametro', $item->Id_parametro)->first();
            } */
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach ($simbolParam as $item) {
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS *************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        if (!is_null($solicitudParametroGrasas)) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
            $gastosModelLength = $gastosModel->count();
            $sumaCaudales = 0;
            $sumaCaudalesFinal = 0;

            //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
            $divCaudalSuma = array();

            //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
            $multResDivCaudal = array();

            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $sumaCaudales += 0;
                } else {
                    $sumaCaudales += $item->Promedio;
                }
            }

            $sumaCaudales = round($sumaCaudales, 2);

            //Paso 2: División de cada caudal entre la sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $div = 0 / $sumaCaudales;

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                } else {
                    $div = round(($item->Promedio / $sumaCaudales), 2);

                    array_push(
                        $divCaudalSuma,
                        $div
                    );
                }
            }

            //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
            for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                array_push(
                    $multResDivCaudal,
                    $mult
                );
            }

            //Paso 4: Sumatoria de multResDivCaudal
            foreach ($multResDivCaudal as $item) {
                $sumaCaudalesFinal += $item;
            }

            $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

            if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
                $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            }
        }

        //echo  implode(" , ", $multResDivCaudal);        

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES ******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();
        $resColi = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);

            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteColi = DB::table('parametros')->where('Id_parametro', $solicitudParametroColiformesFe[0]->Id_parametro)->first();

            if ($resColi < $limiteColi->Limite) {
                $resColi = "< " . $limiteColi->Limite;
            }
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES *********************************************** 

        //************************************** CALCULO DE NITROGENO KJELDAHL **********************************************

        //Consulta si existen los parámetros nitrogeno total y amoniacal para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();

            $resKjeldahl = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            $resKjeldahl = $resLimAmo + $resLimOrg;
        }

        //************************************** FIN DE CALCULO DE NITROGENO KJELDAHL ***************************************

        //***************************************CALCULO DE NITROGENO TOTAL *************************************************
        //Consulta si existen los parámetros nitrogeno total, amoniacal, nitritos y nitratos para esta solicitud
        $solParamAmoniacal = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 10)->first();
        $solParamOrganico = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 11)->first();
        $solParamNitritos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 9)->first();
        $solParamNitratos = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 8)->first();

        //Sí existen los parámetros para el cálculo kjeldahl
        if (!is_null($solParamAmoniacal) && !is_null($solParamOrganico) && !is_null($solParamNitritos) && !is_null($solParamNitratos)) {
            //Verifica si el resultado es menor al límite de cuantificación del parámetro
            $limiteAmoniacal = DB::table('parametros')->where('Id_parametro', $solParamAmoniacal->Id_parametro)->first();
            $limiteOrganico = DB::table('parametros')->where('Id_parametro', $solParamOrganico->Id_parametro)->first();
            $limiteNitritos = DB::table('parametros')->where('Id_parametro', $solParamNitritos->Id_parametro)->first();
            $limiteNitratos = DB::table('parametros')->where('Id_parametro', $solParamNitratos->Id_parametro)->first();

            $resNitrogenoT = 0;
            $resLimAmo = 0;
            $resLimOrg = 0;
            $resLimNitritos = 0;
            $resLimNitratos = 0;

            //Nitrogeno Amoniacal
            if ($solParamAmoniacal->Resultado < $limiteAmoniacal->Limite) {
                $resLimAmo = $limiteAmoniacal->Limite;
            } else {
                $resLimAmo = $solParamAmoniacal->Resultado;
            }

            //Nitrogeno Organico
            if ($solParamOrganico->Resultado < $limiteOrganico->Limite) {
                $resLimOrg = $limiteOrganico->Limite;
            } else {
                $resLimOrg = $solParamOrganico->Resultado;
            }

            //Nitritos
            if ($solParamNitritos->Resultado < $limiteNitritos->Limite) {
                $resLimNitritos = $limiteNitritos->Limite;
            } else {
                $resLimNitritos = $solParamNitritos->Resultado;
            }

            //Nitratos
            if ($solParamNitratos->Resultado < $limiteNitratos->Limite) {
                $resLimNitratos = $limiteNitratos->Limite;
            } else {
                $resLimNitratos = $solParamNitratos->Resultado;
            }

            $resNitrogenoT = $resLimAmo + $resLimOrg + $resLimNitritos + $resLimNitratos;
        }
        //*************************************** FIN DE CALCULO DE NITROGENO TOTAL *****************************************

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if (!is_null($gModel)) {
            foreach ($gModel as $item) {
                if ($item->Promedio == null) {
                    $gastosProm += 0;
                } else {
                    $gastosProm += $item->Promedio;
                }
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if (!is_null($tModel)) {
            foreach ($tModel as $item) {
                if ($item->Promedio == null) {
                    $tProm += 0;
                } else {
                    $tProm += $item->Promedio;
                }
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if (!is_null($phModel)) {
            foreach ($phModel as $item) {
                if ($item->Promedio == null) {
                    $phProm += 0;
                } else {
                    $phProm += $item->Promedio;
                }
            }

            $phProm /= $phModelLength;
        }

        $limitesC = array();

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($solicitudParametros as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            } else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 20) {   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limitesC, $limC);
            }
        }

        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        //Recupera el valor correcto de la hora de muestreo
        if ($solicitud->Id_muestra !== 'COMPUESTA') {
            $horaMuestreo = \Carbon\Carbon::parse($modelProcesoAnalisis->Hora_entrada)->format('H:i:s');
        } else {
            $horaMuestreo = 'COMPUESTA';
        }

        //Recupera la obs de campo
        $modelComp = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        $obsCampo = $modelComp->Observaciones;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyComparacionInforme',  compact('solicitudParametros', 'solicitudParametrosLength', 'limitesC', 'sumaCaudalesFinal', 'resColi', 'sParam', 'puntoMuestreo'));

        //HEADER-FOOTER******************************************************************************************************************                 
        $htmlHeader = view('exports.informes.conComparacion.headerComparacionInforme', compact('solicitud', 'direccion', 'cliente', 'puntoMuestreo', 'numOrden', 'horaMuestreo', 'modelProcesoAnalisis'));
        $htmlFooter = view('exports.informes.conComparacion.footerComparacionInforme', compact('solicitud', 'simbologiaParam', 'obsCampo'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de resultados con comparacion.pdf', 'D');
    }

    //************************************************************************************************
    public function exportPdfInformeMensual($idSol1Temp, $idSol2Temp, $tipo)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 71,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        echo "<br>ID: ".$idSol1Temp;
        echo "<br>ID: ".$idSol2Temp;
        $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
        $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
        $valFol = explode('-',$solModel1->Folio_servicio);
        $valFol2 = explode('-',$solModel2->Folio_servicio);
        echo "<br> VALFOL1: ".$valFol[0];
        echo "<br> VALFOL2: ".$valFol2[0];
        if ($valFol[0] < $valFol2[0]) {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            echo "<br> Entro IF";
            $idSol2 = $idSol2Temp;
            $idSol1 = $idSol1Temp;
        }else{
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $idSol2 = $idSol1Temp;
            $idSol1 = $idSol2Temp;
        }
        echo "<br> Folio 1: ".$solModel1->Folio_servicio. "| ID: ".$idSol1;
        echo "<br> Folio 2: ".$solModel2->Folio_servicio; "| ID: ".$idSol2;
        

        $direccion1 = DireccionReporte::where('Id_direccion',$solModel1->Id_direccion)->first();
        // $direccion2 = DireccionReporte::where('Id_direccion',$solModel2->Id_direccion)->first();

        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        $auxPunto = PuntoMuestreoSir::where('Id_punto',$punto->Id_muestreo)->first();
        @$tituloConsecion = TituloConsecionSir::where('Id_titulo',$auxPunto->Titulo_consecion)->first();
        $rfc = RfcSucursal::where('Id_sucursal', $solModel1->Id_sucursal)->first();
        if ($solModel1->Id_norma == 27) { 
            return redirect()->to('admin/informes/exportPdfInformeMensual/001/' . $idSol1 . '/' . $idSol2 . '/' . $tipo);
        }

        //historial (informe Mensual)
        $informesModel = InformesRelacion::where('Id_solicitud', $idSol1)->where('Id_solicitud2', $idSol2)->get();
        if ($informesModel->count()) {
            $informesReporte = DB::table('ViewReportesInformesMensual')->where('Id_reporte', $informesModel[0]->Id_reporte)->first();
        } else {
            $informesReporte = DB::table('ViewReportesInformesMensual')->orderBy('Num_rev', 'desc')->where('deleted_at',null)->first();
            InformesRelacion::create([
                'Id_solicitud' => $idSol1,
                'Id_solicitud2' => $idSol2,
                'Tipo' => $tipo,
                'Id_reporte' => $informesReporte->Id_reporte,
            ]);
        }
        //ViewCodigoParametro
        $reportesInformes = DB::table('ViewReportesInformesMensual')->where('deleted_at',null)->orderBy('Num_rev', 'desc')->first(); //Condición de busqueda para las configuraciones(Historicos)  

        $model1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();

        $obs1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $obs2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();
        $auxAmbienteProm1 = TemperaturaAmbiente::where('Id_solicitud', $idSol1)->where('Activo', 1)->get();
        $tempAmbienteProm1 = 0;
        $auxTem1 = 0;
        foreach ($auxAmbienteProm1 as $item) {
            $tempAmbienteProm = $tempAmbienteProm1 + $item->Temperatura1;
            $auxTem1++;
        }
        @$tempAmbienteProm1 = round($tempAmbienteProm1 / $auxTem1);

        $auxAmbienteProm2 = TemperaturaAmbiente::where('Id_solicitud', $idSol2)->where('Activo', 1)->get();
        $tempAmbienteProm2 = 0;
        $auxTem2 = 0;
        foreach ($auxAmbienteProm2 as $item) {
            $tempAmbienteProm2 = $tempAmbienteProm2 + $item->Temperatura1;
            $auxTem2++;
        }
        @$tempAmbienteProm2 = round($tempAmbienteProm2 / $auxTem2);

        $PhMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->get();
        $PhMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->get();
        

        $gasto1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $gasto2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $proceso1 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol1)->first();
        $proceso2 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol2)->first();
        $numOrden1 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel1->Hijo)->first();
        $numOrden2 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel2->Hijo)->first();
        $firma1 = User::find(14);
        $firma2 = User::find(12);
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $solModel1->Id_cotizacion)->first();
        $tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        $cliente = Clientes::where('Id_cliente', $solModel1->Id_cliente)->first();

        echo "<br> Fecha1: ".$proceso1->Hora_recepcion;
        echo "<br> Fecha2: ".$proceso2->Hora_recepcion;

        @$promGastos = (round($gasto1->Resultado2,2) + round($gasto2->Resultado2,2));
        @$parti1 = round($gasto1->Resultado2,2) / $promGastos;
        @$parti2 = round($gasto2->Resultado2,2) / $promGastos;
        $limitesN = array();
        $limitesC1 = array();
        $limitesC2 = array();
        $limitesC3 = array();
        $ponderado = array();
        $aux = 0;
        $limC1 = 0;
        $limC2 = 0;
        $limP = 0;
        $cont = 0;
        foreach ($model1 as $item) {

            switch ($item->Id_parametro) {
                case 2:
                    if ($item->Resultado2 == 1) {
                        $limC1 = "PRESENTE";
                    } else {
                        $limC1 = "AUSENTE";
                    }
                    if ($model2[$cont]->Resultado2 == 1) {
                        $limC2 = "PRESENTE";
                    } else {
                        $limC2 = "AUSENTE";
                    }
                    break;
                case 97:
                    $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                    $limP = round($limP);
                    $limC1 = round($item->Resultado2);
                    $limC2 = round($model2[$cont]->Resultado2);
                    break;
                case 14:
                case 110:
                    $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                    $limP = number_format($prom, 2, ".", "");
                    break;
                case 64:
                case 358:
                    $aux64 = 0;
                    switch (@$item->Resultado2) {
                        case 499:
                            $limC1 = "< 500";
                            $aux64 = 499;
                            break;
                        case 500:
                            $limC1 = "500";
                            $aux64 = 500;
                            break;
                        case 1000:
                            $limC1 = "1000";
                            $aux64 = 1000; 
                            break;
                        case 1500:
                            $limC1 = "> 1000";
                            $aux64 = 1500;
                            break;
                        default:
                            $limC1 =  number_format(@$item->Resultado2, 2, ".", "");
                            $aux64 = $aux64 + @$item->Resultado2;
                            break;
                    }
                    switch (@$model2[$cont]->Resultado2) {
                        case 499:
                            $limC2 = "< 500";
                            $aux64 = $aux64 + 499;
                            break;
                        case 500:
                            $limC2 = "500";
                            $aux64 = $aux64 + 500;
                            break;
                        case 1000:
                            $limC2 = "1000";
                            $aux64 = $aux64 + 1000;
                            break;
                        case 1500:
                            $limC2 = "> 1000";
                            $aux64 = $aux64 + 1500;
                            break;
                        default:
                            $limC2 =  number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                            $aux64 = $aux64 + @$model2[$cont]->Resultado2;
                            break;
                    }
                    $aux64 = $aux64 / 2;
                    if ($aux64 < 500) {
                        $limP = "< 500";
                    } else if ($aux64 > 1000) {
                        $limP = "> 1000";
                    }else{
                        $limP = number_format(@$aux64, 2, ".", "");;
                    }
                    break;
                    case 67:
                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                        if ($item->Resultado2 >= "3500") {
                            $limC1 = "> 3500";
                        }else{
                            $limC1 = round($item->Resultado2);
                        }
                        if ($model2[$cont]->Resultado2 >= "3500") {
                            $limC2 = "> 3500";
                        }else{
                            $limC2 = round($model2[$cont]->Resultado2);
                        }
                        if ($limP >= "3500") {
                            $limP = "> 3500";
                        }else{
                            $limP = round($limP);
                        }
                        break;
                default:
                    if ($item->Resultado2 != NULL || $model2[$cont]->Resultado2 != NULL) {
                        $limAux1 = 0;
                        $limAux2 = 0;
                        $limPromAux = 1;
                        if (@$item->Resultado2 < $item->Limite) {
                            @$limAux1 = @$item->Limite;
                            $limPromAux = 0;
                        }else{
                            @$limAux1 = @$item->Resultado2;
                        }
                        if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                            @$limAux2 = $item->Limite;
                            $limPromAux = 0;
                        }else{
                            @$limAux2 = @$model2[$cont]->Resultado2;
                        }
                        switch ($item->Id_parametro) {
                                    //Redondeo a enteros
                                case 97:
                                case 358:
                                    $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    $limP = round($prom);
                                    $limC1 = round($item->Resultado2); 
                                    $limC2 = round($model2[$cont]->Resultado2);
                                    break;
                                case 67:
                                    $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                   $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    if ($item->Resultado2 > "3500") {
                                        $limC1 = "> 3500";
                                    }else{
                                        $limC1 = round($item->Resultado2);
                                    }
                                    if ($model2[$cont]->Resultado2 > "3500") {
                                        $limC2 = "> 3500";
                                    }else{
                                        $limC2 = round($model2[$cont]->Resultado2);
                                    }
                                    break;
                                case 26: //gasto
                                    $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    $limP = number_format($prom,2);
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    break;
                                    // 3 Decimales
                                case 17: // Arsenico
                                case 231:         
                                case 20: // Cobre
                                case 22: //Mercurio
                                case 25: //Zinc 
                                case 227:
                                case 24: //Plomo
                                case 21: //Cromoa
                                case 264:
                                case 18: //Cadmio
                                case 7:   
                                case 8:
                                case 152: 
                                case 19:
                                case 23:
                                case 113:
                               
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    }else{
                                        $limP = ($limAux1 + $limAux2) / 2;
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "<" . number_format(@$item->Limite, 3, ".", "");
                                    }else{
                                        if ($limP == $item->Limite) {
                                            if ($limPromAux == 0) {
                                                $limP = "< ".$item->Limite;
                                            }else{
                                                $limP = number_format(@$limP, 3, ".", "");      
                                            }
                                        }else{
                                            $limP = number_format(@$limP, 3, ".", "");  
                                        }
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 3, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 3, ".", "");
                                    }
                                    break;
                                case 9:
                                case 10:
                                case 11:
                                case 83:
                                case 12:
                                case 15:
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    }else{
                                        $limP = ($limAux1 + $limAux2) / 2;
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        if ($limP == $item->Limite) {
                                            if ($limPromAux == 0) {
                                                $limP = "< ".$item->Limite;
                                            }else{
                                                $limP = number_format(@$limP, 2, ".", "");      
                                            }
                                        }else{
                                            $limP = number_format(@$limP, 2, ".", "");  
                                        }
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                                case 35:
                                case 134:
                                case 50:
                                case 51:
                                case 78:
                                case 253: 
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    }else{
                                        $limP = ($limAux1 + $limAux2) / 2;
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        if ($limP == $item->Limite) {
                                            if ($limPromAux == 0) {
                                                $limP = "< ".$item->Limite;
                                            }else{
                                                $limP = number_format(@$limP, 2, ".", "");      
                                            }
                                        }else{
                                            $limP = number_format(@$limP, 2, ".", "");  
                                        }
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                        // $limC1 = round(@$item->Resultado2);
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        // $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                        // $limC2 = round(@$model2[$cont]->Resultado2);
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                                default: 
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                    }else{
                                        $limP = ($limAux1 + $limAux2) / 2;
                                    }

                                    if ($limP < $item->Limite) {
                                        $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        if ($limP == $item->Limite) {
                                            if ($limPromAux == 0) {
                                                $limP = "< ".$item->Limite;
                                            }else{
                                                $limP = number_format(@$limP, 2, ".", "");      
                                            }
                                        }else{
                                            $limP = number_format(@$limP, 2, ".", "");  
                                        }
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                            }
                    
                    } else { 
                        $limC1 = "-----";
                        $limC2 = "-----";
                        $limP = "-----";
                    }
                    break;
            }
            switch ($item->Id_norma) {
                case 27:
                    @$limNo = DB::table('limite001_2021')->where('Id_categoria', $tipoReporte->Id_categoria)->where('Id_parametro', $item->Id_parametro)->get();
                    if ($limNo->count()) {
                        $aux = $limNo[0]->Pm;
                    } else {
                        $aux = "N/A";
                    }
                    break;
                default:

                    break;
            }
            array_push($limitesN, $aux);
            array_push($limitesC1, $limC1);
            array_push($limitesC2, $limC2);
            array_push($ponderado, $limP);
            $cont++;
        }
        $phMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->where('Activo',1)->get();
        $phMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->where('Activo',1)->get();

        $auxPh = 0;
        $olor1 = false;
        $olor2 = false;
        $color1 = "";
        $color2 = "";

        foreach ($phMuestra1 as $item) {
            if ($item->Olor == "Si") {
                $olor1 = true;   
            }
            if ($phMuestra2[$auxPh]->Olor == "Si") {
                $olor2 = true;   
            }
            $auxPh++;
        }
        foreach ($phMuestra1 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color1 = $item->Color;
                break;
            }else{
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get(); 
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color2 = $item->Color;
                break;
            }else{
                $color2 = $item->Color;
            }
        }
        $tempAmbiente1  = TemperaturaMuestra::where('Id_solicitud', $idSol1)->where('Activo',1)->get();
        $tempAmbiente2  = TemperaturaMuestra::where('Id_solicitud', $idSol2)->where('Activo',1)->get();
        $auxTemp = 0;
        $tempProm1 = 0;
        $tempProm2 = 0;
        foreach ($tempAmbiente1 as $item) {
            $tempProm1 += $item->Promedio;
            $tempProm2 += $tempAmbiente2[$auxTemp]->Promedio;
            $auxTemp++;
        }
        $tempProm1 = $tempProm1 / $auxTemp;
        $tempProm2 = $tempProm2 / $auxTemp;

        
        $auxPh = 0;
        $olor1 = false;
        $olor2 = false;
        $color1 = "";
        $color2 = "";

        foreach ($phMuestra1 as $item) {
            if ($item->Olor == "Si") {
                $olor1 = true;   
            }
            if ($phMuestra2[$auxPh]->Olor == "Si") {
                $olor2 = true;   
            }
            $auxPh++;
        }
        foreach ($phMuestra1 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color1 = $item->Color;
                break;
            }else{
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get(); 
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color2 = $item->Color;
                break;
            }else{
                $color2 = $item->Color;
            }
        }
        
        $campoCompuesto1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $campoCompuesto2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();


        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
                 Encripta el contenido de la variable, enviada como parametro.
                  */
        $folioSer1 = $solModel1->Id_solicitud;
        $folioEncript1 =  openssl_encrypt($folioSer1, $method, $clave, false, $iv);
        $folioSer2 = $solModel1->Id_solicitud;
        $folioEncript2 =  openssl_encrypt($folioSer1, $method, $clave, false, $iv);



        $data = array(
            'folioEncript1' => $folioEncript1,
            'folioEncript2' => $folioEncript2,
            'campoCompuesto1' => $campoCompuesto1,
            'campoCompuesto2' => $campoCompuesto2,
            'reportesInformes' => $reportesInformes,
            'rfc' => $rfc,
            'ponderado' => $ponderado,
            'tempProm1' => $tempProm1,
            'tempProm2' => $tempProm2,
            'color1' => $color1,
            'color2' => $color2,
            'olor1' => $olor1,
            'olor2' => $olor2,
            'obs1' => $obs1,
            'obs2' => $obs2,
            'tituloConsecion' => $tituloConsecion,
            'direccion1' => $direccion1,
            'cliente' => $cliente,
            'limitesN' => $limitesN,
            'limitesC1' => $limitesC1,
            'limitesC2' => $limitesC2,
            'tipo' => $tipo,
            'punto' => $punto,
            'model1' => $model1,
            'model2' => $model2,
            'gasto1' => $gasto1,
            'gasto2' => $gasto2,
            'proceso1' => $proceso1,
            'proceso2' => $proceso2,
            'numOrden1' => $numOrden1,
            'numOrden2' => $numOrden2,
            'solModel1' => $solModel1,
            'solModel2' => $solModel2,
            'firma1' => $firma1,
            'firma2' => $firma2,
            'informesReporte' => $informesReporte,
        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.mensual.bodyInforme', $data);
        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.mensual.headerInforme', $data);
        $htmlFooter = view('exports.informes.mensual.footerInforme', $data);

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'I');
    }
    public function exportPdfInformeMensual001($idSol1Temp, $idSol2Temp, $tipo)
    {
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 78,
            'margin_bottom' => 55,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // Hace los filtros para realizar la comparacion
        // $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1)->first();
        // $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2)->first();
        $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
        $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
        $valFol = explode('-',$solModel1->Folio_servicio);
        $valFol2 = explode('-',$solModel2->Folio_servicio);
        // echo "<br> VALFOL1: ".$valFol[0];
        // echo "<br> VALFOL2: ".$valFol2[0];
        if ($valFol[0] < $valFol2[0]) {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            // echo "<br> Entro IF";
            $idSol2 = $idSol2Temp;
            $idSol1 = $idSol1Temp;
        }else{
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $idSol2 = $idSol1Temp;
            $idSol1 = $idSol2Temp;
        }
        
        @$gasto1Aux = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->get();
        @$gasto2Aux = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->get();
        if ($gasto1Aux->count()) {}else{
            $solGen = SolicitudesGeneradas::where('Id_solicitud',$idSol1)->first();
            CodigoParametros::create([
                'Id_solicitud' => $idSol1,
                'Id_parametro' => 26,
                'Codigo' => $solModel1->Folio_servicio,
                'Num_muestra' => 1,
                'Cadena' => 1,
                'Asignado' => 1,
                'Analizo' => $solGen->Id_muestreador,
                'Cancelado' => 0,
                'Liberado' => 1,
            ]);
            $auxGasto1 = GastoMuestra::where('Id_solicitud', $idSol1)->where('Activo', 1)->get();
            $contGas1 = 0;   
            foreach ($auxGasto1 as $item) {
                $contGas1 = $contGas1 + $item->Promedio;
            }
            
        
        $modGas = CodigoParametros::where('Id_parametro',26)->where('Id_solicitud',$idSol1)->first();
        $modGas->Resultado = ($contGas1 / $auxGasto1->count());
        $modGas->Resultado2 = ($contGas1 / $auxGasto1->count());
        $modGas->save();
        }
        if ($gasto2Aux->count()) {}else{
            $solGen = SolicitudesGeneradas::where('Id_solicitud',$idSol2)->first();
            CodigoParametros::create([
                'Id_solicitud' => $idSol2,
                'Id_parametro' => 26,
                'Codigo' => $solModel2->Folio_servicio,
                'Num_muestra' => 1,
                'Cadena' => 1,
                'Asignado' => 1,
                'Analizo' => $solGen->Id_muestreador,
                'Cancelado' => 0,
                'Liberado' => 1,
            ]);
            $auxGasto2 = GastoMuestra::where('Id_solicitud', $idSol2)->where('Activo', 1)->get();     
            $contGas2 = 0;        
         
            foreach ($auxGasto2 as $item) {
                $contGas2 = $contGas2 + $item->Promedio;
            }
    
            $modGas2 = CodigoParametros::where('Id_parametro',26)->where('Id_solicitud',$idSol2)->first();
            $modGas2->Resultado = ($contGas2 / $auxGasto2->count());
            $modGas2->Resultado2 = ($contGas2 / $auxGasto2->count());
            $modGas2->save();
    
        }

        

        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        if ($solModel1->Siralab == 1) {
            // $punto = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $idSol1)->first();
            // $rfc = RfcSiralab::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            // $titulo = TituloConsecionSir::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            // $dirTemp = DB::table('ViewDireccionSir')->where('Id_cliente_siralab', $solModel1->Id_direccion)->first();
            // $dirReporte = @$dirTemp->Calle . ' ' . @$dirTemp->Num_exterior . ' ' . @$dirTemp->Num_interior . ' ' . @$dirTemp->NomEstado . ' ' . @$dirTemp->NomMunicipio . ' ' . @$dirTemp->Colonia . ' ' . @$dirTemp->Colonia . ' ' . @$dirTemp->Ciudad . ' ' . @$dirTemp->Localidad;
        } else {
            // $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $idSol1)->first();
            // $rfc = RfcSiralab::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            // $titulo = TituloConsecionSir::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            // $dirTemp = DireccionReporte::where('Id_direccion', $solModel1->Id_direccion)->first();
            // $dirReporte = $dirTemp->Direccion;
        }
        $rfc = RfcSucursal::where('Id_sucursal', $solModel1->Id_sucursal)->first();
        $direccion1 = DireccionReporte::where('Id_direccion',$solModel1->Id_direccion)->first();
        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        $auxPunto = PuntoMuestreoSir::where('Id_punto',$punto->Id_muestreo)->first();
        $tituloConsecion = TituloConsecionSir::where('Id_titulo',$auxPunto->Titulo_consecion)->first();

        $model1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Reporte', 1)->where('Mensual',1)->orderBy('Parametro', 'ASC')->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Reporte', 1)->where('Mensual',1)->orderBy('Parametro', 'ASC')->get();

        @$gasto1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        @$gasto2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $obs1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $obs2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();
        $tempAmbiente1  = TemperaturaMuestra::where('Id_solicitud', $idSol1)->where('Activo',1)->get();
        $tempAmbiente2  = TemperaturaMuestra::where('Id_solicitud', $idSol2)->where('Activo',1)->get();
        $auxTemp = 0; 
        $tempProm1 = 0;
        $tempProm2 = 0;
        foreach ($tempAmbiente1 as $item) {
            $tempProm1 += $item->Promedio;
            $tempProm2 += $tempAmbiente2[$auxTemp]->Promedio;
            $auxTemp++;
        }
        $tempProm1 = $tempProm1 / $auxTemp;
        $tempProm2 = $tempProm2 / $auxTemp;

        
        $phMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->where('Activo',1)->get();
        $phMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->where('Activo',1)->get();

        $auxPh = 0;
        $olor1 = false;
        $olor2 = false;
        $color1 = "";
        $color2 = "";

        foreach ($phMuestra1 as $item) {
            if ($item->Olor == "Si") {
                $olor1 = true;   
            }
            if ($phMuestra2[$auxPh]->Olor == "Si") {
                $olor2 = true;   
            }
            $auxPh++;
        }
        foreach ($phMuestra1 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color1 = $item->Color;
                break;
            }else{
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color',$item->Color)->where('Activo',1)->get(); 
            if ($colorTemp->count() >= (($phMuestra1->count())/2)) {
                $color2 = $item->Color;
                break;
            }else{
                $color2 = $item->Color;
            }
        }

        $proceso1 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol1)->first();
        $proceso2 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol2)->first();
        $numOrden1 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel1->Hijo)->first();
        $numOrden2 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel2->Hijo)->first();
        $firma1 = User::find(14);
        $firma2 = User::find(12);
        $cotModel = DB::table('ViewSolicitud2')->where('Id_cotizacion', $solModel1->Id_cotizacion)->first();
        $tipoReporte = DB::table('categoria001_2021')->where('Id_categoria', $cotModel->Id_reporte2)->first();
        $cliente = Clientes::where('Id_cliente', $solModel1->Id_cliente)->first();
        $reportesInformes = DB::table('ViewReportesInformesMensual')->orderBy('Num_rev', 'desc')->first(); //Condición de busqueda para las configuraciones(Historicos)  
        $reviso = DB::table('users')->where('id', $reportesInformes->Id_reviso)->first();
        $autorizo = DB::table('users')->where('id', $reportesInformes->Id_autorizo)->first();

        @$promGastos = ($gasto1->Resultado2 + $gasto2->Resultado2);
        @$parti1 = $gasto1->Resultado2 / $promGastos;
        @$parti2 = $gasto2->Resultado2 / $promGastos;

        $limitesN = array();
        $limitesC1 = array();
        $limitesC2 = array();
        $limitesC3 = array();
        $ponderado = array();
        $aux = 0;
        $limC1 = 0;
        $limC2 = 0;
        $limP = 0;
        $cont = 0;
        foreach ($model1 as $item) {

            switch ($item->Id_parametro) {
                case 2:
                    if ($item->Resultado2 == 1) {
                        $limC1 = "PRESENTE";
                    } else {
                        $limC1 = "AUSENTE";
                    }
                    if ($model2[$cont]->Resultado2 == 1) {
                        $limC2 = "PRESENTE";
                    } else {
                        $limC2 = "AUSENTE";
                    }
                    break;
                case 97:
                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                    $limP = round($limP);
                    $limC1 = round($item->Resultado2);
                    $limC2 = round($model2[$cont]->Resultado2);
                    break;
                case 14:
                case 110:
                
                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                    $limP = number_format((($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2)), 2, ".", "");
                    break;
                case 64:
                case 358:
                    $aux64 = 0;
                    switch (@$item->Resultado2) {
                        case 499:
                            $limC1 = "< 500";
                            $aux64 = 499;
                            break;
                        case 500:
                            $limC1 = "500";
                            $aux64 = 500;
                            break;
                        case 1000:
                            $limC1 = "1000";
                            $aux64 = 1000; 
                            break;
                        case 1500:
                            $limC1 = "> 1000";
                            $aux64 = 1500;
                            break;
                        default:
                            $limC1 =  number_format(@$item->Resultado2, 2, ".", "");
                            $aux64 = $aux64 + @$item->Resultado2;
                            break;
                    }
                    switch (@$model2[$cont]->Resultado2) {
                        case 499:
                            $limC2 = "< 500";
                            $aux64 = $aux64 + 499;
                            break;
                        case 500:
                            $limC2 = "500";
                            $aux64 = $aux64 + 500;
                            break;
                        case 1000:
                            $limC2 = "1000";
                            $aux64 = $aux64 + 1000;
                            break;
                        case 1500:
                            $limC2 = "> 1000";
                            $aux64 = $aux64 + 1500;
                            break;
                        default:
                            $limC2 =  number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                            $aux64 = $aux64 + @$model2[$cont]->Resultado2;
                            break;
                    }
                    $aux64 = $aux64 / 2;
                    if ($aux64 < 500) {
                        $limP = "< 500";
                    } else if ($aux64 > 1000) {
                        $limP = "> 1000";
                    }else{
                        $limP = number_format(@$aux64, 2, ".", "");;
                    }
                    break;
                    case 67:
                        $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                        if ($item->Resultado2 >= "3500") {
                            $limC1 = "> 3500";
                        }else{
                            $limC1 = round($item->Resultado2);
                        }
                        if ($model2[$cont]->Resultado2 >= "3500") {
                            $limC2 = "> 3500";
                        }else{
                            $limC2 = round($model2[$cont]->Resultado2);
                        }
                        if ($limP >= "3500") {
                            $limP = "> 3500";
                        }else{
                            $limP = round($limP);
                        }
                        break;
                default:
                    if ($item->Resultado2 != NULL || $model2[$cont]->Resultado2 != NULL) {

                        $limAux1 = 0;
                        $limAux2 = 0;
                        if (@$item->Resultado2 < $item->Limite) {
                            @$limAux1 = @$item->Limite;
                        }else{
                            @$limAux1 = @$item->Resultado2;
                        }
                        if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                            @$limAux2 = $item->Limite;
                        }else{
                            @$limAux2 = @$model2[$cont]->Resultado2;
                        }

                        switch ($item->Id_parametro) {
                                    //Redondeo a enteros
                                case 97:
                                case 358:
                                    $limP = round($limP);
                                    $limC1 = round($item->Resultado2); 
                                    $limC2 = round($model2[$cont]->Resultado2);
                                    break;
                                case 67:
                                   $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                    if ($item->Resultado2 > "3500") {
                                        $limC1 = "> 3500";
                                    }else{
                                        $limC1 = round($item->Resultado2);
                                    }
                                    if ($model2[$cont]->Resultado2 > "3500") {
                                        $limC2 = "> 3500";
                                    }else{
                                        $limC2 = round($model2[$cont]->Resultado2);
                                    }
                                    break;
                                case 26: //gasto
                                    $limP = number_format((($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2)),2);
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    break;
                                    // 3 Decimales
                                case 17: // Arsenico
                                case 231:         
                                case 20: // Cobre
                                case 22: //Mercurio
                                case 25: //Zinc 
                                case 227:
                                case 24: //Plomo
                                case 21: //Cromoa
                                case 264:
                                case 18: //Cadmio
                                case 7:  
                                case 8:
                                case 152: 
                                case 19:
                                case 23:
                                case 113:
                                case 232: //fierro total
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                    }else{
                                        $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "" . number_format(@$item->Limite, 3, ".", "");
                                    }else{
                                        $limP = number_format(@$limP, 3, ".", "");  
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 3, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 3, ".", "");
                                    }
                                    break;
                                case 9:
                                case 10:
                                case 11:
                                case 83:
                                case 12:
                                case 15:
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                    }else{
                                        $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                                case 35:
                                case 134:
                                case 50:
                                case 51:
                                case 78:
                                case 253: 
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                    }else{
                                        $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                    }
                                    if ($limP < $item->Limite) {
                                        $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                        // $limC1 = round(@$item->Resultado2);
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        // $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                        // $limC2 = round(@$model2[$cont]->Resultado2);
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                                default: 
                                    if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.")
                                    {
                                        $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                    }else{
                                        $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                    }

                                    if ($limP < $item->Limite) {
                                        $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                    }else{
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                    if (@$item->Resultado2 < @$item->Limite) {
                                        $limC1 = "< ".$item->Limite;
                                    }else{
                                        $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                        $limC2 = "< ".$item->Limite;
                                    }else{
                                        $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    }
                                    break;
                            }
                    
                    } else { 
                        $limC1 = "-----";
                        $limC2 = "-----";
                        $limP = "-----";
                    }
                    break;
            }
            switch ($item->Id_norma) {
                case 27:
                    @$limNo = DB::table('limite001_2021')->where('Id_categoria', $tipoReporte->Id_categoria)->where('Id_parametro', $item->Id_parametro)->get();
                    if ($limNo->count()) {
                        $aux = $limNo[0]->Pm;
                    } else {
                        $aux = "N/A";
                    }
                    break;
                default:

                    break;
            }
            array_push($limitesN, $aux);
            array_push($limitesC1, $limC1);
            array_push($limitesC2, $limC2);
            array_push($ponderado, $limP);
            $cont++;
        }
        
        $campoCompuesto1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $campoCompuesto2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();
        $reportesInformes = DB::table('ViewReportesInformesMensual')->where('deleted_at',null)->orderBy('Num_rev', 'desc')->first(); //Condición de busqueda para las configuraciones(Historicos)  

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
                 Encripta el contenido de la variable, enviada como parametro.
                  */
        $folioSer1 = $solModel1->Id_solicitud;
        $folioEncript1 =  openssl_encrypt($folioSer1, $method, $clave, false, $iv);
        $folioSer2 = $solModel1->Id_solicitud;
        $folioEncript2 =  openssl_encrypt($folioSer2, $method, $clave, false, $iv);



        $data = array(
            'folioEncript1' => $folioEncript1,
            'folioEncript2' => $folioEncript2,
             'olor1' => $olor1,
             'olor2' => $olor2,
            'color2' => $color2,
            'color1' => $color1,
            'tempProm1' => $tempProm1,
            'tempProm2' => $tempProm2,
            'obs1' => $obs1,
            'obs2' => $obs2,
            'tempAmbiente1' => $tempAmbiente1,
            'tempAmbiente2'=> $tempAmbiente2,
            'direccion1' => $direccion1,
            'tituloConsecion' => $tituloConsecion,
            'campoCompuesto1' => $campoCompuesto1,
            'campoCompuesto2' => $campoCompuesto2,
            // 'dirReporte' => $dirReporte,
            'ponderado' => $ponderado,
            'rfc' => $rfc,
            // 'titulo' => $titulo,
            'tipoReporte' => $tipoReporte,
            'cliente' => $cliente,
            'limitesN' => $limitesN,
            'limitesC1' => $limitesC1,
            'limitesC2' => $limitesC2,
            'tipo' => $tipo,
            'punto' => $punto,
            'model1' => $model1,
            'model2' => $model2,
            'gasto1' => $gasto1,
            'gasto2' => $gasto2,
            'proceso1' => $proceso1,
            'proceso2' => $proceso2,
            'numOrden1' => $numOrden1,
            'numOrden2' => $numOrden2,
            'solModel1' => $solModel1,
            'solModel2' => $solModel2,
            'firma1' => $firma1,
            'firma2' => $firma2,
            'reportesInformes' => $reportesInformes,
        );




        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.mensual.001.bodyInforme', $data);
        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.mensual.001.headerInforme', $data);
        $htmlFooter = view('exports.informes.mensual.001.footerInforme', $data);

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'I');
    }
    public function exportPdfInformeMensualCampo($idSol1, $idSol2)
    {
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        // Hace los filtros para realizar la comparacion
        $solModel1 = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol1)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol2)->first();
        if ($solModel1->Siralab == 1) {
            $punto = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $idSol1)->first();
            $rfc = RfcSiralab::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            $titulo = TituloConsecionSir::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            $dirTemp = DB::table('ViewDireccionSir')->where('Id_cliente_siralab', $solModel1->Id_direccion)->first();
            $dirReporte = @$dirTemp->Calle . ' ' . @$dirTemp->Num_exterior . ' ' . @$dirTemp->Num_interior . ' ' . @$dirTemp->NomEstado . ' ' . @$dirTemp->NomMunicipio . ' ' . @$dirTemp->Colonia . ' ' . @$dirTemp->Colonia . ' ' . @$dirTemp->Ciudad . ' ' . @$dirTemp->Localidad;
        } else {
            $punto = DB::table('ViewPuntoGenSol')->where('Id_solicitud', $idSol1)->first();
            $rfc = RfcSiralab::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            $titulo = TituloConsecionSir::where('Id_sucursal', $solModel1->Id_sucursal)->first();
            $dirTemp = DireccionReporte::where('Id_direccion', $solModel1->Id_direccion)->first();
            $dirReporte = $dirTemp->Direccion;
        }
        $model1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        $gasto1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $gasto2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();

        $proceso1 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol1)->first();
        $proceso2 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol2)->first();
        $numOrden1 =  DB::table('ViewSolicitud')->where('Id_solicitud', $solModel1->Hijo)->first();
        $numOrden2 =  DB::table('ViewSolicitud')->where('Id_solicitud', $solModel2->Hijo)->first();
        $firma1 = User::find(14);
        $firma2 = User::find(12);
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $solModel1->Id_cotizacion)->first();
        $tipoReporte = DB::table('categoria001_2021')->where('Id_categoria', $cotModel->Tipo_reporte)->first();
        $cliente = Clientes::where('Id_cliente', $solModel1->Id_cliente)->first();
        $compuesto1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $compuesto2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();

        $promGastos = ($gasto1->Resultado2 + $gasto2->Resultado2);
        $parti1 = $gasto1->Resultado2 / $promGastos;
        $parti2 = $gasto2->Resultado2 / $promGastos;

        $gastoModel1 = GastoMuestra::where('Id_solicitud', $idSol1)->get();
        $sumGasto1 = 0;
        $gastoProm1 = array();
        foreach ($gastoModel1 as $item) {
            if ($item->Activo == 1) {
                $sumGasto1 = $sumGasto1 + $item->Promedio;
            }
        }
        foreach ($gastoModel1 as $item) {
            array_push($gastoProm1, ($item->Promedio / $sumGasto1));
        }

        $gastoModel2 = GastoMuestra::where('Id_solicitud', $idSol2)->get();
        $sumGasto2 = 0;
        $gastoProm2 = array();
        foreach ($gastoModel2 as $item) {
            $sumGasto2 = $sumGasto2 + $item->Promedio;
        }
        foreach ($gastoModel2 as $item) {
            array_push($gastoProm2, ($item->Promedio / $sumGasto2));
        }
        $ph1 = PhMuestra::where('Id_solicitud', $idSol1)->get();
        $ph2 = PhMuestra::where('Id_solicitud', $idSol2)->get();
        $tempModel1 = TemperaturaMuestra::where('Id_solicitud', $idSol1)->get();
        $tempModel2 = TemperaturaMuestra::where('Id_solicitud', $idSol2)->get();
        $grasasModel1 = CodigoParametros::where('Id_solicitud', $idSol1)->where('Id_parametro', 13)->get();
        $grasasModel2 = CodigoParametros::where('Id_solicitud', $idSol2)->where('Id_parametro', 13)->get();
        $colModel1 = CodigoParametros::where('Id_solicitud', $idSol1)->where('Id_parametro', 78)->get();
        $colModel2 = CodigoParametros::where('Id_solicitud', $idSol2)->where('Id_parametro', 78)->get();

        $temp = 0;
        $promPh1 = 0;
        $promPh2 = 0;
        $promTemp1 = 0;
        $promTemp2 = 0;
        $promGa1 = 0;
        $promGa2 = 0;
        $promCol1 = 0;
        $promCol2 = 0;
        for ($i = 0; $i < $ph1->count(); $i++) {
            if ($ph1[$temp]->Activo == 1) {
                @$promPh1 = $promPh1 + ($ph1[$temp]->Promedio * $gastoProm1[$i]);
                @$promPh2 = $promPh2 + ($ph2[$temp]->Promedio * $gastoProm2[$i]);
                @$promTemp1 = $promTemp1 + ($tempModel1[$temp]->Promedio * $gastoProm1[$i]);
                @$promTemp2 = $promTemp2 + ($tempModel2[$temp]->Promedio * $gastoProm2[$i]);
                @$promGa1 = $promGa1 + ($grasasModel1[$temp]->Resultado * $gastoProm1[$i]);
                @$promGa2 = $promGa2 + ($grasasModel2[$temp]->Resultado * $gastoProm2[$i]);
                @$promCol1 = $promCol1 + $colModel1[$temp]->Resultado;
                @$promCol2 = $promCol2 + $colModel2[$temp]->Resultado;
                $temp++;
            }
        }

        $limPh = DB::table('limite001_2021')->where('Id_parametro', 14)->first();
        $limTemp = DB::table('limite001_2021')->where('Id_parametro', 97)->first();
        $limCol = DB::table('limite001_2021')->where('Id_parametro', 35)->first();
        $limGa = DB::table('limite001_2021')->where('Id_parametro', 13)->first();

        $data = array(
            'gastoModel1' => $gastoModel1,
            'gastoModel2' => $gastoModel2,
            'dirReporte' => $dirReporte,
            'compuesto1' => $compuesto1,
            'compuesto2' => $compuesto2,
            'limCol' => $limCol,
            'limGa' => $limGa,
            'promGa1' => $promGa1,
            'promGa2' => $promGa2,
            'promCol1' => $promCol1,
            'promCol2' => $promCol2,
            'promTemp1' => $promTemp1,
            'promTemp2' => $promTemp2,
            'limTemp' => $limTemp,
            'limPh' => $limPh,
            'promPh1' => $promPh1,
            'promPh2' => $promPh2,
            'colModel1' => $colModel1,
            'colModel2' => $colModel2,
            'grasasModel1' => $grasasModel1,
            'grasasModel2' => $grasasModel2,
            'tempModel1' => $tempModel1,
            'tempModel2' => $tempModel2,
            'ph1' => $ph1,
            'ph2' => $ph2,
            'gastoProm2' => $gastoProm2,
            'gastoProm1' => $gastoProm1,
            'rfc' => $rfc,
            'titulo' => $titulo,
            'tipoReporte' => $tipoReporte,
            'cliente' => $cliente,
            'punto' => $punto,
            'model1' => $model1,
            'model2' => $model2,
            'gasto1' => $gasto1,
            'gasto2' => $gasto2,
            'proceso1' => $proceso1,
            'proceso2' => $proceso2,
            'numOrden1' => $numOrden1,
            'numOrden2' => $numOrden2,
            'solModel1' => $solModel1,
            'solModel2' => $solModel2,
            'firma1' => $firma1,
            'firma2' => $firma2,
        );




        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.mensual.campo.bodyInforme', $data);
        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.mensual.001.headerInforme', $data);
        $htmlFooter = view('exports.informes.mensual.001.footerInforme', $data);

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'I');
    }

    public function pdfComparacion2($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $idSol2 = 0;
        $punto2 = 0;
        if ($solModel->Siralab == 1) {
            $punto = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $idSol)->first();
            $punto2 = DB::table('ViewPuntoMuestreoSolSir')->where('Id_muestreo', $punto->Id_muestreo)->where('Id_solicitud', '<', $idSol)->orderBy('Id_solicitud', 'DESC')->get();
        } else {
            $punto = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud', $idSol)->first();
            $punto2 = DB::table('ViewPuntoMuestreoGen')->where('Id_muestreo', $punto->Id_muestreo)->where('Id_solicitud', '<', $idSol)->orderBy('Id_solicitud', 'DESC')->get();
        }
        $idSol2 = $punto2[1]->Id_solicitud;
        $solModel2 = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol2)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);

        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach ($model as $item) {
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach ($simbolParam as $item) {
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();

        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();

        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) {
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();

            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) { //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }

                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if (!is_null($gModel)) {
            foreach ($gModel as $item) {
                if ($item->Promedio == null) {
                    $gastosProm += 0;
                } else {
                    $gastosProm += $item->Promedio;
                }
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if (!is_null($tModel)) {
            foreach ($tModel as $item) {
                if ($item->Promedio == null) {
                    $tProm += 0;
                } else {
                    $tProm += $item->Promedio;
                }
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if (!is_null($phModel)) {
            foreach ($phModel as $item) {
                if ($item->Promedio == null) {
                    $phProm += 0;
                } else {
                    $phProm += $item->Promedio;
                }
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();
        $limitesC = array();

        //Almacena los límites de la Norma
        $limitesNorma = array();

        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();
            $paramLim = Limite001::where('Id_parametro', $item->Id_parametro)->first();

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            } else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . round($limiteC->Limite, 3);

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, @$paramLim->Prom_Mmax);
            array_push($resultsParam, $item->Resultado);
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $idSol2)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;

        if ($gastosModelLength > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        }

        //Calcula Gasto LPS2*********************************************************************     

        $gastosModel2 = GastoMuestra::where('Id_solicitud', $idSol2)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if ($gastosModelLength2 > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada

        $gModel2 = GastoMuestra::where('Id_solicitud', $idSol2)->get();
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if ($gModelLength2 > 0) {
            foreach ($gModel2 as $item) {
                if ($item->Promedio == null) {
                    $gastosProm2 += 0;
                } else {
                    $gastosProm2 += $item->Promedio;
                }
            }

            $gastosProm2 /= $gModelLength2;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $idSol2)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if ($tModelLength2 > 0) {
            foreach ($tModel2 as $item) {
                if ($item->Promedio == null) {
                    $tProm2 += 0;
                } else {
                    $tProm2 += $item->Promedio;
                }
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $idSol2)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if ($phModelLength2 > 0) {
            foreach ($phModel2 as $item) {
                if ($item->Promedio == null) {
                    $phProm2 += 0;
                } else {
                    $phProm2 += $item->Promedio;
                }
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();
        $resultsParam2 = array();

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            } else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . round($limite2C->Limite, 3);

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Id_parametro', 7)->first();

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if (!is_null($solicitudParamDqo)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if (!is_null($solicitudParamDqo2)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      

        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();

        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'limitesNorma', 'lGras', 'lColi', 'lDqo', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Con Comparacion.pdf', 'I');
        //echo $modelDiag;
        //echo implode(" , ", $limiteMostrar);
        //echo implode(" , ", $limiteMostrar2); 
    }


    //SE ACCEDE A TRAVÉS DEL PREFIJO CLIENTES DE LA RUTA
    public function pdfSinComparacionCliente($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);

        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach ($model as $item) {
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach ($simbolParam as $item) {
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();

        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();

        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) {
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();

            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) { //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }

                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if (!is_null($gModel)) {
            foreach ($gModel as $item) {
                if ($item->Promedio == null) {
                    $gastosProm += 0;
                } else {
                    $gastosProm += $item->Promedio;
                }
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if (!is_null($tModel)) {
            foreach ($tModel as $item) {
                if ($item->Promedio == null) {
                    $tProm += 0;
                } else {
                    $tProm += $item->Promedio;
                }
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if (!is_null($phModel)) {
            foreach ($phModel as $item) {
                if ($item->Promedio == null) {
                    $phProm += 0;
                } else {
                    $phProm += $item->Promedio;
                }
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();

        //Almacena ya sea el resultado del parametro o el limite de cuantificacion
        $limitesC = array();

        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Almacena únicamente los límites de cuantificación
        $limitesNorma = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            echo $item->Parametro . "<br>";
            echo $limiteC->Limite . "<br>";

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            } else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . $limiteC->Limite;

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                echo $item->Parametro . "<br>";
                echo $limiteC->Limite . "<br>";

                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, $limiteC->Limite);
            array_push($resultsParam, $item->Resultado);
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;

        if ($gastosModelLength > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        }

        //Calcula Gasto LPS2*********************************************************************     

        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if ($gastosModelLength2 > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada

        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if ($gModelLength2 > 0) {
            foreach ($gModel2 as $item) {
                if ($item->Promedio == null) {
                    $gastosProm2 += 0;
                } else {
                    $gastosProm2 += $item->Promedio;
                }
            }

            $gastosProm2 /= $gModelLength2;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if ($tModelLength2 > 0) {
            foreach ($tModel2 as $item) {
                if ($item->Promedio == null) {
                    $tProm2 += 0;
                } else {
                    $tProm2 += $item->Promedio;
                }
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if ($phModelLength2 > 0) {
            foreach ($phModel2 as $item) {
                if ($item->Promedio == null) {
                    $phProm2 += 0;
                } else {
                    $phProm2 += $item->Promedio;
                }
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();
        $resultsParam2 = array();

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            } else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . number_format($limite2C->Limite, 3, ".", ",");

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if (!is_null($solicitudParamDqo)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if (!is_null($solicitudParamDqo2)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      

        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();

        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.sinComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'lGras', 'lColi', 'lDqo', 'limitesNorma', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.sinComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.sinComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Informe de Resultados Sin Comparacion.pdf', 'D');
        //echo $modelLength;

        //echo implode(" , ", $limitesNorma);
        //echo implode(" , ", $limiteMostrar2);
    }

    public function pdfComparacionCliente($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 74,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        // Hace los filtros para realizar la comparacion
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();
        $solModel2 = DB::table('ViewSolicitud')->where('IdPunto', $solModel->IdPunto)->OrderBy('Id_solicitud', 'DESC')->get();

        //ViewCodigoParametro
        $cont = (sizeof($solModel2) - 1);

        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();
        $modelLength = $model->count();

        $sParam = array();

        foreach ($model as $item) {
            $paramModel = Parametro::where('Id_parametro', $item->Id_parametro)->first();
            $sP = SimbologiaParametros::where('Id_simbologia', $paramModel->Id_simbologia)->first();
            array_push($sParam, $sP->Simbologia);
        }

        //Recupera sin duplicados las simbologías de los parámetros
        $simbolParam = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->get();
        $simbolParam = $simbolParam->unique('Id_simbologia');
        $simbologiaParam = array();

        foreach ($simbolParam as $item) {
            array_push($simbologiaParam, $item->Id_simbologia);
        }

        $model2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->orderBy('Parametro', 'ASC')->get();

        //Formatea la fecha; Por adaptar para el informe sin comparacion
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }

        //Obtiene la norma------------------------
        $norma = Norma::where('Id_norma', $solModel->Id_norma)->first();

        //Obtiene la dirección del reporte---------------------
        $direccion = DireccionReporte::where('Id_direccion', $solModel->Id_direccion)->first();

        //Recupera el nombre del cliente---------------
        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();

        //Recupera el punto de muestreo del informe-----------------
        $solicitudPunto = SolicitudPuntos::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $puntoMuestreo = DB::table('puntos_muestreo')->where('Id_punto', $solicitudPunto->Id_punto)->first();

        //*************************************CALCULO DE CONCENTRACIÓN CUANTIFICADA DE GRASAS*************************************
        //Consulta si existe el parámetro de Grasas y Aceites en la solicitud
        $solicitudParametroGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength = $solicitudParametroGrasas->count();

        $solicitudParametroGrasas2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 14)->get();
        $solicitudParametroGrasasLength2 = $solicitudParametroGrasas2->count();

        $sumaCaudales = 0;
        $sumaCaudales2 = 0;

        //Establece si debe mostrarse o no el promedio ponderado de los caudales
        $limExceed1  = 0;
        $limExceed2  = 0;

        //Límite de grasas
        $limGras = 0;
        $limGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal = 0;

        //Cálculo grasas de la segunda solicitud
        $sumaCaudalesFinal2 = 0;

        //Calcula Grasas para el primer folio
        if ($solicitudParametroGrasasLength > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) {
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales += 0;
                    } else {
                        $sumaCaudales += $item->Promedio;
                    }
                }

                $sumaCaudales = round($sumaCaudales, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal += $item;
                }

                $sumaCaudalesFinal = round($sumaCaudalesFinal, 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal < $limiteGrasas->Limite) {
            $sumaCaudalesFinal = "< " . $limiteGrasas->Limite;
            $limExceed1 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //Calcula Grasas para el segundo folio        
        if ($solicitudParametroGrasasLength2 > 0) { //Encontró grasas

            //Recupera los gastos (caudales) de la solicitud
            $gastosModel = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();

            $gastosModelLength = $gastosModel->count();

            if ($gastosModelLength > 0) { //Encontró grasas
                //Arreglo que almacena el resultado de cada caudal entre la sumatoria de los caudales
                $divCaudalSuma = array();

                //Arreglo que almacena los resultados de las multiplicaciones de divCaudalSuma por el resultado de cada muestra del parámetro
                $multResDivCaudal = array();

                //Paso 1: Sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $sumaCaudales2 += 0;
                    } else {
                        $sumaCaudales2 += $item->Promedio;
                    }
                }

                $sumaCaudales2 = round($sumaCaudales2, 2);

                //Paso 2: División de cada caudal entre la sumatoria de los caudales
                foreach ($gastosModel as $item) {
                    if ($item->Promedio === null) {
                        $div = 0 / $sumaCaudales2;

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    } else {
                        $div = round(($item->Promedio / $sumaCaudales2), 2);

                        array_push(
                            $divCaudalSuma,
                            $div
                        );
                    }
                }

                //Paso 3: Multiplicación de cada divCaudalSuma por el resultado del parametro                                    
                for ($i = 0; $i < $solicitudParametroGrasasLength2; $i++) {
                    $mult = round(($divCaudalSuma[$i] * $solicitudParametroGrasas2[$i]->Resultado), 2);

                    array_push(
                        $multResDivCaudal,
                        $mult
                    );
                }

                //Paso 4: Sumatoria de multResDivCaudal
                foreach ($multResDivCaudal as $item) {
                    $sumaCaudalesFinal2 += $item;
                }

                $sumaCaudalesFinal2 = round(($sumaCaudalesFinal2), 2);
            }
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteGrasas = DB::table('parametros')->where('Id_parametro', 14)->first();

        if ($sumaCaudalesFinal2 < $limiteGrasas->Limite) {
            $sumaCaudalesFinal2 = "< " . $limiteGrasas->Limite;
            $limExceed2 = 1;
            $limGras = $limiteGrasas->Limite;
        }

        //**************************************FIN DE CALCULO DE CONCENTRACION CUANTIFICADA DE GRASAS ******************************

        //************************************** CALCULO DE COLIFORMES FECALES******************************************************
        //Consulta si existe el parámetro de Coliformes Fecales en la solicitud
        $solicitudParametroColiformesFe = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength = $solicitudParametroColiformesFe->count();

        $solicitudParametroColiformesFe2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 13)->get();
        $solicitudParametroColiformesFeLength2 = $solicitudParametroColiformesFe2->count();

        //Establece si debe mostrarse o no el promedio ponderado de los coliformes
        $limExceedColi1  = 0;
        $limExceedColi2  = 0;
        $limColi = 0;

        //Calculo de coliformes de la primera solicitud
        $resColi = 0;

        //Calculo de coliformes de la segunda solicitud
        $resColi2 = 0;

        if ($solicitudParametroColiformesFeLength > 0) { //Encontró coliformes fecales para folio 1
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi < $limiteColi->Limite) {
            $resColi = "< " . $limiteColi->Limite;
            $limExceedColi1 = 1;
            $limColi = $limiteColi->Limite;
        }



        if ($solicitudParametroColiformesFeLength2 > 0) { //Encontró coliformes fecales para folio 2
            $productoColi = 1;

            //Paso 1: Multiplicación de todos los resultados de coliformes fecales
            foreach ($solicitudParametroColiformesFe2 as $item) {
                $productoColi *= $item->Resultado;
            }

            //Paso 2: Raíz a la N cantidad de resultados de coliformes
            $resColi2 = round(pow($productoColi, 1 / $solicitudParametroColiformesFeLength2), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteColi2 = DB::table('parametros')->where('Id_parametro', 13)->first();

        if ($resColi2 < $limiteColi2->Limite) {
            $resColi2 = "< " . $limiteColi2->Limite;
            $limExceedColi2 = 1;
            $limColi = $limiteColi->Limite;
        }
        //************************************** FIN DE CALCULO DE COLIFORMES FECALES*********************************************** 

        //Recupera el gasto promedio para la solicitud dada
        $gModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gModelLength = $gastosModel->count();
        $gastosProm = 0;

        if (!is_null($gModel)) {
            foreach ($gModel as $item) {
                if ($item->Promedio == null) {
                    $gastosProm += 0;
                } else {
                    $gastosProm += $item->Promedio;
                }
            }

            $gastosProm /= $gModelLength;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->get();
        $tModelLength = $tModel->count();
        $tProm = 0;

        if (!is_null($tModel)) {
            foreach ($tModel as $item) {
                if ($item->Promedio == null) {
                    $tProm += 0;
                } else {
                    $tProm += $item->Promedio;
                }
            }

            $tProm /= $tModelLength;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel = PhMuestra::where('Id_solicitud', $idSol)->get();
        $phModelLength = $phModel->count();
        $phProm = 0;

        if (!is_null($phModel)) {
            foreach ($phModel as $item) {
                if ($item->Promedio == null) {
                    $phProm += 0;
                } else {
                    $phProm += $item->Promedio;
                }
            }

            $phProm /= $phModelLength;
        }

        $limiteMostrar = array();
        $limitesC = array();

        //Almacena los límites de la Norma
        $limitesNorma = array();

        //Almacena los resultados de los parametros
        $resultsParam = array();

        //Recupera los límites de cuantificación de los parámetros del primer folio
        foreach ($model as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();
            $paramLim = Limite001::where('Id_parametro', $item->Id_parametro)->first();

            //
            /* switch($item->Id_parametro){
                case 14: 
            } */

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limitesC, number_format($gastosProm, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limitesC, number_format($tProm, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limitesC, number_format($phProm, 2, ".", ","));
            } else if ($item->Resultado < $limiteC->Limite) {
                $limC = "< " . round($limiteC->Limite, 3);

                array_push($limiteMostrar, 1);
                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $limC = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $limC = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $limC = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $limC = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limiteMostrar, 0);
                array_push($limitesC, $limC);
            }

            array_push($limitesNorma, @$paramLim->Prom_Mmax);
            array_push($resultsParam, $item->Resultado);
        }

        //Recupera la fecha de recepción del primer y segundo folio
        $modelProcesoAnalisis1 = ProcesoAnalisis::where('Id_solicitud', $solModel->Id_solicitud)->first();
        $modelProcesoAnalisis2 = ProcesoAnalisis::where('Id_solicitud', $solModel2[0]->Id_solicitud)->first();

        //Calcula Gasto LPS1********************************************************************
        $gastosModel = GastoMuestra::where('Id_solicitud', $solModel->Id_solicitud)->get();
        $gastosModelLength = $gastosModel->count();
        $gastoSum1 = 0;
        $gastoLPS1 = 0;

        if ($gastosModelLength > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel as $item) {
                if ($item->Promedio === null) {
                    $gastoSum1 += 0;
                } else {
                    $gastoSum1 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos
            $gastoLPS1 = $gastoSum1 / $gastosModelLength;
        }

        //Calcula Gasto LPS2*********************************************************************     

        $gastosModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastoSum2 = 0;
        $gastoLPS2 = 0;

        if ($gastosModelLength2 > 0) {
            //Paso 1: Sumatoria de los caudales
            foreach ($gastosModel2 as $item) {
                if ($item->Promedio === null) {
                    $gastoSum2 += 0;
                } else {
                    $gastoSum2 += $item->Promedio;
                }
            }

            //Paso 2: División entre el total de gastos            
            $gastoLPS2 = $gastoSum2 / $gastosModelLength2;
        }
        //*******************************************************************************************  

        //Recupera el gasto promedio para la solicitud dada

        $gModel2 = GastoMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $gModelLength2 = $gastosModel->count();
        $gastosProm2 = 0;

        if ($gModelLength2 > 0) {
            foreach ($gModel2 as $item) {
                if ($item->Promedio == null) {
                    $gastosProm2 += 0;
                } else {
                    $gastosProm2 += $item->Promedio;
                }
            }

            $gastosProm2 /= $gModelLength2;
        }

        //Recupera la temperatura promedio para la solicitud dada
        $tModel2 = TemperaturaMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $tModelLength2 = $tModel2->count();
        $tProm2 = 0;

        if ($tModelLength2 > 0) {
            foreach ($tModel2 as $item) {
                if ($item->Promedio == null) {
                    $tProm2 += 0;
                } else {
                    $tProm2 += $item->Promedio;
                }
            }

            $tProm2 /= $tModelLength2;
        }

        //Recupera el pH promedio para la solicitud dada
        $phModel2 = PhMuestra::where('Id_solicitud', $solModel2[0]->Id_solicitud)->get();
        $phModelLength2 = $phModel2->count();
        $phProm2 = 0;

        if ($phModelLength2 > 0) {
            foreach ($phModel2 as $item) {
                if ($item->Promedio == null) {
                    $phProm2 += 0;
                } else {
                    $phProm2 += $item->Promedio;
                }
            }

            $phProm2 /= $phModelLength2;
        }

        $limiteMostrar2 = array();
        $limites2C = array();
        $resultsParam2 = array();

        /* $solicitudParametros2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Num_muestra', 1)->get();
        $solicitudParametros2Length = $solicitudParametros2->count(); */

        //Recupera los límites de cuantificación de los parámetros
        foreach ($model2 as $item) {
            $limite2C = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Id_parametro == 27) { //Gasto
                array_push($limites2C, number_format($gastosProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 98) { //Temperatura
                array_push($limites2C, number_format($tProm2, 2, ".", ","));
            } else if ($item->Id_parametro == 15) { //pH
                array_push($limites2C, number_format($phProm2, 2, ".", ","));
            } else if ($item->Resultado < $limite2C->Limite) {
                $lim2C = "< " . round($limite2C->Limite, 3);

                array_push($limiteMostrar2, 1);
                array_push($limites2C, $lim2C);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 20) {   //Cianuros
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 13) {   //Coliformes fecales
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 22) {   //Cromo total
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 72) {   //DBO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 7) {   //DQO
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 16) {   //Fosforo
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 14) {   //Grasas y Aceites
                    $lim2C = number_format($item->Resultado, 2, ".", ",");
                } else if ($item->Id_parametro == 17) {   //Huevos de helminto
                    $lim2C = number_format($item->Resultado, 0, ".", ",");
                } else if ($item->Id_parametro == 23) {   //Mercurio
                    $lim2C = number_format($item->Resultado, 4, ".", ",");
                } else if ($item->Id_parametro == 8) {   //Nitratos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 9) {   //Nitritos
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                } else if ($item->Id_parametro == 10 || $item->Id_parametro == 11) {   //Nitrogeno Amoniacal y Nitrogeno Organico
                    $limC2 = number_format($item->Resultado, 2, ".", ",");
                } else {
                    $lim2C = number_format($item->Resultado, 3, ".", ",");
                }

                array_push($limiteMostrar2, 0);
                array_push($limites2C, $lim2C);
            }
            array_push($resultsParam2, $item->Resultado);
        }

        //*************************************** CÁLCULO DQO ********************************************************
        $solicitudParamDqo = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel->Id_solicitud)->where('Id_parametro', 7)->first();

        $solicitudParamDqo2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $solModel2[0]->Id_solicitud)->where('Id_parametro', 7)->first();

        //Establece si debe mostrarse o no el promedio ponderado de los DQO
        $limExceedDqo1  = 0;
        $limExceedDqo2  = 0;

        //Límite de DQO
        $limDqo = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal1 = 0;

        //Cálculo DQO de la segunda solicitud
        $dqoFinal2 = 0;

        //Cálculo DQO del primer folio
        if (!is_null($solicitudParamDqo)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal1 = round(($solicitudParamDqo->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal1 < $limiteDqo->Limite) {
            $dqoFinal1 = "< " . $limiteDqo->Limite;
            $limExceedDqo1 = 1;
            $limDqo = $limiteDqo->Limite;
        }

        //Cálculo DQO del segundo folio
        if (!is_null($solicitudParamDqo2)) {
            $preCalculo = $gastoLPS1 / ($gastoLPS1 + $gastoLPS2);
            $dqoFinal2 = round(($solicitudParamDqo2->Resultado * $preCalculo), 2);
        }

        //Verifica si el resultado es menor al límite de cuantificación del parámetro
        $limiteDqo2 = DB::table('parametros')->where('Id_parametro', 7)->first();

        if ($dqoFinal2 < $limiteDqo2->Limite) {
            $dqoFinal2 = "< " . $limiteDqo2->Limite;
            $limExceedDqo2 = 1;
            $limDqo = $limiteDqo2->Limite;
        }
        //*************************************** FIN DE CÁLCULO DQO *************************************************      

        //Recupera la base del folio 1
        $folio = explode("-", $solModel->Folio_servicio);
        $parte1 = strval($folio[0]);
        $parte2 = strval($folio[1]);

        $numOrden = Solicitud::where('Folio_servicio', $parte1 . "-" . $parte2)->first();

        //Recupera la base del folio 2
        //Recupera la base del folio
        $folio2 = explode("-", $solModel->Folio_servicio);
        $parte12 = strval($folio2[0]);
        $parte22 = strval($folio2[1]);

        $numOrden2 = Solicitud::where('Folio_servicio', $parte12 . "-" . $parte22)->first();

        //Almacena el límite de grasas
        $lGras = $limGrasas->Limite;

        //Almacena el límite coliformes fecales
        $limiteCuantiColi = DB::table('parametros')->where('Id_parametro', 13)->first();
        $lColi = $limiteCuantiColi->Limite;

        //Almacena el límite DQO
        $limiteCuantiDqo = DB::table('parametros')->where('Id_parametro', 7)->first();
        $lDqo = $limiteCuantiDqo->Limite;

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.conComparacion.bodyInformeMensual',  compact('limitesC', 'limites2C', 'limiteMostrar', 'limiteMostrar2', 'sumaCaudalesFinal', 'resColi', 'sumaCaudalesFinal2', 'resColi2', 'dqoFinal1', 'dqoFinal2', 'modelLength', 'solModel', 'model', 'model2', 'limExceed1', 'limExceed2', 'limGras', 'limExceedColi1', 'limExceedColi2', 'limColi', 'limExceedDqo1', 'limExceedDqo2', 'limDqo', 'limitesNorma', 'lGras', 'lColi', 'lDqo', 'resultsParam', 'resultsParam2', 'sParam'));

        //HEADER-FOOTER******************************************************************************************************************
        $htmlHeader = view('exports.informes.conComparacion.headerInformeMensual', compact('solModel', 'solModel2', 'direccion', 'cliente', 'puntoMuestreo', 'norma', 'modelProcesoAnalisis1', 'modelProcesoAnalisis2', 'gastoLPS1', 'gastoLPS2', 'numOrden', 'numOrden2'));
        $htmlFooter = view('exports.informes.conComparacion.footerInformeMensual', compact('solModel', 'simbologiaParam'));

        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        // $mpdf->Output('Informe de Resultados Con Comparacion.pdf', 'I');
        //echo $modelDiag;
        //echo implode(" , ", $limiteMostrar);
        //echo implode(" , ", $limiteMostrar2);
    }


    //---------------------------------------
    public function exportPdfCustodiaInterna($idSol)
    {
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

        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $areaParam = DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $idSol)->where('Id_parametro','!=',64)->where('Reportes', 1)->where('stdArea', '=', NULL)->get();
        // $areaParam = DB::table('ViewEnvaseParametroSol')->where('Id_solicitud', $idSol)->where('Reportes', 1)->where('stdArea', '=', NULL)->get();
        $phMuestra = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get(); 
        $tempArea = array();
        $temp = 0;
        $sw = false;

        //Datos generales
        $area = array();
        $idArea = array();
        $responsable = array();
        $numRecipientes = array();
        $fechasSalidas = array();
        $stdArea = array();
        $firmas = array();
        $idParametro = array();
        foreach ($areaParam as $item) {
            $sw = false;
            $valParametro = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', $item->Id_parametro)->where('Cancelado',0)->get();
            if ($valParametro->count()) {
                for ($i = 0; $i < sizeof($tempArea); $i++) {
                    if ($item->Id_area == $tempArea[$i]) {
                        $sw = true;
                    }
                }
                if ($sw != true) {
                    $auxArea = DB::table('areas_lab')->where('Id_area', $item->Id_area)->first();
                    $user = DB::table('users')->where('id', $auxArea->Id_responsable)->first();
                    if (@$item->Id_areaAnalisis == 12 || @$item->Id_areaAnalisis == 6 || @$item->Id_areaAnalisis == 13 || @$item->Id_areaAnalisis == 3) {
                        if (@$item->Id_parametro != 16) {
                            if ($model->Id_servicio != 3) {
                                array_push($numRecipientes, $phMuestra->count());
                            } else {
                                array_push($numRecipientes, $model->Num_tomas);
                            }
                        }else{
                            array_push($numRecipientes, 1);    
                        }

                        array_push($stdArea, 1);
                    } else {
                        array_push($numRecipientes, 1);
                        array_push($stdArea, 0);
                    }

                    $paramTemp = Parametro::find($item->Id_parametro);
                    // var_dump($temp->Id_area);
                    $fechaTemp = '';
                    // echo "<br>";
                    // echo "Parametro: ".$paramTemp->Parametro."Area ".$paramTemp->Id_area . "Otra area: ".$item->Area. " Id area: ".$item->Id_area;
                    switch ($paramTemp->Id_area) {
                        case 2: // Metales
                            $modelDet = DB::table('ViewLoteDetalle')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                            if ($modelDet->count()) {
                                $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                $fechaTemp = $loteTemp->Fecha;
                            } else {
                                $fechaTemp = "";
                            }
                            break;
                        case 17: // Metales ICP
                            // $modelDet = DB::table('lote_detalle_icp')->where('Id_codigo', $model->Folio_servicio)->where('Id_control', 1)->where('Id_parametro', $item->Parametro)->get();
                            $modelDet = DB::table('lote_detalle_icp')->where('Id_control', 1)->where('Id_codigo', $model->Folio_servicio)->where('Id_parametro', $item->Id_parametro)->get();
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
                            switch ($item->Id_parametro) {
                                case 5: // DBO
                                case 71:
                                    $modelDet = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 12: // Coliformes  
                                case 134:
                                case 135:
                                case 133:
                                case 35:
                                case 137:
                                case 51:
                                    $modelDet = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 16: // H.H
                                    $modelDet = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 78: // E.Coli
                                    $modelDet = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 253:
                                    $modelDet = DB::table('ViewLoteDetalleEnterococos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                default:
                                    $modelDet = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
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
                            switch ($item->Id_parametro) {
                                case 6: // DQO
                                    $modelDet = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case "218":
                                    $modelDet = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 9:
                                case 10:
                                case 11:
                                    $modelDet = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                default:
                                    $modelDet = DB::table('ViewLoteDetallePotable')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
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
                            $modelDet = DB::table('ViewLoteDetalleGA')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                            if ($modelDet->count()) {
                                $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                $fechaTemp = $loteTemp->Fecha;
                            } else {
                                $fechaTemp = "";
                            }
                            break;
                        case 15: // Solidos
                            $modelDet = DB::table('ViewLoteDetalleSolidos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                            if ($modelDet->count()) {
                                $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                $fechaTemp = $loteTemp->Fecha;
                            } else {
                                $fechaTemp = "";
                            }
                            break;
                        case 16: // Espectrofotonetria
                            $modelDet = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                            if ($modelDet->count()) {
                                $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                $fechaTemp = $loteTemp->Fecha;
                            } else {
                                $fechaTemp = "";
                            }

                            break;
                        case 5: // FQ
                            switch ($item->Id_parametro) {
                                case 5: // DBO
                                case 71:
                                    $modelDet = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                default:
                                    $modelDet = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                            }
                            if ($modelDet->count()) {
                                $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                $fechaTemp = $loteTemp->Fecha;
                            } else {
                                $fechaTemp = "";
                            }
                            // echo $item->Id_parametro."<br>";
                            // echo "Lote:".$loteTemp->Id_lote."<br>";
                            // echo $fechaTemp."<br>";
                            break;
                        case 8: // potable
                            switch ($item->Id_parametro) {
                                case 108: // N Amoniacal
                                    $modelDet = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 14:
                                case 110:
                                case 98:
                                    $modelDet = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    break;
                                case 103:
                                    $modelDet = DB::table('ViewLoteDetalleDureza')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
                                    echo "Entro dure";
                                    break;
                                case 64:
                                case 358:

                                    break;
                                default:
                                    $modelDet = DB::table('ViewLoteDetallePotable')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
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
                            $modelDet = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
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
                    array_push($idArea, $paramTemp->Id_area);
                    array_push($idParametro, $item->Id_parametro);
                    array_push($responsable, $user->name);
                    array_push($firmas, $user->firma);
                }
            }
        }

        // var_dump($fechasSalidas);


        $paramResultado = DB::table('ViewCodigoParametro')
            ->where('Id_solicitud', $idSol)
            ->where('Id_parametro', '!=', 26)
            ->where('Cadena', 1)
            ->where('Id_area','!=',9)
            ->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
            
        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $idSol)->first();
        $resInfo = array();
        $resTemp = 0;
        foreach ($paramResultado as $item) {
            $resTemp = 0;
            if ($item->Cancelado != 1) {     
            switch ($item->Id_parametro) {
                case 12:
                case 13:
                case 35:
                case 253:
                case 137:
                case 51:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        if ($item->Resultado < $item->Limite) {
                            $resTemp = "< " . $item->Limite;
                        } else {
                            $resTemp = $item->Resultado;
                        }
                    }
                    break;
                case 135:
                case 78:
                case 134:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        if ($item->Resultado > 0) {
                            $resTemp = $item->Resultado;
                        } else {
                            $resTemp = "" . $item->Limite;
                        }
                    }
                    break;
                case 3:
                case 4:
                // case 13: // g y a
                case 6: //DQO
                case 5: //DBO
                case 71:
                case 9: //nitrogeno amoniacal
                case 83: //kejendal
                case 10: //organico
                case 11: //nitrogeno total
                case 15: //Fosforo
                case 251:
                case 77:
                // case 152:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    }else{
                        if ($item->Resultado2 < $item->Limite) {
                            $resTemp = "< " . $item->Limite;
                        } else {
    
                            // $resTemp = round($item->Resultado2, 2);
                            $resTemp = number_format(@$item->Resultado2, 2, ".", "");
                        }
                    }
                  
                    break;
                case 152:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    }else{
                        if ($item->Resultado2 < $item->Limite) {
                            $resTemp = "< " . $item->Limite;
                        } else {
    
                            // $resTemp = round($item->Resultado2, 2);
                            $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                        }
                    }
                  
                    break;
                case 133:
                   
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    }else{
                        if ($item->Resultado2 < $item->Limite) {
                            $resTemp = "< " . $item->Limite;
                        } else {
    
                            // $resTemp = round($item->Resultado2, 2);
                            $resTemp = number_format(@$item->Resultado2, 1, ".", "");
                        }
                    }
                    break;
                case 22:
                case 7:
                case 8:
                case 23: //niquel
                case 24: //plomo total
                case 122:
                case 106: //nitratos
                case 124:
                case 114:
                case 96:
                case 95:
                case 243: //sulfatos
                case 17: //arsenico total
                case 113:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    }else{
                        if ($item->Resultado2 < $item->Limite) {
                            $resTemp = "< " . number_format(@$item->Limite, 3, ".", "");
                        } else {
                            $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                        }
                    }

                    break;
                case 80:
                case 105:
                case 121: // Fluoruros 
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        }else{
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . number_format(@$item->Limite, 3, ".", "");
                            } else {
                                $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                        }
    
                        break;
                case 2:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        if ($item->Resultado2 == 1 || $item->Resultado2 == "PRESENTE") {
                            $resTemp = "PRESENTE";
                        } else {
                            $resTemp = "AUSENTE";
                        }
                    }
                    break;
                case 14: // ph
                case 110:
                case 64:
                    switch ($model->Id_norma) {
                        case 1:
                        case 27: 
                            if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                                $resTemp = "----";
                            } else {
                                $resTemp = number_format(@$item->Resultado2, 2, ".", "");
                                
                            }
                            break;
                        default:
                            if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                                $resTemp = "----";
                            } else {
                                $resTemp = number_format(@$item->Resultado2, 1, ".", "");
                            }
                            break;
                    }
                    break;
                case 97:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        $resTemp = round($item->Resultado2);
                    }
                    break;
                case 67:
                case 110:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        switch ($item->Id_norma) {
                            case 1:
                            case 27:
                                if ($puntoMuestreo->Condiciones != 1) {
                                    if ($item->Resultado2 >= 3500) {
                                        $resTemp = "> 3500";
                                    }else{
                                        $resTemp = round($item->Resultado2);
                                    }
                                }else{
                                    $resTemp = round($item->Resultado2);
                                }
                               
                                break;
                            
                            default:
                                $resTemp = round($item->Resultado2);
                                break;
                        }
                    }
                    break;
                    case 358:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        switch ($item->Id_norma) {
                            case 1:
                            case 27:
                                switch ($item->Resultado2) {
                                    case 499:
                                        $resTemp = "< 500";
                                        break;
                                    case 500:
                                        $resTemp = "500";
                                        break;
                                    case 1000:
                                        $resTemp = "1000";
                                        break;
                                    case 1500:
                                        $resTemp = "> 1000";
                                        break;
                                    default:
                                        $resTemp =  number_format(@$item->Resultado2, 2, ".", "");    
                                        break;
                                }
                                    break;
                                default:
                                    if ($item->Resultado2 < $item->Limite) {
                                        $limC = "< " . $item->Limite;
                                    }else{
                                        $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    break;
                            }
                        }
                        break;
                default:
                    if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                        $resTemp = "----";
                    } else {
                        if ($item->Resultado2 < $item->Limite) {
                            $resTemp = "< " . $item->Limite;
                        } else {
                            $resTemp = $item->Resultado2;
                        }
                    }
                    break;
            }
            } else {
                $resTemp = "----";
            }

            array_push($resInfo, $resTemp);
        }

        $promGra = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->where('Num_muestra', 1)->get();
        $promGas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 26)->where('Num_muestra', 1)->get();
        $promCol = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 12)->where('Num_muestra', 1)->get();
        $promCol2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 137)->where('Num_muestra', 1)->get();
        $promEco = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 35)->where('Num_muestra', 1)->get();
        $promEnt = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 253)->where('Num_muestra', 1)->get();

        $recepcion = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        $firmaRes = User::where('id', 17)->first();
        // $firmaRes = User::where('id', 4)->first();
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
            'idParametro' => $idParametro,
            'idArea' => $idArea,
            'promCol2' => $promCol2,
            'promGra' => $promGra,
            'promGas' => $promGas,
            'promCol' => $promCol,
            'promEco' => $promEco,
            'promEnt' => $promEnt,
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
    public function custodiaInterna($idSol)
    {
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

        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();

        $recepcion = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        $paramResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Cadena', 1)->orderBy('Parametro', 'ASC')->get();
        $paramResultadoLength = $paramResultado->count();


        $recibidos = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $recibidosLength = $recibidos->count();
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $tempModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempLength = $tempModel->count();
        $promGastos = 0;

        //Promedio temperatura

        $limitesC = array();

        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->where('Reportes', 1)->get();
        $paqueteLength = $paquete->count();


        $fechasSalidas = array();
        foreach ($paquete as $item) {
            // echo "Id:".$item->Id_area. ": ".$item->Area ." ".$item->Parametro."<br>";
            // $modelDet = LoteDetalleColiformes::where('Id_analisis',$idSol)->where('Id_parametro',5)->first();
            $temp = Parametro::find($item->Parametro);
            $valParametro = CodigoParametros::where('Id_solicitud', $idSol)->where('Id_parametro', $item->Id_parametro)->get();
            // var_dump($temp->Id_area);
            $fechaTemp = "";
            if ($valParametro->count()) {
                switch (@$temp->Id_area) {
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
                    case 3:
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
                            case 253:
                                $modelDet = DB::table('ViewLoteDetalleEnterococos')->where('Id_analisis', $idSol)->where('Id_parametro', $item->Parametro)->get();
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
            }
        }
        $promGra = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->where('Num_muestra', 1)->first();
        $promGas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 26)->where('Num_muestra', 1)->get();

        $promCol = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 12)->where('Num_muestra', 1)->get();

        $resInfo = array();
        $resTemp = 0;
        foreach ($paramResultado as $item) {
            $resTemp = 0;
            switch ($item->Id_parametro) {
                case 12:
                case 13:
                case 253:
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

        //$fechaEmision = \Carbon\Carbon::now();        
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        $firmaRes = User::where('id', 35)->first();
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first(); //Condición de busqueda para las configuraciones(Historicos)

        $mpdf->showWatermarkImage = true;

        $htmlInforme = view(
            'exports.campo.cadenaCustodiaInterna.bodyCadena',
            compact('reportesCadena', 'model', 'promGra', 'resInfo', 'promCol', 'promGas', 'fechasSalidas', 'paquete', 'firmaRes', 'paqueteLength', 'norma', 'recibidos', 'recepcion', 'paramResultado', 'paramResultadoLength')
        );

        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        // var_dump($paramResultado);
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'I');
    }

    //***********************************************************SE ACCEDE A TRAVÉS DEL QR DESDE LA RUTA CLIENTES
    public function custodiaInternaCli($idSol)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 20,
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

        $model = DB::table('ViewSolicitud')->where('Id_solicitud', $idSol)->first();

        $paramResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->orderBy('Parametro', 'ASC')->get();
        $paramResultadoLength = $paramResultado->count();

        $recibidos = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $recibidosLength = $recibidos->count();
        $gastosModel2 = GastoMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $gastosModelLength2 = $gastosModel2->count();
        $gastosModel = GastoMuestra::where('Id_solicitud', $idSol)->get();
        $gastosModelLength = $gastosModel->count();
        $tempModel = TemperaturaMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempLength = $tempModel->count();
        $promGastos = 0;

        //Promedio temperatura
        foreach ($gastosModel2 as $item) {
            if ($item->Promedio == NULL) {
                $promGastos += 0;
            } else {
                $promGastos += $item->Promedio;
            }
        }

        $promGastos = $promGastos / $gastosModelLength2;

        $gaMenorLimite = false;

        $limitesC = array();
        $limiteGrasas = "";
        $limiteCGrasa = DB::table('parametros')->where('Id_parametro', 14)->first();
        $paramGrasasResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->first();

        if ($paramGrasasResultado->Resultado < $limiteCGrasa->Limite) {
            $limC = "< " . $limiteCGrasa->Limite;

            $limiteGrasas = $limC;

            $gaMenorLimite = true;
        } else {  //Si es mayor el resultado que el límite de cuantificación            
            $limC = $paramGrasasResultado->Resultado;
            $limiteGrasas = $limC;
        }

        //Calcula el promedio ponderado de las grasas y aceites
        if ($gaMenorLimite === false) {
            $modelParamGrasas = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 14)->get();
            $modelParamGrasasLength = $modelParamGrasas->count();

            //Calcula promedio ponderado de G y A
            $promPonderadoGaArray = 0;
            //Arreglo que contiene los valores de GYA * GASTO por cada fila
            $gaPorGastoArray = array();
            //Contiene la suma de G(GYA * GASTO)
            $sumaG = 0;
            //Arreglo que contiene los valores de GYA * GASTO entre sumaG por cada fila
            $gaPorGastoDivSumaArray = array();
            //Arreglo que contiene los valores de GYA * (GYA*GASTO) / SUMAG
            $gaPorgaGastoDivSumaG = array();

            //Realiza las operaciones GYA * GASTO
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                if ($gastosModel[$i]->Promedio === null) {
                    $gastos = 0;
                } else {
                    $gastos = $gastosModel[$i]->Promedio;
                }

                array_push($gaPorGastoArray, $modelParamGrasas[$i]->Resultado * $gastos);
            }

            //Realiza la suma de G (GYA * GASTO)
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                //Si no tiene un valor guardado lo toma como cero
                $sumaG += $gaPorGastoArray[$i];
            }

            //Realiza las operaciones (GYA*GASTO)/SUMA DE G
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                array_push($gaPorGastoDivSumaArray, $gaPorGastoArray[$i] / $sumaG);
            }

            //Realiza las operaciones GYA * (GYA*GASTO) / SUMAG
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $resultado = ($modelParamGrasas[$i]->Resultado * $gaPorGastoArray[$i]) / $sumaG;
                array_push($gaPorgaGastoDivSumaG, $resultado);
            }

            //Calcula el promedio ponderado de GA
            for ($i = 0; $i < $modelParamGrasasLength; $i++) {
                $promPonderadoGaArray += $gaPorgaGastoDivSumaG[$i];
            }
        }

        $promedioPonderadoGA = "";
        if ($gaMenorLimite === false) {
            $promedioPonderadoGA = number_format($promPonderadoGaArray, 2);
        } else {
            $promedioPonderadoGA = $limiteGrasas;
        }

        $limiteColiformes = "";
        $limiteColiformes = DB::table('parametros')->where('Id_parametro', 13)->first();
        $paramColiformesResultado = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->first();
        $coliformesMenorLimite = false;
        $mediaAritmeticaColi = "";
        $mAritmeticaColi = "";

        if ($paramColiformesResultado->Resultado < $limiteColiformes->Limite) {
            $limC = "< " . $limiteColiformes->Limite;

            $limiteColiformes = $limC;

            $coliformesMenorLimite = true;
        } else {  //Si es mayor el resultado que el límite de cuantificación            
            $limC = $paramColiformesResultado->Resultado;
            $limiteColiformes = $limC;
        }

        //Calcula la media aritmética de los coliformes
        if ($coliformesMenorLimite === false) {
            $modelParamColiformes = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 13)->get();
            $modelParamColiformesLength = $modelParamColiformes->count();
            $res = 0;

            for ($i = 0; $i < $modelParamColiformesLength; $i++) {
                $res += $modelParamColiformes[$i]->Resultado;
            }

            $mediaAritmeticaColi = $res / $modelParamColiformesLength;
        }

        if ($coliformesMenorLimite === false) {
            $mAritmeticaColi = number_format($mediaAritmeticaColi, 2);
        } else {
            $mAritmeticaColi = $limiteColiformes;
        }

        //Calcula el promedio de los promedios del gasto
        $gastoPromSuma = 0;
        foreach ($gastosModel as $item) {
            if ($item->Promedio === null) {
                $gastoPromSuma += 0;
            } else {
                $gastoPromSuma += $item->Promedio;
            }
        }

        $gastoPromFinal = $gastoPromSuma / $gastosModelLength;

        //Promedio de ph
        $promPh = 0;

        foreach ($recibidos as $item) {
            $promPh += $item->Promedio;
        }

        $promPh /= $recibidosLength;

        //Promedio de temperatura
        $promTemp = 0;

        foreach ($tempModel as $item) {
            $promTemp += $item->Promedio;
        }

        $promTemp /= $tempLength;

        //Recupera los límites de cuantificación de los parámetros        
        foreach ($paramResultado as $item) {
            $limiteC = DB::table('parametros')->where('Id_parametro', $item->Id_parametro)->first();

            if ($item->Resultado < $limiteC->Limite) {
                if ($item->Id_parametro == 27) {
                    $limC = number_format($promGastos, 2, ".", ",");
                } else if ($item->Id_parametro == 15) { //pH
                    $limC = number_format($promPh, 1, ".", ",");
                } else if ($item->Id_parametro == 98) { //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                } else {
                    $limC = "< " . $limiteC->Limite;
                }

                array_push($limitesC, $limC);
            } else {  //Si es mayor el resultado que el límite de cuantificación
                if ($item->Id_parametro == 27) { //Gasto
                    $limC = number_format($promGastos, 2, ".", ",");
                }
                if ($item->Id_parametro == 15) { //pH
                    $limC = number_format($promPh, 1, ".", ",");
                } else if ($item->Id_parametro == 98) { //temperatura
                    $limC = number_format($promTemp, 2, ".", ",");
                } else {
                    $limC = $item->Resultado;
                }

                array_push($limitesC, $limC);
            }
        }

        $paquete = DB::table('ViewPlanPaquete')->where('Id_paquete', $model->Id_subnorma)->distinct()->get();
        $paqueteLength = $paquete->count();

        $responsables = array();

        foreach ($paquete as $item) {
            //INSTRUCCIÓN TEMPORAL
            // if (!is_null($item)) {
            //     $responsableArea = AreaLab::where('Area', $item->Area)->first();
            //     $modelResponsable = DB::table('users')->where('id', $responsableArea->Id_responsable)->first();
            //     $responsable = $modelResponsable->name;

            //     array_push($responsables, $responsable);
            // }
        }

        $fechaEmision = \Carbon\Carbon::now();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        //$reportesCadena = DB::table('ViewReportesCadena')->orderBy('Num_rev', 'desc')->first();
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first(); //Condición de busqueda para las configuraciones(Historicos)
        $mpdf->showWatermarkImage = true;

        $htmlInforme = view(
            'exports.campo.cadenaCustodiaInterna.bodyCadena',
            compact('model', 'paquete', 'paqueteLength', 'norma', 'recibidos', 'fechaEmision', 'paramResultado', 'paramResultadoLength', 'limitesC', 'limiteGrasas', 'limiteColiformes', 'responsables', 'promedioPonderadoGA', 'mAritmeticaColi', 'gastoPromFinal', 'reportesCadena')
        );

        $mpdf->WriteHTML($htmlInforme);

        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->Output('Cadena de Custodia Interna.pdf', 'D');
    }

    public function muestrasCanceladas()
    {
        $sol = DB::table('ViewSolicitudGenerada')->where('Id_muestreador','!=',null)->whereDate('Fecha_muestreo', '>=', "2023-08-1")->whereDate('Fecha_muestreo', '<=', "2023-08-15")->get();

        foreach ($sol as $item) {
            $ph = PhMuestra::where('Id_solicitud',$item->Id_solicitud)->where('Activo',1)->where('Promedio',null)->get();

            foreach ($ph as $item2) {
                echo '-------------------------';
                echo '<br>';
                echo 'ID Sol: '.$item2->Id_solicitud;
                echo '<br>';
            }
        }
    }
}
