<?php

namespace App\Http\Controllers\ClientesAcama;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\Clientes;
use App\Models\DireccionReporte;
use App\Models\InformesRelacion;
use App\Models\LoteAnalisis;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\RfcSucursal;
use App\Models\Solicitud;
use App\Models\SolicitudPuntos;
use App\Models\TemperaturaAmbiente;
use App\Models\TipoCuerpo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

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
    public function informederesultados($code)
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
        
        $reportesInformes = array();
        //Opciones del documento PDF

        $reportesInformes = array();
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 15,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        $mpdf->SetWatermarkImage(
            asset('/public/storage/membreteVertical2.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        
        $tipo = 2;

        $model = DB::table('ViewSolicitud2')->where('Folio_servicio', $folioEncript)->get();
        $idPunto = $model[0]->Id_solicitud;
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        $tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);

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

        $cliente = Clientes::where('Id_cliente', $solModel->Id_cliente)->first();
        $rfc = RfcSucursal::where('Id_sucursal', $solModel->Id_sucursal)->first();

        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $idSol)->first();
        $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Reporte',1)->orderBy('Parametro', 'ASC')->get();
        // $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;
        foreach ($auxAmbienteProm as $item) {
            $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
            $auxTem++;
        }
        @$tempAmbienteProm = $tempAmbienteProm / $auxTem;

        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numOrden =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel->Hijo)->first();
        if ($solModel->Id_servicio != 3) {
            $horaMuestreo = \Carbon\Carbon::parse($phCampo[0]->Fecha)->format('H:i');
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
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
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
                    case 110:
                    case 67:
                    case 68:
                    case 125:
                        $limC = round($item->Resultado2, 1);
                        break;
                    case 34:
                        $limC = $item->Resultado2;
                        break;
                    case 135:
                    case 78:
                    case 134:
                        if ($item->Resultado2 > 0) {
                            $limC = $item->Resultado;
                        } else {
                            $limC = "" . $item->Limite;
                        }
                        break;
                    case 5:
                        if ($item->Resultado2 <= $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {

                            $limC = round($item->Resultado2, 2);
                        }
                        break;
                    case 11:
                        if ($item->Resultado2 <= $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = round($item->Resultado2, 2);
                        }
                        break;
                    case 227:
                    case 25:
                        if ($item->Resultado2 <= $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
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
            case 30:
                $firma1 = User::find(14);
                $firma2 = User::find(4);
                break;
            
            default:
                $firma1 = User::find(4);
                $firma2 = User::find(17);
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

    public function seguimientoServicio(){
        return view('clientes.seguimientoServicio');
    }

    public function getFolioServicio(Request $res){
        $modelFolio = DB::table('viewsolicitud2')
        ->where('Folio_servicio', '=', $res->numero_seguimiento)
        ->first();

        if(empty($modelFolio)){
            $data = array("error" => "No se encontró el registro");
            return response()->json($data);
        }
        else{
            $data = array(
                "porcentaje" => "0", //Porcentaje que se va a mostrar en la barra
                "estado_muestreo" => 1, //1 Si la barra de porcentaje va a incluir muestreo, 0 si no
                "orden_servicio" => array
                (
                    "cliente" => "",
                    "norma" => "",
                    "servicio" => "",
                    "siralab" => "",
                    "folio" => ""
                ),
                "muestreo" => "",
                "recepcion" => "",
                "ingreso_lab" => "",
                "impresion" => ""
            );
            if($modelFolio->Id_servicio == 3){
                $data["estado_muestreo"] = 0;
            }
            $data["orden_servicio"]["cliente"] = $modelFolio->Empresa;
            $data["orden_servicio"]["norma"] = $modelFolio->Clave_norma;
            $data["orden_servicio"]["servicio"] = $modelFolio->Servicio;
            if($modelFolio->Siralab == 0){
                $data["orden_servicio"]["siralab"] = 'No';
            }
            else{
                $data["orden_servicio"]["siralab"] = 'Si';
            }
            $data["orden_servicio"]["folio"] = $modelFolio->Folio_servicio;

            if($data["estado_muestreo"] == 1){
                $data["porcentaje"] = "21";
                $modelSolicitudGenerada = DB::table('viewsolicitudgenerada')
                ->where('Folio_servicio', 'LIKE', '%' . $modelFolio->Folio_servicio . '%')
                ->get();

                if(!empty($modelSolicitudGenerada)){
                    $data["muestreo"] = "Capturando puntos de muestreo<br />";
                    $data["porcentaje"] = "40";
                }
            }
            else if($data["estado_muestreo"] == 0){
                $data["porcentaje"] = "25";
            }

            $modelCodigoParametro = DB::table('codigo_parametro')
            ->where('Codigo', 'LIKE', '%' . $modelFolio->Folio_servicio . '%')
            ->get();

            if(!empty($modelCodigoParametro)){
                $data["recepcion"] = "Muestras en laboratorio";
                if($data["estado_muestreo"] == 1){
                    $data["muestreo"] = $data["muestreo"] . "<span class='texto-verde'>Puntos de muestreo capturados<span>";
                    $data["porcentaje"] = "64";
                }
                else if($data["estado_muestreo"] == 0){
                    $data["porcentaje"] = "50";
                }
            }

            $modelProcesoAnalisis = DB::table('proceso_analisis')
            ->where('Folio', 'LIKE', '%' . $modelFolio->Folio_servicio . '%')
            ->get();

            if(!empty($modelProcesoAnalisis)){
                $data["ingreso_lab"] = "Muestras ingresadas<br />";
                if($data["estado_muestreo"] == 1){
                    $data["porcentaje"] = "81";
                }
                else if($data["estado_muestreo"] == 0){
                    $data["porcentaje"] = "74";
                }
            }

            $hayResultado = false;
            $hayResultado2 = true;
            foreach($modelCodigoParametro as $fila){
                if($fila->Resultado != null){
                    $hayResultado = true;
                }
                if($fila->Resultado2 == null){
                    $hayResultado2 = false;
                }
            }
            if($hayResultado == true){
                $data["ingreso_lab"] = $data["ingreso_lab"] . "Analizando muestras<br />";
                $data["porcentaje"] = "89";
            }
            if($hayResultado2 == true){
                $data["ingreso_lab"] = $data["ingreso_lab"] . "<span class='texto-verde'>Muestras analizadas</span>";
                $data["porcentaje"] = "94";
            }

            $todoLiberado = true;
            $todoImpreso = true;
            foreach($modelProcesoAnalisis as $fila){
                if($fila->Liberado == 0){
                    $todoLiberado = false;
                }
                if($fila->Impresion_informe == 0){
                    $todoImpreso = false;
                }
            }
            if($todoLiberado == true){
                $data["impresion"] = "Informe pendiente para impresión<br />";
                $data["porcentaje"] = "97";
            }
            if($todoImpreso == true){
                $data["impresion"] = $data["impresion"] . "<span class='texto-verde'>Informe impreso</span>";
                $data["porcentaje"] = "100";
            }

            return response()->json($data);
        }
    }
    // 156-3/24 con muestreo
    // 128-100/23 sin muestreo

    public function validacionInformeDiario(){
        // $clave  = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        // //Metodo de encriptaciÃ³n
        // $method = 'aes-256-cbc';
        // // Puedes generar una diferente usando la funcion $getIV()
        // $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        // /*
        //  Encripta el contenido de la variable, enviada como parametro.
        //   */
        // $folioEncript = openssl_decrypt($id, $method, $clave, false, $iv);

        // echo "".$folioEncript;

        return view('clientesAcama.validacionInforme');
    }
    public function getFirmaEncriptada(Request $res){
        $clave  = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*
         Encripta el contenido de la variable, enviada como parametro.
          */
        $folioEncript = openssl_decrypt($res->codigo, $method, $clave, false, $iv);

        $data = array(
            'folioEncript' => $folioEncript,
        );
        return response()->json($data);
    }
    // public function getDatosInforme($code){
    //     $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
    //     //Metodo de encriptaciÃ³n
    //     $method = 'aes-256-cbc';
    //     // Puedes generar una diferente usando la funcion $getIV()
    //     $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
    //     /*
    //      Encripta el contenido de la variable, enviada como parametro.
    //       */
    //     $folioEncript = openssl_decrypt($code, $method, $clave, false, $iv);

        
    //     $model = DB::table('ViewSolicitud2')->where('Folio_servicio', $folioEncript)->first();
    //     $direccion = DireccionReporte::where('Id_direccion', $model->Id_direccion)->first();
    //     // $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $model->Id_solicitud)->first();
    //     // $data = array(
    //     //     'model ' => $model,
    //     // );
    //     return view('clientesAcama.datosInforme',compact('model','direccion'));
    // }

    
    public function getDatosInforme($id) {
        $code = $id;
        $id = urldecode($id);
        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        $method = 'aes-256-cbc';
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
     
        $folioEncript = openssl_decrypt($id, $method, $clave, false, $iv);
        if ($folioEncript == "") {
            $folioEncript = openssl_decrypt($code, $method, $clave, false, $iv);   
        }
        $model = DB::table('ViewSolicitud2')->where('Folio_servicio', "LIKE" , '%'.$folioEncript.'%')->first();
        
        if (!$model) {
            abort(404, "No se encontró el folio en la base de datos.");
        }
    
        $direccion = DireccionReporte::where('Id_direccion', $model->Id_direccion)->first();
        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud',  $model->Id_solicitud)->first();
    
        return view('clientesAcama.datosInforme', compact('model', 'direccion','puntoMuestreo'));
    }
    
}
