<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\Parametros;
use App\Models\AreaLab;
use App\Models\CadenaGenerales;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\Clientes;
use App\Models\ClienteSiralab;
use App\Models\CodigoParametros;
use App\Models\Cotizacion;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\ImpresionInforme;
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
use App\Models\Sucursal;
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
use App\Models\MetodoPrueba;
use App\Models\SimbologiaInforme;
use App\Models\Unidad;
use Carbon\Carbon;
// use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\Select;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;
use Illuminate\Support\Facades\File;
use Soap\Url;

class InformesController extends Controller
{
    //
    public function index()
    {
        $tipoReporte = TipoReporte::all();
        $model  = DB::table('ViewProcesoAnalisis')->where('Cancelado', 0)->where('Padre', 1)->orderBy("Id_procAnalisis", "desc")->get();
        // $model = DB::table('ViewProcesoAnalisis')->where('Cancelado', 0)->where('Padre', 1)->whereYear('created_at', 2025)->orderBy('Id_procAnalisis', 'desc')->get();
        return view('informes.informes', compact('tipoReporte', 'model'));
    }
    public function informe2(){
      return view('informes.informes2');
    }
    public function getinforme(Request $request)
    {
        $query = ProcesoAnalisis::where('Folio', 'like', '%/%')
            ->where('Folio', 'not like', '%/%-%')
            ->orderBy('Created_at', 'desc')
            ->with([
                'solicitud.norma:Id_norma,Clave_norma',
                'solicitud.servicio:Id_tipo,Servicio'
            ])->where('Cancelado', 0)
            ->select('Id_solicitud','Folio','Empresa');
    
        // Búsqueda por columna enviada desde JS
        if ($request->filled('column') && $request->filled('value')) {
            $column = $request->input('column');
            $value = $request->input('value');
    
            if (in_array($column, ['Id_solicitud','Folio','Empresa'])) {
                $query->where($column, 'like', "%$value%");
            } elseif ($column === 'Clave_norma') {
                $query->whereHas('solicitud.norma', function($q) use ($value) {
                    $q->where('Clave_norma', 'like', "%$value%");
                });
            } elseif ($column === 'Servicio') {
                $query->whereHas('solicitud.servicio', function($q) use ($value) {
                    $q->where('Servicio', 'like', "%$value%");
                });
            }
        } else {
            // Limitar a últimos 1000 registros si no hay filtro
            $query->limit(1000);
        }
    
        $procesos = $query->get();
    
        $results = $procesos->map(function($p) {
            return [
                'Id_solicitud' => $p->Id_solicitud,
                'Folio'        => $p->Folio,
                'Empresa'      => $p->Empresa,
                'Clave_norma'  => $p->solicitud->norma->Clave_norma ?? null,
                'Servicio'     => $p->solicitud->servicio->Servicio ?? null,
            ];
        });
    
        return response()->json($results);
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
    public function getSolParametro(Request $res)
    {
        $model = DB::table('ViewCodigoParametroSol')->where('Id_solicitud', $res->idPunto)->get();
        $proceso = ProcesoAnalisis::where('Id_solicitud', $res->idPunto)->first();
        $data = array(
            'proceso' => $proceso,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getInformacionPuntosMuestreo(Request $res)
    {
        $modelCodigoInforme = DB::table('viewcodigoinforme')
            // ->where('Codigo', 'LIKE', '%' . $res->folio . '%')
            ->where('Id_solicitud', '=', $res->puntoMuestreo)
            ->where('deleted_at', '=', NULL)
            ->select('Id_solicitud', 'Id_parametro', 'Parametro', 'Unidad', 'Resultado', 'Resultado2','Incertidumbre','Id_codigo')
            ->get();

        $modelSolicitud = DB::table('solicitudes')
            ->where('Id_solicitud', '=', $res->puntoMuestreo)
            ->where('deleted_at', '=', NULL)
            ->first();
        $proceso = ProcesoAnalisis::where('Id_solicitud', $res->puntoMuestreo)->first();
        switch ($modelSolicitud->Id_norma) {
            case 27:
                switch ($modelSolicitud->Id_reporte2) {
                    case 0:
                        foreach ($modelCodigoInforme as $fila) {
                            $fila->Limite_cuantificacion = 'N/A';
                        }
                        break;
                    default:
                        foreach ($modelCodigoInforme as $fila) {
                            $modelLimites = DB::table('limite001_2021')
                                ->where('Id_parametro', '=', $fila->Id_parametro)
                                ->where('Id_categoria', '=', $modelSolicitud->Id_reporte2)
                                ->where('deleted_at', '=', NULL)
                                ->first();

                            // $data = array("model" => $modelLimites);
                            // return response()->json($data);

                            if (!empty($modelLimites)) {
                                if ($modelSolicitud->Id_muestreo == 6) {
                                    $fila->Limite_cuantificacion = $modelLimites->Vi;
                                } else {
                                    $fila->Limite_cuantificacion = $modelLimites->Pd;
                                }
                            } else {
                                $fila->Limite_cuantificacion = 'N/A';
                            }
                        }
                        break;
                }
                break;
            default:
                foreach ($modelCodigoInforme as $fila) {
                    $fila->Limite_cuantificacion = 'N/A';
                }
                break;
        }

        $data = array("model" => $modelCodigoInforme, "proceso" => $proceso,);
        return response()->json($data);

        // foreach($modelCodigoInforme as $fila){
        //     switch($modelSolicitud->Id_norma){
        //         case 27:
        //             switch($modelSolicitud->Id_reporte2){
        //                 case 0:
        //                     $fila->Limite_cuantificacion = 'N.A';
        //                     break;
        //                 default:
        //                     $modelLimites = DB::table('limite001_2021')
        //                     ->where('Id_parametro', '=', $fila->Id_parametro)
        //                     ->where('Id_categoria', '=', $modelSolicitud->Id_reporte2)
        //                     ->where('deleted_at', '=', NULL)
        //                     ->first();

        //                     if($modelSolicitud->Id_muestreo == 6){
        //                         $fila->Limite_cuantificacion = '' . $modelLimites->Vi;
        //                     }
        //                     else{
        //                         $fila->Limite_cauntificacion = '' . $modelLimites->Pd;
        //                     }

        //                     break;
        //             }
        //             break;
        //         default:
        //             $fila->Limite_cuantificacion = 'N.A';
        //             break;
        //     }
        // }
    }
    public function mensual()
    {
        $model = DB::table('ViewSolicitud2')->OrderBy('Id_solicitud', 'DESC')->where('Padre', 0)->get();
        return view('informes.mensual', compact('model'));
    }
    public function getPreReporteMensual(Request $res)
    {
        $solModel = Solicitud::where('Id_solicitud', $res->id1)->first();
        $solModel2 = Solicitud::where('Id_solicitud', $res->id2)->first();
        $sw = false;
        $parametro = array();
        $unidad = array();
        $metodo = array();
        $punto2 = SolicitudPuntos::where('Id_solicitud', $res->id2)->first();
        $punto1 = SolicitudPuntos::where('Id_solicitud', $res->id1)->first();
        if ($punto1->Id_muestreo == $punto2->Id_muestreo) {
            $sw = true;
        }

        if ($sw == true) {
            $model = CodigoParametros::where('Id_solicitud', $res->id1)->get();
            $model2 = CodigoParametros::where('Id_solicitud', $res->id2)->get();
            foreach ($model as $item) {
                $temp = DB::table('ViewParametros')->where('Id_parametro', $item->Id_parametro)->first();
                array_push($parametro, '(' . $temp->Id_parametro . ') ' . $temp->Parametro);
                array_push($unidad, $temp->Unidad);
                array_push($metodo, $temp->Metodo_prueba);
            }
        } else {
            $model = '';
            $model2 = '';
        }

        $data = array(
            'sw' => $sw,
            'unidad' => $unidad,
            'metodo' => $metodo,
            'parametro' => $parametro,
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
            'margin_bottom' => 30,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        // Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        // $mpdf->showWatermarkImage = true;
        $auxSol = Solicitud::where('Id_solicitud', $idSol)->first();
        $model = Solicitud::where('Id_solicitud', $idPunto)->get();
        //aqui le rezo a dios 
        $cotModel = Cotizacion::where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        @$tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);
        $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        $auxNota = "";

        if ($tipo == 1) {
            $auxNota .= "<p>NOTA4: LOS LIMITES MÁXIMOS PERMISIBLES EN ESTE INFORME SON SOLICITADOS POR EL CLIENTE, LOS LIMITES MOSTRADOS PUEDEN VARIAR DE ACUERDO DE ACUERDO A LAS REGULACIONES ESTABLECIDAS POR LAS DEPENDENCIAS, PERMISOS DE DESCARGA, MUNICIPIOS O ESTADOS. ESTOS LIMITES MOSTRADOS SOLO SON DE USO INFORMATIVO Y NO PARA UNA DECLARACIÓN DE CONFORMIDAD ANTE LAS DEPENDENCIAS.</p>";
        }
        
        if ($impresion->count()) {
        } else {
            $simBac = CodigoParametros::where('Id_solicitud', $idPunto)->where('Resultado2', 'LIKE', "%*%")->where('Id_parametro', 32)->get();
            if ($simBac->count()) {
                $auxNota = "<p>* VALOR ESTIMADO</p>";
            }
            $reporteInforme = ReportesInformes::where('Fecha_inicio', '<=', @$model[0]->Fecha_muestreo)->where('Fecha_fin', '>=', @$model[0]->Fecha_muestreo)->get();

            if ($reporteInforme->count()) {
                if ($model[0]->Siralab == 1) {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Nota_siralab' => $reporteInforme[0]->Nota_siralab,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                } else {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                }
            }
            $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        }
        // $reportesInformes = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first(); //Historicos (Informe)
        $aux = true;

        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $cod2 = DB::table('codigo_parametro')->where('Id_solicitud', $idPunto)->pluck('Id_parametro');
        //    dd($cod2);
        $idSol = $idPunto;

        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_informe = 1;
        $proceso->save();

        $aux = DB::table('viewprocesoanalisis')->where('Hijo', $solModel->Hijo)->where('Impresion_informe', 0)->get();
        if ($aux->count() == 0) {
            $proceso = ProcesoAnalisis::where('Id_solicitud', $solModel->Hijo)->first();
            $proceso->Impresion_informe = 1;
            $proceso->save();
        }

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
            $auxPunto = PuntoMuestreoSir::where('Id_punto', $puntoMuestreo->Id_muestreo)->withTrashed()->first();
            $titTemp = TituloConsecionSir::where('Id_titulo', $auxPunto->Titulo_consecion)->withTrashed()->first();
            $tituloConsecion = $titTemp->Titulo;
        }
        // $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area','!=',9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area', '!=', 9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->where('Id_parametro','!=', 173)->get();
        $incerAux = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area', '!=', 9)->where('Reporte', 1)->where('Id_parametro','!=', 173)->where('Incertidumbre','!=','')->get();
        $vidrio = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->where('Id_parametro', 173)->get();
        $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;
        foreach ($auxAmbienteProm as $item) {
            $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
            $auxTem++;
        }
        if ($auxAmbienteProm->count()) { // Val
            @$tempAmbienteProm = round($tempAmbienteProm / $auxTem);
        }
        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
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
        $limitesCon = array();
        $aux = 0;
        $limC = 0;
        $auxCon = "";
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
                switch ($item->Id_parametro) {

                    
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                    case 57:
                    case 59:
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
                            case 9:
                            case 21:
                            case 20:
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
                    case 39:
                        @$limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    case 16:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 0, ".", "");
                        }
                        break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109:
                    case 68:
                    case 57:
                        $limC = $item->Resultado2;
                        break;

                    case 78: 
                        // case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado2 > 8) {
                                $limC = '>' . 8;
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 132:
                    //case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "< 1.1";
                        }
                        break;
                    case 350:
                          $limC = $item->Resultado;
                        // break;
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 137: //AQUI VA NETZA
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 65:
                        if ($item->Resultado2 < 3) {
                            $limC = "< 3";
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 66:
                    case 102:
                        // case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else if ($item->Resultado2 > 3) {
                            $limC = ">3";
                        } else if ($item->Resultado2 == 3) {
                            $limC = "3";
                        } else {
                            $limC = number_format($item->Resultado2, 1, ".", "");
                        }
                        break;

                    case 58:
                    case 271:
                        $limC = $item->Resultado2;
                        break;
                    // case 271:
                    //     $limC = number_format(@$item->Resultado2, 1, ".", "");
                    //     break;
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
                    case 112:
                    case 218:
                    case 253:
                    case 252:
                    case 29:
                    case 51:
                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 71:
                    case 38: //ortofosfato
                    case 36: //fosfatros
                    case 46: //ssv
                    case 137: //Coliformes totales
                    case 251:
                    case 77:
                    case 30:
                    case 90:
                    case 33:
                    case 27:
                    case 28:
                    case 43:
                    case 44:
                    case 45:
                    case 47:
                    case 48:

                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;

                    case 98:
                    case 89:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        // Verificar si $limC es mayor a 10 y cambiar su valor
                        if (is_numeric($limC) && $limC > 10) {
                            $limC = ">10";
                        }

                        break;
                    case 370:
                    case 372:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }

                        if (is_numeric($limC) && $limC > 70) {
                            $limC = ">70";
                        }
                        break;

                    // case 271:
                    // audi
                    case 52:
                    case 250:
                    case 54:
                    case 130:
                    case 95:
                    case 113:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 227:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 3, ".", "");
                        }
                        break;
                    // case 64:
                    case 358:
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                            case 33:
                            case 9:
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
                                } else {
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 64:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC =  number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;

                    case 67: //conductividad  AQUI ME QUEDE 
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                                if ($solicitud->Id_servicio != 3) {
                                    if ($puntoMuestreo->Condiciones != 1) {
                                        if ($item->Resultado2 >= 3500) {
                                            $limC = "> 3500";
                                        } else {
                                            $limC = round($item->Resultado2);
                                        }
                                    } else {
                                        // $limC = round($item->Resultado2);
                                        if ($item->Resultado2 >= 3500) {
                                            $limC = "> 3500";
                                        } else {
                                            $limC = round($item->Resultado2);
                                        }
                                    }
                                } else {
                                    $limC = round($item->Resultado2);
                                }
                                break;
                            default:
                                $limC = round($item->Resultado2);
                                break;
                        }
                        break;
                    case 268: // sulfuros
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;

                            $Resultado =  floatval($item->Resultado2);

                            $limC = number_format(@$Resultado, 2, ".", "");
                        }
                        break;
                    case 210:
                    case 195:
                    case 215:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            // echo "<br> Dato error ".$item->Resultado2;

                            $Resultado =  floatval($item->Resultado2);

                            $limC = number_format(@$Resultado, 4, ".", "");
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
                switch ($solModel->Id_norma) { 
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        //comentarios
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            switch (@$solModel->Id_promedio) {
                                case 1:
                                    $aux = $limNo[0]->Instantaneo;
                                    break;
                                case 2:
                                    $aux = $limNo[0]->PromM;
                                    break;
                                case 3:
                                    $aux = $limNo[0]->PromD;
                                    break;
                                default:
                                    $aux = $limNo[0]->PromD;
                                    break;
                            }
                            // switch ($item->Id_parametro) {
                            //     case 14:
                            //         $rango = explode("-", $aux);
                            //         if (@$rango[0] <= @$item->Resultado2 &&  @$rango >= @$item->Resultado2) {
                            //             $auxCon = "CUMPLE";
                            //         } else {
                            //             $auxCon = "NO CUMPLE";
                            //         }
                            //         break;
                            //     case 2:
                            //         if (@$item->Resultado2 == 1) {
                            //             $auxCon = "NO CUMPLE";
                            //         } else {
                            //             $auxCon = "CUMPLE";
                            //         }
                            //         break;
                            //     default:
                            //         if ($aux != "N.N.") {
                            //             if ($aux != "N/A") {
                            //                 if (@$item->Resultado2 <= $aux) {
                            //                     $auxCon = "CUMPLE";
                            //                 } else {
                            //                     $auxCon = "NO CUMPLE";
                            //                 }
                            //             } else {
                            //                 $auxCon = "N/A";
                            //             }
                            //         } else {
                            //             $auxCon = "N.N.";
                            //         }
                            //         break;
                            // }
                        } else {
                            $aux = "N/A";
                            $auxCon = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_min != "") {
                                $aux = $limNo[0]->Per_min . " - " . $limNo[0]->Per_max;
                            } else {
                                $aux = $limNo[0]->Per_max;
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 7:
                        $limNo = DB::table('limitepnorma_201')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_max != "") {
                                $aux = $limNo[0]->Per_max;
                            } else {
                                $aux = "N/A";
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud->Id_reporte2)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 365:
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
            array_push($limitesCon, $auxCon);
        }
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        //Id Firmas
        //ID 4 Luisita
        //ID 12 Sandy
        //ID 14 Lupita
        //ID 35 Agueda
        //ID 97 Javier A.
        //ID 31 elsa
        //ID 30 Marianita
        //ID 37 Dassa

        switch ($solModel->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                $firma1 = User::find(14);
                //  $firma1 = User::find(35); 
                //  $firma1 = User::find(12); // Reviso
                $firma2 = User::find(31); // Autorizo
                //$firma2 = User::find(12); // Autorizo
                //$firma2 = User::find(14);
                break;
            default:
                //$firma1 = User::find(12); // Reviso
                //$firma1 = User::find(14); //reviso
                $firma1 = User::find(14); //reviso
                // $firma2 = User::find(35); //Autorizo
                $firma2 = User::find(31); //Autorizo
                //$firma2 = User::find(12); // Autorizo

                break;
        }
        //Proceso de Reporte Informe

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*Encripta el contenido de la variable, enviada como parametro.*/
        $folioSer = $solicitud->Folio_servicio;
        // dd($folioSer);
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        // cambio 
        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $firma1->name . ' | ' . $solicitud->Folio_servicio;
        $dataFirma2 = $firma2->name . ' | ' . $solicitud->Folio_servicio;
        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();


        if ($modelProcesoAnalisis->Firma_superviso != "") {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
            $mpdf->showWatermarkImage = true;
        } else {
            $firmaEncript1 = "";
        }
        if ($modelProcesoAnalisis->Firma_autorizo != "") {
            $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);
            $mpdf->showWatermarkImage = true;
        } else {
            $firmaEncript2 = "";
        }
        $data = array(
            'incerAux' => $incerAux,
            'vidrio'=>$vidrio,
            'cod2' => $cod2,
            'firmaEncript1' => $firmaEncript1,
            'firmaEncript2' => $firmaEncript2,
            'norma' => $norma,
            'limitesCon' => $limitesCon,
            'impresion' => $impresion,
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

        $folPadre = Solicitud::where('Id_solicitud', $solicitud->Hijo)->first();
        $primeraLetra = substr($cliente->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();


        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $solicitud->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSol->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $solicitud->Fecha_muestreo . '/' . $folioPadre);


        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-informe.pdf';

        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }

    public function exportPdfInformeVidrio($idSol, $idPunto)
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
        // Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        // $mpdf->showWatermarkImage = true;
        $auxSol = Solicitud::where('Id_solicitud', $idSol)->first();
        $model = Solicitud::where('Id_solicitud', $idPunto)->get();

        $cotModel = Cotizacion::where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        @$tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);

        $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        $auxNota = "";
        if ($impresion->count()) {
        } else {
            $simBac = CodigoParametros::where('Id_solicitud', $idPunto)->where('Resultado2', 'LIKE', "%*%")->where('Id_parametro', 32)->get();
            if ($simBac->count()) {
                $auxNota = "<br> * VALOR ESTIMADO";
            }
            $reporteInforme = ReportesInformes::where('Fecha_inicio', '<=', @$model[0]->Fecha_muestreo)->where('Fecha_fin', '>=', @$model[0]->Fecha_muestreo)->get();
            if ($reporteInforme->count()) {
                if ($model[0]->Siralab == 1) {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Nota_siralab' => $reporteInforme[0]->Nota_siralab,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                } else {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                }
            }
            $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        }


        // $reportesInformes = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first(); //Historicos (Informe)
        $aux = true;

        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $idSol = $idPunto;

        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_informe = 1;
        $proceso->save();

        $aux = DB::table('viewprocesoanalisis')->where('Hijo', $solModel->Hijo)->where('Impresion_informe', 0)->get();
        if ($aux->count() == 0) {
            $proceso = ProcesoAnalisis::where('Id_solicitud', $solModel->Hijo)->first();
            $proceso->Impresion_informe = 1;
            $proceso->save();
        }

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
            $auxPunto = PuntoMuestreoSir::where('Id_punto', $puntoMuestreo->Id_muestreo)->withTrashed()->first();
            $titTemp = TituloConsecionSir::where('Id_titulo', $auxPunto->Titulo_consecion)->withTrashed()->first();
            $tituloConsecion = $titTemp->Titulo;
        }
        // $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area','!=',9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Reporte', 1)
            ->orderBy('Parametro', 'ASC')->where('Id_parametro', 173)->get();
        $incerAux = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->where('Id_parametro', 173)->where('Incertidumbre','!=','')->get();
           
        // $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;
        if ($auxAmbienteProm->count()) {
            foreach ($auxAmbienteProm as $item) {
                $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
                $auxTem++;
            }
            @$tempAmbienteProm = round($tempAmbienteProm / $auxTem);
        }
        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
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
        $limitesCon = array();
        $aux = 0;
        $limC = 0;
        $auxCon = "";
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
                switch ($item->Id_parametro) {
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                    case 57:
                    case 59:
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
                            case 9:
                            case 21:
                            case 20:
                                $limC = number_format(@$item->Resultado2, 2, ".", "");
                                break;
                            default:



                                break;
                        }
                        break;
                    case 110:
                    case 125:
                        $limC = number_format(@$item->Resultado2, 1, ".", "");
                        break;
                    case 26:
                    case 39:
                        @$limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    case 16:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 0, ".", "");
                        }
                        break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109:
                        // case 67:
                    case 68:
                    case 57:
                        $limC = $item->Resultado2;
                        break;

                    case 78:
                        // case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado2 > 8) {
                                $limC = '>' . 8;
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                        // case 132:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 132:
                    case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "< 1.1";
                        }
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 137:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 65:
                    case 66:
                    case 102:
                    case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 58:
                    case 271:
                        $limC = $item->Resultado2;
                        break;
                    // case 271:
                    //     $limC = number_format(@$item->Resultado2, 1, ".", "");
                    //     break;
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
                    case 252:
                    case 29:
                    case 51:

                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 71:
                    case 38: //ortofosfato
                    case 36: //fosfatros
                    case 46: //ssv
                    case 137: //Coliformes totales
                    case 251:
                    case 77:
                    case 30:
                    case 90:
                    case 33:
                        // case 271:
                        // audi
                    case 52:
                    case 250:
                    case 54:
                    case 261:
                    case 130:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 227:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 3, ".", "");
                        }
                        break;
                    // case 64:
                    case 358:
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                            case 33:
                            case 9:
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
                                } else {
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 64:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC =  number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 67: //conductividad
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                                if ($puntoMuestreo->Condiciones != 1) {
                                    if ($item->Resultado2 >= 3500) {
                                        $limC = "> 3500";
                                    } else {
                                        $limC = round($item->Resultado2);
                                    }
                                } else {
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
                switch ($solModel->Id_norma) {
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        //comentarios
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            switch (@$solModel->Id_promedio) {
                                case 1:
                                    $aux = $limNo[0]->Instantaneo;
                                    break;
                                case 2:
                                    $aux = $limNo[0]->PromM;
                                    break;
                                case 3:
                                    $aux = $limNo[0]->PromD;
                                    break;
                                default:
                                    $aux = $limNo[0]->PromD;
                                    break;
                            }
                            switch ($item->Id_parametro) {
                                case 14:
                                    $rango = explode("-", $aux);
                                    if (@$rango[0] <= @$item->Resultado2 &&  @$rango >= @$item->Resultado2) {
                                        $auxCon = "CUMPLE";
                                    } else {
                                        $auxCon = "NO CUMPLE";
                                    }
                                    break;
                                case 2:
                                    if (@$item->Resultado2 == 1) {
                                        $auxCon = "NO CUMPLE";
                                    } else {
                                        $auxCon = "CUMPLE";
                                    }
                                    break;
                                default:
                                    if ($aux != "N.N.") {
                                        if ($aux != "N/A") {
                                            if (@$item->Resultado2 <= $aux) {
                                                $auxCon = "CUMPLE";
                                            } else {
                                                $auxCon = "NO CUMPLE";
                                            }
                                        } else {
                                            $auxCon = "N/A";
                                        }
                                    } else {
                                        $auxCon = "N.N.";
                                    }
                                    break;
                            }
                        } else {
                            $aux = "N/A";
                            $auxCon = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_min != "") {
                                $aux = $limNo[0]->Per_min . " - " . $limNo[0]->Per_max;
                            } else {
                                $aux = $limNo[0]->Per_max;
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 7:
                        $limNo = DB::table('limitepnorma_201')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_max != "") {
                                $aux = $limNo[0]->Per_max;
                            } else {
                                $aux = "N/A";
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud->Id_reporte2)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 365:
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
            array_push($limitesCon, $auxCon);
        }
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        //Id Firmas
        //ID 4 Luisita
        //ID 12 Sandy
        //ID 14 Lupita
        //ID 35 Agueda
        //ID 31 elsa

        switch ($solModel->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                //$firma1 = User::find(14) ; 
                $firma1 = User::find(14);
                //  $firma1 = User::find(12); // Reviso
                $firma2 = User::find(30); // Autorizo
                //$firma2 = User::find(12); // Autorizo
                //$firma2 = User::find(14);
                break;
            default:
                //$firma1 = User::find(12); // Reviso
                //$firma1 = User::find(14); //reviso
                $firma1 = User::find(14); //reviso
                // $firma2 = User::find(35); //Autorizo
                $firma2 = User::find(31); //Autorizo
                //$firma2 = User::find(12); // Autorizo

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
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        // cambio 
        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $firma1->name . ' | ' . $solicitud->Folio_servicio;
        $dataFirma2 = $firma2->name . ' | ' . $solicitud->Folio_servicio;
        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        if ($tempProceso->Supervicion != 0) {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript1 = "";
        }
        if ($tempProceso->Firma_aut != 0) {
            $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript2 = "";
        }


        $data = array(
            'incerAux' => $incerAux, //supongo que sirve para prevenir que le filtre vidrio en los informes
            'url' => url(''), // url?
            'firmaEncript1' => $firmaEncript1, //firma incriptada 1
            'firmaEncript2' => $firmaEncript2, //firma incriptada 2
            'norma' => $norma,  //consulta las norma acorde a la solicitud creo
            'limitesCon' => $limitesCon,
            'impresion' => $impresion,
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
            // 'tipo' => $tipo,
            'rfc' => $rfc,
            'reportesInformes' => $reportesInformes,
        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.vidrio.bodyInforme', $data);
        $htmlHeader = view('exports.informes.vidrio.headerInforme', $data);
        $htmlFooter = view('exports.informes.vidrio.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        //Para la proteccion con contraseña, es el segundo parametro de la función SetProtection, el tercer parametro es una contraseña de propietario para permitir más acciones
        //En el caso del ultimo parámetro es la longitud del cifrado
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';

        // $mpdf->SetProtection(array(), 654, null, 128);
        // Establecer protección con contraseña de usuario y propietario

        $folPadre = Solicitud::where('Id_solicitud', $solicitud->Hijo)->first();
        $primeraLetra = substr($cliente->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();

        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $solicitud->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSol->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $solicitud->Fecha_muestreo . '/' . $folioPadre);

        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-informe.pdf';
        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }
    public function InformeGeneral($idSol, $idPunto, $tipo)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 30,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        // Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        $auxSol = Solicitud::where('Id_solicitud', $idSol)->first();
        $model = Solicitud::where('Id_solicitud', $idPunto)->get();
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $vidrio = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->where('Id_parametro', 173)->get();
      

        //Mis Modelos para datos
         $paTable  = (new ProcesoAnalisis)->getTable();
         $solTable = (new Solicitud)->getTable();
         $spTable  = (new SolicitudPuntos)->getTable();
         $dirTable = (new DireccionReporte)->getTable();
         $phTable = (new PhMuestra)->getTable();
         $NorTable = (new Norma)->getTable();
         $rfcTable = (new RfcSucursal)->getTable();
         $TituloTable = (new TituloConsecionSir)->getTable();
         $sucursalTable = (new SucursalCliente())->getTable();


        $datos = ProcesoAnalisis::where("$paTable.Id_solicitud", $idPunto)
            ->leftJoin($solTable, "$solTable.Id_solicitud", '=', "$paTable.Id_solicitud")
            ->leftJoin($spTable, "$spTable.Id_solicitud", '=', "$paTable.Id_solicitud")
            ->leftJoin($dirTable, "$dirTable.Id_direccion", '=', "$solTable.Id_direccion")
            ->leftJoin($phTable, "$phTable.Id_solicitud", '=', "$paTable.Id_solicitud")
            ->leftJoin($NorTable, "$NorTable.Id_norma", '=', "$solTable.Id_norma")
            ->leftJoin($rfcTable,"$rfcTable.Id_sucursal", '=', "$solTable.Id_sucursal")
            ->leftJoin($TituloTable,"$TituloTable.Id_sucursal", '=', "$solTable.Id_sucursal")
            ->leftJoin($sucursalTable, "$sucursalTable.Id_sucursal", '=', "$solTable.Id_sucursal") 
        
            ->select(
                "$solTable.Id_muestra",
                "$paTable.Firma_superviso",
                "$paTable.Firma_autorizo",
                "$paTable.Empresa",
                "$solTable.Hijo",
                "$paTable.Id_solicitud",
                "$paTable.Obs_proceso",
                "$dirTable.Direccion as Direccion", 
                "$NorTable.Norma as Norma", 
                "$spTable.Punto",
                "$solTable.Fecha_muestreo",
                "$solTable.Atencion",
                "$paTable.Hora_recepcion",
                "$paTable.Hora_entrada",
                "$paTable.Emision_informe",
                "$solTable.Folio_servicio",
                "$solTable.Id_norma",
                "$solTable.Id_servicio",
                "$solTable.Nota_4",
                "$solTable.Num_tomas",
                "$solTable.Siralab",
                "$solTable.Id_reporte",
                "$solTable.Id_sucursal",
                "$sucursalTable.Empresa as Sucursal",
                "$rfcTable.RFC",
                "$TituloTable.Titulo",
                DB::raw("DATE_FORMAT($phTable.Fecha,'%H:%i') as Hora")
            )
            ->first();

        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $datos->Id_reporte)->first();
         $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        $auxNota = "";
        if ($impresion->count()) {
        } else {
            $simBac = CodigoParametros::where('Id_solicitud', $idPunto)->where('Resultado2', 'LIKE', "%*%")->where('Id_parametro', 32)->get();
            if ($simBac->count()) {
                $auxNota = "<br> * VALOR ESTIMADO";
            }
            $reporteInforme = ReportesInformes::where('Fecha_inicio', '<=', @$datos->Fecha_muestreo)->where('Fecha_fin', '>=', @$datos->Fecha_muestreo)->get();
            if ($reporteInforme->count()) {
                if ($datos->Siralab == 1) {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Nota_siralab' => $reporteInforme[0]->Nota_siralab,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                } else {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                }
            }
            $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        }

        //Mis Modelos para datos2 
        $cod = (new CodigoParametros)->getTable();
        $par = (new Parametro)->getTable();
        $usr = (new User)->getTable();
        $met = (new MetodoPrueba)->getTable();
        $uni = (new Unidad)->getTable();
        $sim = (new SimbologiaParametros)->getTable();
        $sim2 = (new SimbologiaInforme)->getTable();
        $sol = (new Solicitud)->getTable();
        $pun = (new SolicitudPuntos)->getTable();

        //consulta Principal datos2
        $datos2 = CodigoParametros::where("$cod.Id_solicitud", $idPunto)->where("$cod.Reporte",1)->whereRaw("(($par.Id_parametro = 173) OR ($cod.Num_muestra = 1))")->Where("$par.Id_area",'!=',9)
            ->leftJoin($par, "$par.Id_parametro", '=', "$cod.Id_parametro")
            ->leftJoin($usr, "$usr.id", '=', "$cod.Analizo")
            ->leftJoin($met, "$met.Id_metodo", '=', "$par.Id_metodo")
            ->leftJoin($uni, "$uni.Id_unidad", '=', "$par.Id_unidad")
            ->leftJoin($sim, "$sim.Id_simbologia", '=', "$par.Id_simbologia")
            ->leftJoin($sim2, "$sim2.Simbologia", '=', "$sim.Simbologia")
            ->leftJoin($sol, "$sol.Id_solicitud", '=', "$cod.Id_solicitud")
            ->leftJoin($pun, "$pun.Id_solicitud", '=', "$cod.Id_solicitud") 
            ->select(
                "$cod.Id_codigo",
                "$cod.Resultado2",
                "$cod.Cadena",
                "$cod.Ph_muestra",
                "$cod.Resultado",
                "$cod.Resultado_aux",
                "$cod.Resultado_aux2",
                "$cod.Resultado_aux3",
                "$cod.Resultado_aux4",
                "$cod.Incertidumbre",
                "$cod.Reporte",
                "$cod.Num_muestra",
                "$par.Parametro",
                "$par.Id_area",
                "$par.Limite",
                "$sol.Id_norma",
                "$sol.Num_tomas",
                "$sol.Id_servicio",
                "$sol.Id_reporte2",
                "$sol.Id_promedio",
                "$pun.Condiciones",
                "$par.Id_parametro",
                 DB::raw("CASE
                                 WHEN $cod.Resultado2 IS NULL THEN '-------' 
                                      -- Parámetros 2, 42, 57, 59
                                 WHEN $par.Id_parametro IN (2,42,57,59) THEN IF($cod.Resultado2 = 1, 'PRESENTE', 'AUSENTE')
                                 
                                 -- Parámetro 97
                                 WHEN $par.Id_parametro = 97 THEN FLOOR($cod.Resultado2 + 0.5)

                            
                                 -- Parámetros 110, 125
                                 WHEN $par.Id_parametro IN (110,125,65,365) THEN ROUND($cod.Resultado2, 1)
                                 
                                 -- Parámetros 26, 39
                                 WHEN $par.Id_parametro IN (26,39) THEN ROUND($cod.Resultado2, 2)
                                 
                                 -- Parámetro 14 según Id_norma
                                 WHEN $par.Id_parametro = 14 AND $sol.Id_norma IN (1,27,2,4,9,21,20) THEN ROUND($cod.Resultado2, 2)
                                 WHEN $par.Id_parametro = 14 AND $sol.Id_norma NOT IN (1,27,2,4,9,21,20) THEN ROUND($cod.Resultado2, 1)
                                 
                                 
                                 -- Parámetro 16
                                 WHEN $par.Id_parametro = 16 AND $cod.Resultado2 < $par.Limite THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro = 16 AND $cod.Resultado2 >= $par.Limite THEN ROUND($cod.Resultado2, 0)

                                  -- Parámetro 619,152  COT
                                WHEN $par.Id_parametro IN (619,152) AND CAST($cod.Resultado2 AS DECIMAL(10,3)) < CAST($par.Limite AS DECIMAL(10,3)) THEN CONCAT('< ', $par.Limite)
                                WHEN $par.Id_parametro IN (619,152) AND CAST($cod.Resultado2 AS DECIMAL(10,3)) > CAST($par.Limite AS DECIMAL(10,3)) THEN ROUND($cod.Resultado2, 3)
  
                                 -- Parámetro 361 solicito lupita 02/10/2025
                                 WHEN $par.Id_parametro = 361 AND $cod.Resultado2 > 3 THEN CONCAT('> 3')
                                 WHEN $par.Id_parametro = 361 AND $cod.Resultado2 < 3 THEN ROUND($cod.Resultado2, 1)

                                 -- Parámetros 78, 135, 134, 132, 133
                                 WHEN $par.Id_parametro IN (78,135,134,132,133) AND $cod.Resultado2 >= 8 THEN '>8'
                                 WHEN $par.Id_parametro IN (78,135,134,132,133) AND $cod.Resultado2 > 0 AND $cod.Resultado2 <= 8 THEN $cod.Resultado
                                 WHEN $par.Id_parametro IN (78,135,134) AND ($cod.Resultado2 IS NULL OR $cod.Resultado2 <= 0) THEN 'NO DETECTABLE'
                                 
                                 -- Parámetro 133 específico
                                 WHEN $par.Id_parametro = 133 AND ($cod.Resultado2 IS NULL OR $cod.Resultado2 <= 0) THEN CONCAT('< ', $par.Limite)
                                 
                                 -- Parámetro 132 específico
                                 WHEN $par.Id_parametro = 132 AND ($cod.Resultado2 IS NULL OR $cod.Resultado2 <= 0) THEN '< 1.1'
                                 
                                 -- Parámetro 65
                                 WHEN $par.Id_parametro = 65 AND $cod.Resultado2 <= 8 THEN '<3'
                                 WHEN $par.Id_parametro = 1 THEN ROUND($cod.Resultado2, 2)
     
                                 -- Parámetro 137
                                 WHEN $par.Id_parametro = 137 AND $cod.Resultado2 < $par.Limite THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro = 137 AND $cod.Resultado2 >= $par.Limite THEN ROUND($cod.Resultado2, 2)

                                -- Si es menor al límite: '< límite'
                                WHEN $par.Id_parametro = 115 AND $cod.Resultado2 < $par.Limite THEN CONCAT('< ', $par.Limite)
                    
                                -- Si es mayor/igual al límite pero menor a 10: un decimal
                                WHEN $par.Id_parametro = 115 AND $cod.Resultado2 >= $par.Limite AND $cod.Resultado2 < 10 THEN ROUND($cod.Resultado2, 1)
                    
                                -- Si es 10 o más: redondeo entero SIN .0
                                WHEN $par.Id_parametro = 115 AND $cod.Resultado2 >= 10 THEN CAST(ROUND($cod.Resultado2) AS CHAR)

                                 
                                 -- Parámetro 66
                                 WHEN $par.Id_parametro = 66 AND $cod.Resultado2 < $par.Limite THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro = 66 AND $cod.Resultado2 > 3 THEN '>3'
                                 WHEN $par.Id_parametro = 66 AND $cod.Resultado2 = 3 THEN '3'
                                 WHEN $par.Id_parametro = 66 THEN ROUND($cod.Resultado2, 1)
                                
                                  -- Parámetro 47 AL 5
                                 WHEN $par.Id_parametro IN (47,48,45,44,43,28,27,33,90,30,77,251,137,46,36,38,71,161,88,58,51,29,252,253,218,112,103,3,4,83,10,9,15,13,35,12,70,6,11,5) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro IN (47,48,45,44,43,28,27,33,90,30,77,251,137,46,36,38,71,161,88,58,51,29,252,253,218,112,103,3,4,83,10,9,15,13,35,12,70,6,11,5) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND(CAST($cod.Resultado2 AS DECIMAL(10,2)), 2)
                              
                                 -- Parámetro 89,98
                                 WHEN $par.Id_parametro IN (89,98) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                 WHEN $par.Id_parametro IN (89,98) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > CAST($par.Limite AS DECIMAL(10,2)) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > 10 THEN '>10'
                                 WHEN $par.Id_parametro IN (89,98) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > CAST($par.Limite AS DECIMAL(10,2)) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < 10 THEN ROUND($cod.Resultado2, 2)
     
                                 -- Parámetro 370,372
                                 WHEN $par.Id_parametro IN (370,372) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                 WHEN $par.Id_parametro IN (370,372) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > CAST($par.Limite AS DECIMAL(10,2)) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > 70 THEN '>70'
                                 WHEN $par.Id_parametro IN (370,372) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) > CAST($par.Limite AS DECIMAL(10,2)) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < 70 THEN ROUND($cod.Resultado2, 2)
     
                                 -- Parámetro  52,250,54,130,95,113,261
                                 WHEN $par.Id_parametro IN (52,250,54,130,95,113,64,261) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro IN (52,250,54,130,95,113,64,261) AND CAST($cod.Resultado2 AS DECIMAL(10,2)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND($cod.Resultado2, 2)   
                                 
                                 -- Parámetro 227 
                                 WHEN $par.Id_parametro = 227 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                 WHEN $par.Id_parametro = 227 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN $cod.Resultado2
                                 
                                 -- Parámetro 25
                                 WHEN $par.Id_parametro = 25 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                 WHEN $par.Id_parametro = 25 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND(CAST($cod.Resultado2 AS DECIMAL(10,3)), 3)
                             
                                 
                                 -- Parámetro 358 según Id_norma
                                 WHEN $par.Id_parametro = 358 AND $sol.Id_norma IN (1,27,9,33) AND $cod.Resultado2 = 499 THEN '< 500'
                                 WHEN $par.Id_parametro = 358 AND $sol.Id_norma IN (1,27,9,33) AND $cod.Resultado2 = 500 THEN '500'
                                 WHEN $par.Id_parametro = 358 AND $sol.Id_norma IN (1,27,9,33) AND $cod.Resultado2 = 1000  THEN '1000'
                                 WHEN $par.Id_parametro = 358 AND $sol.Id_norma IN (1,27,9,33) AND $cod.Resultado2 = 1500 THEN '> 1000'
                                 WHEN $par.Id_parametro = 358 AND $sol.Id_norma IN (1,27,9,33) THEN ROUND($cod.Resultado2, 2)
                                 
                                 -- Parámetro 358 Caso general para otras normas
                                 WHEN $par.Id_parametro = 358 AND CAST($cod.Resultado2 AS DECIMAL(10,2)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite)
                                 WHEN $par.Id_parametro = 358 AND CAST($cod.Resultado2 AS DECIMAL(10,2)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND($cod.Resultado2, 2)
                                 
                                -- Parámetro 67 según Id_norma, Id_servicio y Condiciones
                                WHEN $par.Id_parametro = 67  AND $sol.Id_norma IN (1,27) AND $sol.Id_servicio != 3 AND $pun.Condiciones != 1 AND $cod.Resultado2 >= 3500 THEN '> 3500'
                                WHEN $par.Id_parametro = 67 AND $sol.Id_norma IN (1,27) AND $sol.Id_servicio != 3 AND $pun.Condiciones != 1 AND $cod.Resultado2 < 3500 THEN ROUND($cod.Resultado2, 0)
                                WHEN $par.Id_parametro = 67 AND $sol.Id_norma IN (1,27) AND $sol.Id_servicio != 3 AND $pun.Condiciones = 1 AND $cod.Resultado2 >= 3500 THEN '> 3500'
                                WHEN $par.Id_parametro = 67 AND $sol.Id_norma IN (1,27) AND $sol.Id_servicio != 3 AND $pun.Condiciones = 1 AND $cod.Resultado2 < 3500 THEN ROUND($cod.Resultado2, 0)
                                WHEN $par.Id_parametro = 67  AND $sol.Id_norma IN (1,27) AND $sol.Id_servicio = 3 THEN ROUND($cod.Resultado2, 0)
                                WHEN $par.Id_parametro = 67  AND $sol.Id_norma NOT IN (1,27) THEN ROUND($cod.Resultado2, 0)
                      
                                -- Parámetro 268
                                WHEN $par.Id_parametro = 268 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                WHEN $par.Id_parametro = 268 AND CAST($cod.Resultado2 AS DECIMAL(10,1)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND(CAST($cod.Resultado2 AS DECIMAL(10,2)), 2)   
                                -- Parámetro 210,195,215
                                WHEN $par.Id_parametro IN (210,195,215) AND CAST($cod.Resultado2 AS DECIMAL(10,1)) < CAST($par.Limite AS DECIMAL(10,2)) THEN CONCAT('< ', $par.Limite) 
                                WHEN $par.Id_parametro IN (210,195,215) AND CAST($cod.Resultado2 AS DECIMAL(10,1)) >= CAST($par.Limite AS DECIMAL(10,2)) THEN ROUND(CAST($cod.Resultado2 AS DECIMAL(10,4)), 4)
                                
                                WHEN $par.Id_parametro = 115 AND $cod.Resultado2 >= 10 THEN CAST(ROUND($cod.Resultado2) AS CHAR)
                                WHEN $cod.Resultado2 REGEXP '^[0-9]+(\\.[0-9]+)?$'
                                              THEN (
                                                  CASE 
                                                      WHEN $cod.Resultado2 < $par.Limite 
                                                          THEN CONCAT('< ', $par.Limite)
                                                      ELSE ROUND($cod.Resultado2, 3)
                                                  END
                                              )
                                          ELSE $cod.Resultado2
                                      END AS Resultado2"),
                                "$sim.Simbologia as Simbologia",
                                "$sim2.Descripcion as Descripcion",
                                "$sim2.Id_simbologia_info as Id_simbologia_info",
                                "$met.Clave_metodo as Metodo",
                                "$uni.Unidad as Unidad",
                                DB::raw("CASE 
                                WHEN $usr.Iniciales = 'LASH' THEN '-------' ELSE $usr.Iniciales END as AnalizoInicial"))->orderBy("$par.Parametro",'asc')->get();

                            $datos2 = $datos2->unique('Id_codigo');              
                            $incerAux = $datos2->whereNotNull('Incertidumbre')->count();    
                            // Agrupa por norma para procesar en lotes
                            $grouped = $datos2->groupBy('Id_norma');
                            foreach ($grouped as $norma => $itemsGroup) {
                                $paramIds = $itemsGroup->pluck('Id_parametro')->unique()->values()->all();
                            
                                switch ($norma) {
                                  
                                    case 1:
                                        $limits = DB::table('limitepnorma_001')
                                            ->whereIn('Id_parametro', $paramIds)
                                            ->where('Id_categoria',1 ) //$tipoReporte->Id_detalle
                                            ->get()
                                            ->keyBy('Id_parametro'); // mapa: Id_parametro => row
                            
                                        foreach ($itemsGroup as $item) {
                                            $item->LimiteNorma = isset($limits[$item->Id_parametro])
                                                ? $limits[$item->Id_parametro]->Prom_Dmax
                                                : 'N/A';
                                        }
                                        break;
                            
                                    case 2:
                                        $limits = DB::table('limitepnorma_002')
                                            ->whereIn('Id_parametro', $paramIds)
                                            ->get()
                                            ->keyBy('Id_parametro');
                            
                                        foreach ($itemsGroup as $item) {
                                            if (isset($limits[$item->Id_parametro])) {
                                                $row = $limits[$item->Id_parametro];
                                                // Usa Id_promedio del item si viene; si no, puedes usar $solModel->Id_promedio
                                                $prom = $item->Id_promedio ?? ($solModel->Id_promedio ?? null);
                                                switch ($prom) {
                                                    case 1:  $item->LimiteNorma = $row->Instantaneo; break;
                                                    case 2:  $item->LimiteNorma = $row->PromM; break;
                                                    case 3:  $item->LimiteNorma = $row->PromD; break;
                                                    default: $item->LimiteNorma = $row->PromD; break;
                                                }
                                            } else {
                                                $item->LimiteNorma = 'N/A';
                                            }
                                        }
                                        break;
                            
                                    case 30:
                                        $limits = DB::table('limitepnorma_127')->whereIn('Id_parametro', $paramIds)
                                            ->get()
                                            ->keyBy('Id_parametro');
                            
                                        foreach ($itemsGroup as $item) {
                                            if (isset($limits[$item->Id_parametro])) {
                                                $r = $limits[$item->Id_parametro];
                                                $item->LimiteNorma = ($r->Per_min !== null && $r->Per_min !== '')
                                                    ? ($r->Per_min . ' - ' . $r->Per_max)
                                                    : $r->Per_max;
                                            } else {
                                                $item->LimiteNorma = 'N/A';
                                            }
                                        }
                                        break;
                            
                                    case 7:
                                        $limits = DB::table('limitepnorma_201')
                                            ->whereIn('Id_parametro', $paramIds)
                                            ->get()
                                            ->keyBy('Id_parametro');
                            
                                        foreach ($itemsGroup as $item) {
                                            $item->LimiteNorma = (isset($limits[$item->Id_parametro]) && $limits[$item->Id_parametro]->Per_max)
                                                ? $limits[$item->Id_parametro]->Per_max
                                                : 'N/A';
                                        }
                                        break;
                            
                                    case 27:
                                        $categories = $itemsGroup->pluck('Id_reporte2')->unique()->values()->all();
                            
                                        $limits = DB::table('limite001_2021')
                                            ->whereIn('Id_parametro', $paramIds)
                                            ->whereIn('Id_categoria', $categories)
                                            ->get()
                                            ->keyBy(function ($row) {
                                                return $row->Id_parametro . '|' . $row->Id_categoria;
                                            });
                            
                                        foreach ($itemsGroup as $item) {
                                            $key = $item->Id_parametro . '|' . $item->Id_reporte2;
                                            $item->LimiteNorma = isset($limits[$key])
                                                ? $limits[$key]->Pd
                                                : 'N/A';
                                        }
                                        break;
                            
                                    default:
                                         foreach ($itemsGroup as $item) {
                                             $item->LimiteNorma = '------';
                                         }
                                        foreach ($itemsGroup as $item) {
                                            $key = $item->Id_parametro . '|' . $item->Id_reporte2;
                                            $item->LimiteNorma = isset($limits[$key])
                                                ? $limits[$key]->Pd
                                                : 'N/A';
                                        }
                                        break;
                                }
                            }
                         //dd($datos2);
                        // Mis modelos para la consulta datos
        $solTable   = (new Solicitud)->getTable();
        $paTable  = (new ProcesoAnalisis)->getTable();
        $campoCom   = (new CampoCompuesto)->getTable();
        $tempAm     = (new TemperaturaAmbiente)->getTable();
        $phcampo    = (new PhMuestra)->getTable();
        
        $temp = Solicitud::where("$solTable.Id_solicitud", $idPunto)
        ->leftJoin($campoCom, "$campoCom.Id_solicitud", '=', "$solTable.Id_solicitud")
        ->leftJoin($tempAm, "$tempAm.Id_solicitud", '=', "$solTable.Id_solicitud")
        ->leftJoin($phcampo, "$phcampo.Id_solicitud", '=', "$solTable.Id_solicitud")
        ->select(
            "$solTable.*",
            "$campoCom.*",
            "$tempAm.*",
            "$phcampo.*"
        )->get(); 
        $tempAmbienteProm = round($temp->avg('Temperatura1'));
        $datos3 = Solicitud::where("$solTable.Id_solicitud", $idPunto)
        ->leftJoin($paTable, "$paTable.Id_solicitud", '=', "$solTable.Id_solicitud")
        ->leftJoin($campoCom, "$campoCom.Id_solicitud", '=', "$solTable.Id_solicitud")
        ->leftJoin($tempAm, "$tempAm.Id_solicitud", '=', "$solTable.Id_solicitud")  

        ->leftJoin($phcampo, "$phcampo.Id_solicitud", '=', "$solTable.Id_solicitud") 
        ->select(
            "$solTable.*",
            "$campoCom.*",
            "$tempAm.*",
            "$phcampo.*",
            "$paTable.*"
        )->first(); 
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
        //  dd($datos3->Observaciones);

                 
        //Id Firmas
        //ID 4 Luisita
        //ID 12 Sandy
        //ID 14 Lupita
        //ID 35 Agueda
        //ID 97 Javier A.
        //ID 31 elsa
        //ID 30 Marianita
        //ID 37 Dassa

        switch ($datos->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                $firma1 = User::find(14);
                //  $firma1 = User::find(35); 
                //  $firma1 = User::find(12); // Reviso
                $firma2 = User::find(31); // Autorizo
                
                //$firma2 = User::find(12); // Autorizo
                //$firma2 = User::find(14);
                break;
            default:
                //$firma1 = User::find(12); // Reviso
                //$firma1 = User::find(14); //reviso
                $firma1 = User::find(14); //reviso
                // $firma2 = User::find(35); //Autorizo
                $firma2 = User::find(31); //Autorizo
                //$firma2 = User::find(12); // Autorizo
                break;
        }

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        /*Encripta el contenido de la variable, enviada como parametro.*/
        $folioSer = $datos3->Folio_servicio;
        // dd($folioSer);
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);
        $norma = Norma::where('Id_norma', $datos3->Id_norma)->first();
        // cambio 
        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $firma1->name . ' | ' . $datos3->Folio_servicio;
        $dataFirma2 = $firma2->name . ' | ' . $datos3->Folio_servicio;
        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
       

        if ($datos->Firma_superviso != "") {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
            $mpdf->showWatermarkImage = true;
        } else {
            $firmaEncript1 = "";
        }
        if ($datos->Firma_autorizo != "") {
            $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);
            $mpdf->showWatermarkImage = true;
        } else {
            $firmaEncript2 = "";
        }
  
        $newTitulo = '';

        $data = array(
            'datos'=>$datos,
            'datos2'=> $datos2,
            'datos3'=> $datos3,
            'tempAmbienteProm'=>$tempAmbienteProm,
            'tipo'=>$tipo,
            'newTitulo' => $newTitulo,
            'tipoReporte' => $tipoReporte,
            'incerAux' => $incerAux,
            'firma1' => $firma1,
            'firma2' => $firma2,
            'impresion'=> $impresion,
            'firmaEncript1' => $firmaEncript1,
            'firmaEncript2' => $firmaEncript2,
            'folioEncript' => $folioEncript,
            'solModel'=> $solModel,
            'model'=>$model,
            'norma'=>$norma,
            'numTomas'=>$numTomas,
            'vidrio' => $vidrio,
            );
           
        
       //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes2.diario.bodyInforme', $data);
        $htmlHeader = view('exports.informes2.diario.headerInforme', $data);
        $htmlFooter = view('exports.informes2.diario.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';

        $folPadre = Solicitud::where('Id_solicitud', $datos->Hijo)->first();
        $primeraLetra = substr($datos->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();


        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $datos->Folio_servicio);
        $folioPadre = str_replace('/', '-', $datos->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $datos->Fecha_muestreo . '/' . $folioPadre);


        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-informe.pdf';

        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

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
        // foreach ($model as $item) {
        //     if ($aux == true) {
        //         if ($item->Siralab == 1) {
        //             $model2 = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud', $item->Id_solicitud)->where('Id_muestreo', $idPunto)->get();
        //             if ($item->Id_solicitud == $model2[0]->Id_solicitud) {
        //                 $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $item->Id_solicitud)->first();
        //                 $aux = false;
        //             }
        //         } else {
        //             $model2 = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud', $idPunto)->get();
        //             if ($model2[0]->Id_solicitud == $item->Id_solicitud) {
        //                 $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $item->Id_solicitud)->first();
        //                 $aux = false;
        //             }
        //         }
        //     }
        // }
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
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
        $obsCampo = @$temperaturaC->Observaciones;
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
        $firma2 = User::find(4);
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
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 71,
            'margin_bottom' => 76,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);
        $solModel1 = Solicitud::where('Id_solicitud', $idSol1Temp)->first();
        $solModel2 = Solicitud::where('Id_solicitud', $idSol2Temp)->first();
        $valFol = explode('-', $solModel1->Folio_servicio);
        $valFol2 = explode('-', $solModel2->Folio_servicio);
        if ($valFol[0] < $valFol2[0]) {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();

            $idSol2 = $idSol2Temp;
            $idSol1 = $idSol1Temp;
        } else {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $idSol2 = $idSol1Temp;
            $idSol1 = $idSol2Temp;
        }

        $direccion1 = DireccionReporte::where('Id_direccion', $solModel1->Id_direccion)->first();
        // $direccion2 = DireccionReporte::where('Id_direccion',$solModel2->Id_direccion)->first();

        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        $auxPunto = PuntoMuestreoSir::where('Id_punto', $punto->Id_muestreo)->first();
        @$tituloConsecion = TituloConsecionSir::where('Id_titulo', $auxPunto->Titulo_consecion)->first();
        $rfc = RfcSucursal::where('Id_sucursal', $solModel1->Id_sucursal)->first();

        if ($solModel1->Id_norma == 27) {
            return redirect()->to('admin/informes/exportPdfInformeMensual/001/' . $idSol1 . '/' . $idSol2 . '/' . $tipo);
        }

        //historial (informe Mensual)
        $informesModel = InformesRelacion::where('Id_solicitud', $idSol1)->where('Id_solicitud2', $idSol2)->get();
        if ($informesModel->count()) {
            $informesReporte = DB::table('ViewReportesInformesMensual')->where('Id_reporte', $informesModel[0]->Id_reporte)->first();
        } else {
            $informesReporte = DB::table('ViewReportesInformesMensual')->orderBy('Num_rev', 'desc')->where('deleted_at', null)->first();
            InformesRelacion::create([
                'Id_solicitud' => $idSol1,
                'Id_solicitud2' => $idSol2,
                'Tipo' => $tipo,
                'Id_reporte' => $informesReporte->Id_reporte,
            ]);
        }
        //ViewCodigoParametro
        $reportesInformes = DB::table('ViewReportesInformesMensual')->where('deleted_at', null)->orderBy('Num_rev', 'desc')->first(); //Condición de busqueda para las configuraciones(Historicos)  

        $model1 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model2 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();

        $obs1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $obs2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();
        $auxAmbienteProm1 = TemperaturaAmbiente::where('Id_solicitud', $idSol1)->where('Activo', 1)->get();
        $tempAmbienteProm1 = 0;
        $auxTem1 = 0;
        foreach ($auxAmbienteProm1 as $item) {
            $tempAmbienteProm1 = $tempAmbienteProm1 + $item->Temperatura1;
            $auxTem1++;
        }
        @$tempProm1 = round($tempAmbienteProm1 / $auxTem1);

        $auxAmbienteProm2 = TemperaturaAmbiente::where('Id_solicitud', $idSol2)->where('Activo', 1)->get();
        $tempAmbienteProm2 = 0;
        $auxTem2 = 0;
        foreach ($auxAmbienteProm2 as $item) {
            $tempAmbienteProm2 = $tempAmbienteProm2 + $item->Temperatura1;
            $auxTem2++;
        }
        @$tempProm2 = round($tempAmbienteProm2 / $auxTem2);

        $PhMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->get();
        $PhMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->get();


        $gasto1 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $gasto2 = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $proceso1 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol1)->first();
        $proceso2 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol2)->first();
        $numOrden1 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel1->Hijo)->first();
        $numOrden2 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel2->Hijo)->first();
        // $firma1 = User::find(14);
        $firma1 = User::find(14); //! Reviso
        $firma2 = User::find(31); //! Autorizo
        $cotModel = DB::table('ViewCotizacion')->where('Id_cotizacion', $solModel1->Id_cotizacion)->first();
        $tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        $cliente = Clientes::where('Id_cliente', $solModel1->Id_cliente)->first();

        echo "<br> Fecha1: " . $proceso1->Hora_recepcion;
        echo "<br> Fecha2: " . $proceso2->Hora_recepcion;

        @$promGastos = (round($gasto1->Resultado2, 2) + round($gasto2->Resultado2, 2));
        @$parti1 = round($gasto1->Resultado2, 2) / $promGastos;
        @$parti2 = round($gasto2->Resultado2, 2) / $promGastos;
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
        $limC1PhColor = 0;
        $limC2PhColor = 0;
        $limCPhColor = 0;
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
                    } else {
                        $limP = number_format(@$aux64, 2, ".", "");;
                    }
                    break;
                case 67:
                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                    if ($item->Resultado2 >= "3500") {
                        $limC1 = "> 3500";
                    } else {
                        $limC1 = round($item->Resultado2);
                    }
                    if ($model2[$cont]->Resultado2 >= "3500") {
                        $limC2 = "> 3500";
                    } else {
                        $limC2 = round($model2[$cont]->Resultado2);
                    }
                    if ($limP >= "3500") {
                        $limP = "> 3500";
                    } else {
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
                        } else {
                            @$limAux1 = @$item->Resultado2;
                        }
                        if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                            @$limAux2 = $item->Limite;
                            $limPromAux = 0;
                        } else {
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
                                } else {
                                    $limC1 = round($item->Resultado2);
                                }
                                if ($model2[$cont]->Resultado2 > "3500") {
                                    $limC2 = "> 3500";
                                } else {
                                    $limC2 = round($model2[$cont]->Resultado2);
                                }
                                break;
                            case 26: //gasto
                                $prom = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                $limP = number_format($prom, 2);
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
                            case 355:
                            case 96: //Saam
                            case 114: //saam
                            case 232:
                            case 351:
                            case 360:
                            case 214:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                } else {
                                    $limP = ($limAux1 + $limAux2) / 2;
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "<" . number_format(@$item->Limite, 3, ".", "");
                                } else {
                                    if ($limP == $item->Limite) {
                                        if ($limPromAux == 0) {
                                            $limP = "< " . $item->Limite;
                                        } else {
                                            $limP = number_format(@$limP, 3, ".", "");
                                        }
                                    } else {
                                        $limP = number_format(@$limP, 3, ".", "");
                                    }
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 3, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 3, ".", "");
                                }
                                break;
                            case 9:
                            case 10:
                            case 11:
                            case 83:
                            case 12:
                            case 15:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                } else {
                                    $limP = ($limAux1 + $limAux2) / 2;
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    if ($limP == $item->Limite) {
                                        if ($limPromAux == 0) {
                                            $limP = "< " . $item->Limite;
                                        } else {
                                            $limP = number_format(@$limP, 2, ".", "");
                                        }
                                    } else {
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            case 35:
                            case 134:
                            case 50:
                            case 51:
                            case 78:
                            case 253:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                } else {
                                    $limP = ($limAux1 + $limAux2) / 2;
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    if ($limP == $item->Limite) {
                                        if ($limPromAux == 0) {
                                            $limP = "< " . $item->Limite;
                                        } else {
                                            $limP = number_format(@$limP, 2, ".", "");
                                        }
                                    } else {
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    // $limC1 = round(@$item->Resultado2);
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    // $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    // $limC2 = round(@$model2[$cont]->Resultado2);
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            case 365:
                            case 372:
                            case 370:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                } else {
                                    $limP = ($limAux1 + $limAux2) / 2;
                                }
                                $limCPhColor = number_format(($item->Ph_muestra + $model2[$cont]->Ph_muestra) / 2, 2, ".", "");
                                $limC1PhColor = number_format(@$item->Ph_muestra, 2, ".", "");
                                $limC2PhColor =  number_format(@$model2[$cont]->Ph_muestra, 2, ".", "");
                                if ($limP < $item->Limite) {
                                    $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    if ($limP == $item->Limite) {
                                        if ($limPromAux == 0) {
                                            $limP = "< " . $item->Limite;
                                        } else {
                                            $limP = number_format(@$limP, 2, ".", "");
                                        }
                                    } else {
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            
                            default:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                                } else {
                                    $limP = ($limAux1 + $limAux2) / 2;
                                }

                                if ($limP < $item->Limite) {
                                    $limP = "<" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    if ($limP == $item->Limite) {
                                        if ($limPromAux == 0) {
                                            $limP = "< " . $item->Limite;
                                        } else {
                                            $limP = number_format(@$limP, 2, ".", "");
                                        }
                                    } else {
                                        $limP = number_format(@$limP, 2, ".", "");
                                    }
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
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
        $phMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->where('Activo', 1)->get();
        $phMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->where('Activo', 1)->get();

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
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color1 = $item->Color;
                break;
            } else {
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color2 = $item->Color;
                break;
            } else {
                $color2 = $item->Color;
            }
        }

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
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color1 = $item->Color;
                break;
            } else {
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color2 = $item->Color;
                break;
            } else {
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

        $notaSiralab = array();
        if ($solModel1->Siralab == 1) {
            $notaSiralab = ImpresionInforme::where('Id_solicitud', $solModel1->Id_solicitud)->first();
        }


        $data = array(
            'limCPhColor' => $limCPhColor,
            'limC1PhColor' => $limC1PhColor,
            'limC2PhColor' => $limC2PhColor,
            'notaSiralab' => $notaSiralab,
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
            'margin_top' => 80,
            'margin_bottom' => 40,
            'defaultheaderfontstyle' => ['n
            ormal'],
            'defaultheaderline' => '0'
        ]);
        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );
        // $mpdf->showWatermarkImage = true;

        $solModel1 = Solicitud::where('Id_solicitud', $idSol1Temp)->first();
        $solModel2 = Solicitud::where('Id_solicitud', $idSol2Temp)->first();
        $valFol = explode('-', $solModel1->Folio_servicio);
        $valFol2 = explode('-', $solModel2->Folio_servicio);

        if ($valFol[0] < $valFol2[0]) {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();

            $idSol2 = $idSol2Temp;
            $idSol1 = $idSol1Temp;
        } else {
            // Hace los filtros para realizar la comparacion
            $solModel1 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol2Temp)->first();
            $solModel2 = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol1Temp)->first();
            $idSol2 = $idSol1Temp;
            $idSol1 = $idSol2Temp;
        }

        @$gasto1Aux = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->get();
        @$gasto2Aux = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->get();
        if ($gasto1Aux->count()) {
        } else {
            $solGen = SolicitudesGeneradas::where('Id_solicitud', $idSol1)->first();
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


            $modGas = CodigoParametros::where('Id_parametro', 26)->where('Id_solicitud', $idSol1)->first();
            $modGas->Resultado = ($contGas1 / $auxGasto1->count());
            $modGas->Resultado2 = ($contGas1 / $auxGasto1->count());
            $modGas->save();
        }
        if ($gasto2Aux->count()) {
        } else {
            $solGen = SolicitudesGeneradas::where('Id_solicitud', $idSol2)->first();
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

            $modGas2 = CodigoParametros::where('Id_parametro', 26)->where('Id_solicitud', $idSol2)->first();
            $modGas2->Resultado = ($contGas2 / $auxGasto2->count());
            $modGas2->Resultado2 = ($contGas2 / $auxGasto2->count());
            $modGas2->save();
        }



        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        if ($solModel1->Siralab == 1) {
        } else {
        }
        $rfc = RfcSucursal::where('Id_sucursal', $solModel1->Id_sucursal)->first();
        $direccion1 = DireccionReporte::where('Id_direccion', $solModel1->Id_direccion)->first();
        $punto = SolicitudPuntos::where('Id_solicitud', $idSol1)->first();
        
        $auxPunto = PuntoMuestreoSir::withTrashed()->where('Id_punto', $punto->Id_muestreo)->first();
        @$tituloConsecion = TituloConsecionSir::withTrashed()->where('Id_titulo', $auxPunto->Titulo_consecion)->first();


        $model1 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Mensual', 1)->orderBy('Parametro', 'ASC')->get();
        $model2 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Mensual', 1)->orderBy('Parametro', 'ASC')->get();

        @$gasto1 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol1)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        @$gasto2 = DB::table('ViewCodigoInformeMensual')->where('Id_solicitud', $idSol2)->where('Num_muestra', 1)->where('Id_parametro', 26)->first();
        $obs1 = CampoCompuesto::where('Id_solicitud', $idSol1)->first();
        $obs2 = CampoCompuesto::where('Id_solicitud', $idSol2)->first();
        $tempAmbiente1  = TemperaturaAmbiente::where('Id_solicitud', $idSol1)->get();
        $tempAmbiente2  = TemperaturaAmbiente::where('Id_solicitud', $idSol2)->get();
        $auxTemp = 0;
        $tempProm1 = 0;
        $tempProm2 = 0;
        foreach ($tempAmbiente1 as $item) {
            $tempProm1 += $item->Temperatura1;
            @$tempProm2 += $tempAmbiente2[$auxTemp]->Temperatura1;
            $auxTemp++;
        }
        $tempProm1 = $tempProm1 / $auxTemp;
        $tempProm2 = $tempProm2 / $auxTemp;

        // echo "Temp2: ".$tempProm2; 


        $phMuestra1 = PhMuestra::where('Id_solicitud', $idSol1)->where('Activo', 1)->get();
        $phMuestra2 = PhMuestra::where('Id_solicitud', $idSol2)->where('Activo', 1)->get();

        $auxPh = 0;
        $olor1 = false;
        $olor2 = false;
        $color1 = "";
        $color2 = "";

        foreach ($phMuestra1 as $item) {
            if ($item->Olor == "Si") {
                $olor1 = true;
            }
            if (@$phMuestra2[$auxPh]->Olor == "Si") {
                $olor2 = true;
            }
            $auxPh++;
        }
        foreach ($phMuestra1 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color1 = $item->Color;
                break;
            } else {
                $color1 = $item->Color;
            }
        }
        foreach ($phMuestra2 as $item) {
            $colorTemp = PhMuestra::where('Id_solicitud', $idSol1)->where('Color', $item->Color)->where('Activo', 1)->get();
            if ($colorTemp->count() >= (($phMuestra1->count()) / 2)) {
                $color2 = $item->Color;
                break;
            } else {
                $color2 = $item->Color;
            }
        }

        $proceso1 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol1)->first();
        $proceso2 = DB::table('proceso_analisis')->where('Id_solicitud', $idSol2)->first();
        $numOrden1 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel1->Hijo)->first();
        $numOrden2 =  DB::table('ViewSolicitud2')->where('Id_solicitud', $solModel2->Hijo)->first();
        // $firma1 = User::find(14);
        $reportesInformes = DB::table('ViewReportesInformesMensual')->where('deleted_at', NULL)->first(); //Condición de busqueda para las configuraciones(Historicos)  
        $firma1 = User::find($reportesInformes->Id_reviso);
        $firma2 = User::find($reportesInformes->Id_autorizo);

        $cotModel = Solicitud::where('Id_cotizacion', $solModel1->Id_cotizacion)->first();
        $tipoReporte = DB::table('categoria001_2021')->where('Id_categoria', $cotModel->Id_reporte2)->first();
        $cliente = Clientes::where('Id_cliente', $solModel1->Id_cliente)->first();
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
        $limC1PhColor = 0;
        $limC2PhColor = 0;
        $limCPhColor = 0;
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
                //case 64:
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
                    } else {
                        $limP = number_format(@$aux64, 2, ".", "");;
                    }
                    break;
                case 67:
                    $limP = ($item->Resultado2 + $model2[$cont]->Resultado2) / 2;
                    if ($item->Resultado2 >= "3500") {
                        $limC1 = "> 3500";
                    } else {
                        $limC1 = round($item->Resultado2);
                    }
                    if ($model2[$cont]->Resultado2 >= "3500") {
                        $limC2 = "> 3500";
                    } else {
                        $limC2 = round($model2[$cont]->Resultado2);
                    }
                    if ($limP >= "3500") {
                        $limP = "> 3500";
                    } else {
                        $limP = round($limP);
                    }
                    break;
                default:
                    if ($item->Resultado2 != NULL || $model2[$cont]->Resultado2 != NULL) {

                        $limAux1 = 0;
                        $limAux2 = 0;
                        if (@$item->Resultado2 < $item->Limite) {
                            @$limAux1 = @$item->Limite;
                        } else {
                            @$limAux1 = @$item->Resultado2;
                        }
                        if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                            @$limAux2 = $item->Limite;
                        } else {
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
                                } else {
                                    $limC1 = round($item->Resultado2);
                                }
                                if ($model2[$cont]->Resultado2 > "3500") {
                                    $limC2 = "> 3500";
                                } else {
                                    $limC2 = round($model2[$cont]->Resultado2);
                                }
                                break;
                            case 26: //gasto
                                $limP = number_format((($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2)), 2);
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
                            case 619:
                            case 19:
                            case 23:
                            case 113:
                            case 79: //Fenole
                            case 232: //fierro total
                            case 114:
                            case 388:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                } else {
                                    $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "" . number_format(@$item->Limite, 3, ".", "");
                                } else {
                                    $limP = number_format(@$limP, 3, ".", "");
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 3, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 3, ".", "");
                                }
                                break;
                            case 9:
                            case 10:
                            case 11:
                            case 83:
                            case 12:
                            case 15:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                } else {
                                    $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    $limP = number_format(@$limP, 2, ".", "");
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            case 35:
                            case 134:
                            case 50:
                            case 51:
                            case 78:
                            case 253:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                } else {
                                    $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                }
                                if ($limP < $item->Limite) {
                                    $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    $limP = number_format(@$limP, 2, ".", "");
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                    // $limC1 = round(@$item->Resultado2);
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    // $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                    // $limC2 = round(@$model2[$cont]->Resultado2);
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            case 365:
                            case 372:
                            case 370:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                } else {
                                    $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                }

                                $limCPhColor = number_format((($parti1 * @$item->Ph_muestra) + ($parti2 * @$model2[$cont]->Ph_muestra)), 2, ".", "");
                                $limC1PhColor = number_format(@$item->Ph_muestra, 2, ".", "");
                                $limC2PhColor =  number_format(@$model2[$cont]->Ph_muestra, 2, ".", "");
                                if ($limP < $item->Limite) {
                                    $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    $limP = number_format(@$limP, 2, ".", "");
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
                                    $limC2 = number_format(@$model2[$cont]->Resultado2, 2, ".", "");
                                }
                                break;
                            default:
                                if (@$item->Limite == "N.A" || @$item->Limite == "N.N" || @$item->Limite == "N/A" || @$item->Limite == "N.A.") {
                                    $limP = (($parti1 * $item->Resultado2) + ($parti2 * $model2[$cont]->Resultado2));
                                } else {
                                    $limP = (($parti1 * $limAux1) + ($parti2 * $limAux2));
                                }

                                if ($limP < $item->Limite) {
                                    $limP = "" . number_format(@$item->Limite, 2, ".", "");
                                } else {
                                    $limP = number_format(@$limP, 2, ".", "");
                                }
                                if (@$item->Resultado2 < @$item->Limite) {
                                    $limC1 = "< " . $item->Limite;
                                } else {
                                    $limC1 = number_format(@$item->Resultado2, 2, ".", "");
                                }
                                if (@$model2[$cont]->Resultado2 < @$item->Limite) {
                                    $limC2 = "< " . $item->Limite;
                                } else {
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
        $reportesInformes = DB::table('ViewReportesInformesMensual')->where('deleted_at', null)->orderBy('Num_rev', 'desc')->first(); //Condición de busqueda para las configuraciones(Historicos)  

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

        $notaSiralab = array();
        if ($solModel1->Siralab == 1) {
            $notaSiralab = ImpresionInforme::where('Id_solicitud', $idSol1)->first();
        }

        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        // $claveFirma = 'folmenencriptABC#Lorem';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = 'Rev: ' . $firma1->name . ' | Fol1: ' . $proceso1->Folio . ' , Fol2: ' . $proceso2->Folio;
        $dataFirma2 = 'Aut:  ' . $firma2->name . ' | Fol1: ' . $proceso1->Folio . ' , Fol2: ' . $proceso2->Folio;

        $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);



        $data = array(
            'limCPhColor' => $limCPhColor,
            'limC1PhColor' => $limC1PhColor,
            'limC2PhColor' => $limC2PhColor,
            'firmaEncript1' => $firmaEncript1,
            'firmaEncript2' => $firmaEncript2,
            'notaSiralab' => $notaSiralab,
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
            'tempAmbiente2' => $tempAmbiente2,
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
        //HEADER-FOOTER************************************************************************************** ****************************
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
        $firma2 = User::find(31);
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
        $mpdf->showWatermarkImage = true;

        $model = DB::table('Viewcustodia')->where('Id_solicitud', $idSol)->first();
        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_cadena = 1;
        $proceso->save();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $auxSolPadre = Solicitud::where('Id_solicitud', $model->Hijo)->first();

        $areaParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $idSol)
            ->where('Id_parametro', '!=', 64)->get();
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
        $contAux = 0;

        // $this->setSupervicion($model->Hijo);
        // $cadenaGenerales = CadenaGenerales::where('Id_solicitud', $idSol)->get();
        $cadenaGenerales = CadenaGenerales::where('Id_solicitud', $idSol)
            ->orderBy('created_at', 'asc')
            ->get()
            ->unique('Area');

        // $cadenaGenerales = CadenaGenerales::where('Id_solicitud', $idSol)->get();
        if ($cadenaGenerales->count()) {
        } else {
            $this->setSupervicion($model->Hijo);
            $cadenaGenerales = CadenaGenerales::where('Id_solicitud', $idSol)
                ->orderBy('created_at', 'asc')
                ->get()
                ->unique('Area');
        }


        $paramResultado = DB::table('ViewCodigoParametro')
            ->where('Id_solicitud', $idSol)
            ->where('Id_parametro', '!=', 26)
            ->where('Id_parametro', '!=', 103)
            ->where('Cadena', 1)
            ->where('Id_area', '!=', 9)
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
                        if ($item->Resultado == "NULL" || $item->Resultado == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {
                                $resTemp = number_format($item->Resultado, 2);
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
                                if ($item->Resultado2 > 8) {
                                    $resTemp = '>' . 8;
                                } else {
                                    $resTemp = $item->Resultado2;
                                }
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
                    case 9: //nitrogeno amoniacal
                    case 83: //kejendal
                    case 10: //organico
                    case 11: //nitrogeno total
                    case 15: //Fosforo
                    case 251:
                    case 77:
                    case 46:
                    case 71:
                    case 70:
                        // case 152:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
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
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                        }

                        break;
                    case 133:
                    case 58:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 1, ".", "");
                            }
                        }
                        break;
                    case 22:
                    case 20: //cobre total
                    case 7:
                    case 8:
                    case 23: //niquel
                    case 24: //plomo total
                    case 25: //zinc total
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
                        } else {
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
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . number_format(@$item->Limite, 3, ".", "");
                            } else {
                                $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                        }

                        break;
                    case 132:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "-----";
                        } else {
                            if ($item->Resultado2 > 0) {
                                if ($item->Resultado2 >= 8) {
                                    $resTemp = "> 8";
                                } else {
                                    $resTemp = $item->Resultado2;
                                }
                            } else {
                                $resTemp = "< 1.1";
                            }
                        }

                        break;
                    case 32:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "-----";
                        } else {
                            $restTemp = $item->Resultado2;
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
                        switch ($model->Id_norma) {
                            case 1:
                            case 27:
                            case 9:
                            case 2:
                            case 3:
                            case 4:
                            case 33:
                            case 21:
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
                    case 64:
                    case 69:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {
                                $resTemp = number_format($item->Resultado2, 2);
                            }
                        }
                        break;
                    case 271:
                        $resTemp = $item->Resultado2;
                        break;
                    case 97:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            $resTemp = round($item->Resultado2);
                        }
                        break;
                    case 365:
                    case 370:
                    case 372:
                        if ($item->Resultado2 === "NULL" || $item->Resultado2 === NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 > 70) {
                                $resTemp = ">70 | pH: " . $item->Ph_muestra;
                            } elseif ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite . " | pH: " . $item->Ph_muestra;
                            } else {
                                $resTemp = $item->Resultado2 . " | pH: " . $item->Ph_muestra;
                            }
                        }
                        break;


                    //CASE 365,370 Y 372 ORIGINAL NO BORRAR 
                    // if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                    //     $resTemp = "----";
                    // } else {
                    //     if ($item->Resultado2 < $item->Limite) {
                    //         $resTemp = "< " . $item->Limite . " | pH: " . $item->Ph_muestra;
                    //     } else {
                    //         $resTemp = $item->Resultado2 . " | pH: " . $item->Ph_muestra;
                    //     }
                    // }
                    // break; 

                    case 102:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            $resTemp = "436nm: " . number_format($item->Resultado, 1, '.', '') . "| 525nm: " . number_format($item->Resultado2, 1, '.', '') . "| 620nm: " . number_format($item->Resultado_aux, 1, '.', '') . "| pH: " . $item->Ph_muestra;;
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
                                case 33:
                                    if ($puntoMuestreo->Condiciones != 1) {
                                        if ($item->Resultado2 >= 3500) {
                                            $resTemp = "> 3500";
                                        } else {
                                            $resTemp = round($item->Resultado2);
                                        }
                                    } else {
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
                                case 33:
                                case 9:
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
                                    } else {
                                        $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    break;
                            }
                        }
                        break;
                    case 89:
                    case 98:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else if ($item->Resultado2 > 10) {
                                $resTemp = ">10";
                            } else {
                                $resTemp = $item->Resultado2;
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
        $firmaRes = User::where('id', 14)->first();
        // $firmaRes = User::where('id', 4)->first();
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first(); //Condición de busqueda para las configuraciones(Historicos)

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

        $folioSer = $model->Folio_servicio;
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);


        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $reportesCadena->Nombre_responsable . ' | ' . $model->Folio_servicio;

        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        if ($tempProceso->Supervicion != 0) {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript1 = "";
        }


        // $mpdf->showWatermarkImage = true;
        $data = array(
            'firmaEncript1' => $firmaEncript1,
            'cadenaGenerales' => $cadenaGenerales,
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
            'phMue6stra' => $phMuestra,
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

        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $model->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSolPadre->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $tempProceso->Emision_informe . '/' . $folioPadre);

        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-custodia.pdf';

        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);


        $mpdf->Output('Cadena de Custodia Interna.pdf', 'I');
    }
    public function custodiaInterna($idSol, $idPunto)
    {
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

        // Marca de agua del Cadena
        $mpdf->SetWatermarkImage(
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            [215, 280],
            [0, 0]
        );
        $mpdf->showWatermarkImage = true;

        // Datos generales
        $datos = Solicitud::with(['norma', 'descarga'])
            ->where('Id_solicitud', $idPunto)
            ->select('Folio_servicio', 'Id_descarga', 'Id_norma', 'Num_tomas', 'Hijo')
            ->first();

        $data1 = [
            'Folio_servicio' => $datos->Folio_servicio,
            'Id_descarga' => $datos->Id_descarga,
            'Id_norma' => $datos->Id_norma,
            'Clave_norma' => $datos->norma->Clave_norma ?? null,
            'Descarga' => $datos->descarga->Descarga ?? null
        ];
        // dd($data1)
        $puntoMuestreo = SolicitudPuntos::where('Id_solicitud', $idPunto)->first();
        // Parámetros
        $solpa = SolicitudParametro::where('Id_solicitud', $idPunto)->select('Id_subnorma')->get();
        $idsSubnorma = $solpa->pluck('Id_subnorma')->toArray();
        $data2 = CadenaGenerales::where('Id_solicitud', $idPunto)
            ->where('Area', '!=', 'Toxicidad Vibrio fischeri')
            ->orderBy('created_at', 'asc')
            ->select('Area', 'Responsable', 'Recipientes', 'Fecha_salida', 'Fecha_entrada', 'Fecha_salidaEli', 'Fecha_emision', 'Firma')
            ->get()
            ->unique('Area')
            ->values();

        if ($data2->count()) {
        } else {
            $this->setSupervicion($datos->Hijo);
            $data2 = CadenaGenerales::where('Id_solicitud', $idPunto)
                ->where('Area', '!=', 'Toxicidad Vibrio fischeri')
                ->orderBy('created_at', 'asc')
                ->select('Area', 'Responsable', 'Recipientes', 'Fecha_salida', 'Fecha_entrada', 'Fecha_salidaEli', 'Fecha_emision', 'Firma')
                ->get()
                ->unique('Area')
                ->values();
        }
        // dd($data2);
        $codigo = CodigoParametros::where('Id_solicitud', $idPunto)
            ->whereHas('parametro', function ($q) {
                $q->where('Id_area', '!=', 9);
            })->with('parametro.unidad')->select(
                'Id_codigo',
                'Id_parametro',
                'Resultado2',
                'Resultado',
                'Resultado_aux',
                'Resultado2 as resTemp',
                'Ph_muestra',
                'Num_muestra',
                'Cancelado'
            )
            ->whereNotIn('Id_parametro', [26, 103, 173])
            ->where('Cadena', 1)
            ->get()
            ->sortBy(function ($item) {
                return [$item->parametro->Parametro, $item->Num_muestra];
            })
            ->values();




        foreach ($codigo as $item) {
            $parametro = $item->parametro;
            $item->resTemp = '--------';
            if ($item->Resultado2 == NULL || $item->Resultado2 == 'NULL') {
                $resTemp = "----";
            } else {
                if ($parametro && $item->Cancelado != 1) {
                    $limite = $parametro->Limite;


                    switch ($item->Id_parametro) {
                        case 361:

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {
                                $item->resTemp = number_format(@$item->Resultado2, 1, ".", "");
                            }
                            break;
                        case 12:
                        case 13:
                        case 35:
                        case 253:
                        case 137:
                        case 51:
                            if ($item->Resultado == NULL  || $item->Resultado == 'NULL') {
                                $item->resTemp = "----";
                            }
                            if ($item->Resultado < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {
                                $item->resTemp = number_format($item->Resultado, 2);
                            }
                            break;
                        case 135:
                        case 78:
                        case 134:

                            if ($item->Resultado2 > 0) {
                                if ($item->Resultado2 > 8) {
                                    $item->resTemp = '>' . 8;
                                } else {
                                    $resTemp = $item->Resultado2;
                                }
                            } else {
                                $item->resTemp = "" . $item->Limite;
                            }

                            break;
                        case 3:
                        case 4:
                            // case 13: // g y a
                        case 6: //DQO
                        case 5: //DBO
                        case 9: //nitrogeno amoniacal
                        case 83: //kejendal
                        case 10: //organico
                        case 11: //nitrogeno total
                        case 15: //Fosforo
                        case 251:
                        case 77:
                        case 46:
                        case 71:
                        case 70:

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $item->resTemp = number_format(@$item->Resultado2, 2, ".", "");
                                // $resTemp = round($item->Resultado2, 2);

                            }
                            break;
                        // DD($item->resTemp);
                        case 152:
                        case 619:
                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $item->resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                            break;
                        case 133:
                        case 58:

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $item->resTemp = number_format(@$item->Resultado2, 1, ".", "");
                            }
                            break;
                        case 22:
                        case 20: //cobre total
                        case 7:
                        case 8:
                        case 23: //niquel
                        case 24: //plomo total
                        case 25: //zinc total
                        case 18:
                        case 122:
                        case 106: //nitratos
                        case 124:
                        case 114:
                        case 96:
                        case 95:
                        case 243: //sulfatos
                        case 17: //arsenico total
                        case 113:
                        case 232:
                            if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                                $item->resTemp = "----";
                            } else {
                                if ($item->Resultado2 < $limite) {
                                    $item->resTemp = "< " . number_format(@$limite, 3, ".", "");
                                } else {
                                    $item->resTemp = number_format(@$item->Resultado2, 3, ".", "");
                                }
                            }
                            break;
                        case 80:
                        case 105:
                        case 121: // Fluoruros 

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . number_format(@$limite, 3, ".", "");
                            } else {
                                $item->resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                            break;
                        case 132:

                            if ($item->Resultado2 > 0) {
                                if ($item->Resultado2 >= 8) {
                                    $resTemp = "> 8";
                                } else {
                                    $item->resTemp = $item->Resultado2;
                                }
                            } else {
                                $item->resTemp = "< 1.1";
                            }
                            break;
                        case 32:
                            $item->restTemp = $item->Resultado2;
                            break;
                        case 2:
                            if ($item->Resultado2 == 1 || $item->Resultado2 == "PRESENTE") {
                                $item->resTemp = "PRESENTE";
                            } else {
                                $item->resTemp = "AUSENTE";
                            }
                            break;
                        case 14: // ph
                        case 110:
                            switch ($datos->Id_norma) {
                                case 1:
                                case 27:
                                case 9:
                                case 2:
                                case 3:
                                case 4:
                                case 33:
                                case 21:
                                    $item->resTemp = number_format(@$item->Resultado2, 2, ".", "");
                                    break;
                                default:
                                    $item->resTemp = number_format(@$item->Resultado2, 1, ".", "");
                                    break;
                            }
                            break;
                        case 64:
                        case 69:
                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {
                                $item->resTemp = number_format($item->Resultado2, 2);
                            }
                            break;
                        case 271:
                            $item->resTemp = $item->Resultado2;
                            break;
                        case 97:
                            $item->resTemp = round($item->Resultado2);
                            break;
                        case 365:
                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite . " | ph ".$item->Ph_muestra;
                            } else {
                                  $item->resTemp = round($item->Resultado2). " | ph ".$item->Ph_muestra;
                            }
                            break;
                        case 370:
                        case 372:

                            if ($item->Resultado2 > 70) {
                                $item->resTemp = ">70 | pH: " . $item->Ph_muestra;
                            } elseif ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite . " | pH: " . $item->Ph_muestra;
                            } else {
                                $item->resTemp = $item->Resultado2 . " | pH: " . $item->Ph_muestra;
                            }

                            break;
                        case 102:
                            $item->resTemp = "436nm: " . number_format($item->Resultado2, 1, '.', '') . "| 525nm: " . number_format($item->Resultado2, 1, '.', '') . "| 620nm: " . number_format($item->Resultado_aux, 1, '.', '') . "| pH:" . $item->Ph_muestra;
                            break;
                        case 67:

                            switch ($datos->Id_norma) {
                                case 1:
                                case 27:
                                case 33:

                                    if ($puntoMuestreo->Condiciones != 1) {
                                        if ($item->Resultado2 >= 3500) {
                                            $item->resTemp = "> 3500";
                                        } else {
                                            $item->resTemp = round($item->Resultado2);
                                        }
                                    } else {
                                        $item->resTemp = round($item->Resultado2);
                                    }
                                    break;

                                default:
                                    $item->resTemp = round($item->Resultado2);
                                    break;
                            }
                            break;
                        case 358:

                            switch ($datos->Id_norma) {
                                case 1:
                                case 27:
                                case 33:
                                case 9:
                                    switch ($item->Resultado2) {
                                        case 499:
                                            $item->resTemp = "< 500";
                                            break;
                                        case 500:
                                            $item->resTemp = "500";
                                            break;
                                        case 1000:
                                            $item->resTemp = "1000";
                                            break;
                                        case 1500:
                                            $item->resTemp = "> 1000";
                                            break;
                                        default:
                                            $item->resTemp =  number_format(@$item->Resultado2, 2, ".", "");
                                            break;
                                    }
                                    break;
                                default:
                                    if ($item->Resultado2 < $limite) {
                                        $item->resTemp = "< " . $limite;
                                    } else {
                                        $item->resTemp =  number_format(@$item->Resultado2, 2, ".", "");
                                    }
                                    break;
                            }

                            break;
                        case 89:
                        case 98:

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else if ($item->Resultado2 > 10) {
                                $item->resTemp = ">10";
                            } else {
                                $item->resTemp = $item->Resultado2;
                            }
                            break;

                        default: //Default por PARAMETROS FINAL

                            if ($item->Resultado2 < $limite) {
                                $item->resTemp = "< " . $limite;
                            } else {
                                $item->resTemp = $item->Resultado2;
                            }
                            break;
                    }
                } else {
                    $resTemp = "----";
                }
            }
        } //aqui acaba el
        $promGra = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 13)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        $promGas = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 26)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        $promCol = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 12)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        $promCol2 = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 137)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        $promEco = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 35)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        $promEnt = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idPunto)->where('Id_parametro', 253)->where('Cadena',1)->where('Num_muestra', 1)->where('Resultado2', '!=', null)->select('Resultado2', 'Limite')->first();
        //dd( $promGra, $promGas,$promEnt);
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first();
        $firmaRes = User::where('id', 14)->first();

        //dd($codigo);
        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $folioSer = $datos->Folio_servicio;
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);
        // Pasar data1 y data2 a las vistas
        $data = [
            'data1' => $data1,
            'data2' => $data2,
            'codigo' => $codigo,
            'promCol2' => $promCol2,
            'promGra' => $promGra,
            'promGas' => $promGas,
            'promCol' => $promCol,
            'promEco' => $promEco,
            'promEnt' => $promEnt,
            'model' => $datos,
            'reportesCadena' => $reportesCadena,
            'firmaRes' => $firmaRes,
            'folioEncript' => $folioEncript,
        ];
        // Footer
        $htmlFooter = view('exports.campo.cadenaCustodiaInterna2.footerCadena', $data)->render();
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');

        // Cuerpo del PDF
        $htmlInforme = view('exports.campo.cadenaCustodiaInterna2.bodyCadena', $data)->render();
        $mpdf->WriteHTML($htmlInforme);

        // Mostrar o descargar PDF
        $mpdf->Output('CadenaCustodiaInterna.pdf', 'I');
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
        $sol = DB::table('ViewSolicitudGenerada')->where('Id_muestreador', '!=', null)->whereDate('Fecha_muestreo', '>=', "2023-08-1")->whereDate('Fecha_muestreo', '<=', "2023-08-15")->get();

        foreach ($sol as $item) {
            $ph = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->where('Promedio', null)->get();

            foreach ($ph as $item2) {
                echo '-------------------------';
                echo '<br>';
                echo 'ID Sol: ' . $item2->Id_solicitud;
                echo '<br>';
            }
        }
    }
    public function setNota4(Request $res)
    {
        $model = Solicitud::where('Hijo', $res->id)->get();
        $std = 0;
        if ($res->nota != "false") {
            $std = 1;
        }
        foreach ($model as $item) {
            $temp = Solicitud::where('Id_solicitud', $item->Id_solicitud)->first();
            $temp->Nota_4 = $std;
            $temp->save();
        }
        $data = array(
            'std' => $std,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setFirmaAut(Request $res)
    {
        $msg = "Firma Autorizada";
        $sol = Solicitud::where('Id_solicitud', $res->id)->first();
        $model = ProcesoAnalisis::where('Folio', 'LIKE', '%' . $sol->Folio_servicio . '%')->get();
        foreach ($model as $item) {
            $temp = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();
            $temp->Firma_aut = 1;
            $temp->save();
        }
        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function setSupervicion($idPadre)
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
        $idSol = $idPadre;
        $tempArea = array();
        $sw = true;
        $user = 1;
        $fechaTemp = '';
        $solModel = Solicitud::where('Hijo', $idPadre)->get();


        foreach ($solModel as $item) {
            $tempArea = array();

            $areaParam = DB::table('viewcodigoinforme')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', '!=', 64)->where('Cancelado', 0)->where('Cadena',1)->get();
            $phMuestra = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();
            $detalleTemp = DB::table('cadena_generales')->where('Id_solicitud', $item->Id_solicitud)->delete();

            $temp = ProcesoAnalisis::where('Id_solicitud', $item->Id_solicitud)->first();


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
                        switch ($auxEnv[0]->Id_responsable) {
                            case 21:
                                $user = DB::table('users')->where('id', 46)->first();
                                break;
                            case 23:
                                $user = DB::table('users')->where('id', 44)->first();
                                break;
                            case 19;
                                $user = DB::table('users')->where('id', 52)->first();
                                break;
                            default:
                                $user = DB::table('users')->where('id', $auxEnv[0]->Id_responsable)->first();
                                break;
                        }
                        if (@$item2->Id_area == 12 || @$item2->Id_area == 6 || @$item2->Id_area == 13 || @$item2->Id_area == 3) {
                            if (@$item2->Id_parametro != 16) {
                                if ($solModel[0]->Id_servicio != 3) {
                                    switch ($auxEnv[0]->Id_area) {

                                        default:
                                            $numRecipientes = $phMuestra->count();
                                            break;
                                    }
                                } else {
                                    switch ($auxEnv[0]->Id_area) {
                                        default:
                                            $numRecipientes = $solModel[0]->Num_tomas;
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
                                $modelDet = DB::table('lote_detalle_icp')->where('Id_control', 1)->where('Id_codigo', $item->Folio_servicio)->where('Id_parametro', $item2->Id_parametro)->get();
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
                                switch ($item2->Id_parametro) {
                                    case 102:
                                        $modelDet = DB::table('lote_detalle_color')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        if ($modelDet->count()) {
                                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                            $fechaTemp = $loteTemp->Fecha;
                                        } else {
                                            $fechaTemp = "";
                                        }
                                        break;
                                    case 173:
                                         $modelDet = DB::table('lote_detalle_vidrio')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        if ($modelDet->count()) {
                                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                            $fechaTemp = $loteTemp->Fecha;
                                        } else {
                                            $fechaTemp = "";
                                        }
                                        break;
                                    default:
                                        $modelDet = DB::table('lote_detalle_directos')->where('Id_analisis', $idSol)->where('Id_parametro', $item2->Id_parametro)->get();
                                        if ($modelDet->count()) {
                                            $loteTemp = LoteAnalisis::where('Id_lote', $modelDet[0]->Id_lote)->first();
                                            $fechaTemp = $loteTemp->Fecha;
                                        } else {
                                            $fechaTemp = "";
                                        }
                                        break;
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
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(18)->format('d/m/Y');
                                            // $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(11)->format('d/m/Y');
                                            break;
                                        case 5:
                                        case 30:
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(21)->format('d/m/Y');
                                            // $fechaEmision = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(14)->format('d/m/Y');
                                            break;
                                        default:
                                            $fechaSalidaEli = \Carbon\Carbon::parse(@$temp->Hora_recepcion)->addDays(18)->format('d/m/Y');
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
                            switch ($item->Id_norma) {
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


                        $detalle = CadenaGenerales::create([
                            'Id_solicitud' => $item->Id_solicitud,
                            'Area' => $auxEnv[0]->Area,
                            'Responsable' => $user->name,
                            'Recipientes' => $numRecipientes,
                            'Fecha_salida' => \Carbon\Carbon::parse(@$fechaTemp)->format('d/m/Y'),
                            'Fecha_entrada' => $fechaEntrada,
                            'Fecha_salidaEli' => $fechaSalidaEli,
                            'Fecha_emision' => $fechaEmision,
                            'Firma' => $firma,
                            'User' => Auth::user()->id,
                        ]);
                    }
                }
            }
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function exportPdfInformeEbenhochSin($idSol, $idPunto)
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
        // Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        // $mpdf->showWatermarkImage = true;
        $auxSol = Solicitud::where('Id_solicitud', $idSol)->first();
        $model = Solicitud::where('Id_solicitud', $idPunto)->get();

        $cotModel = Cotizacion::where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        @$tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);

        $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        $auxNota = "";
        if ($impresion->count()) {
        } else {
            $simBac = CodigoParametros::where('Id_solicitud', $idPunto)->where('Resultado2', 'LIKE', "%*%")->where('Id_parametro', 32)->get();
            if ($simBac->count()) {
                $auxNota = "<br> * VALOR ESTIMADO";
            }
            $reporteInforme = ReportesInformes::where('Fecha_inicio', '<=', @$model[0]->Fecha_muestreo)->where('Fecha_fin', '>=', @$model[0]->Fecha_muestreo)->get();
            if ($reporteInforme->count()) {
                if ($model[0]->Siralab == 1) {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Nota_siralab' => $reporteInforme[0]->Nota_siralab,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                } else {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                }
            }
            $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        }


        // $reportesInformes = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first(); //Historicos (Informe)
        $aux = true;

        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $idSol = $idPunto;

        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_informe = 1;
        $proceso->save();

        $aux = DB::table('viewprocesoanalisis')->where('Hijo', $solModel->Hijo)->where('Impresion_informe', 0)->get();
        if ($aux->count() == 0) {
            $proceso = ProcesoAnalisis::where('Id_solicitud', $solModel->Hijo)->first();
            $proceso->Impresion_informe = 1;
            $proceso->save();
        }

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
            $auxPunto = PuntoMuestreoSir::where('Id_punto', $puntoMuestreo->Id_muestreo)->withTrashed()->first();
            $titTemp = TituloConsecionSir::where('Id_titulo', $auxPunto->Titulo_consecion)->withTrashed()->first();
            $tituloConsecion = $titTemp->Titulo;
        }
        // $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area','!=',9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model = DB::table('ViewCodigoInforme')
            ->where('Id_solicitud', $idSol)
            ->where('Num_muestra', 1)
            ->where('Id_area', '!=', 9)
            ->whereNotIn('Id_parametro', ['222', '118', '122', '124']) // ✅ Uso correcto
            ->where('Reporte', 1)
            ->orderBy('Parametro', 'ASC')
            ->get();
            $incerAux = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->where('Id_parametro', 173)->where('Incertidumbre','!=','')->get();
        // $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;

        if ($auxAmbienteProm->count()) {
            foreach ($auxAmbienteProm as $item) {
                $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
                $auxTem++;
            }
            @$tempAmbienteProm = round($tempAmbienteProm / $auxTem);
        }
        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
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
        $limitesCon = array();
        $aux = 0;
        $limC = 0;
        $auxCon = "";
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
                switch ($item->Id_parametro) {
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                    case 57:
                    case 59:
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
                            case 9:
                            case 21:
                            case 20:
                                $limC = number_format(@$item->Resultado2, 2, ".", "");
                                break;
                            default:



                                break;
                        }
                        break;
                    case 110:
                    case 125:
                        $limC = number_format(@$item->Resultado2, 1, ".", "");
                        break;
                    case 26:
                    case 39:
                        @$limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    case 16:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 0, ".", "");
                        }
                        break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109:
                        // case 67:
                    case 68:
                    case 57:
                        $limC = $item->Resultado2;
                        break;

                    case 78:
                        // case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado2 > 8) {
                                $limC = '>' . 8;
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                        // case 132:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 132:
                    case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "< 1.1";
                        }
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 137:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 65:
                    case 66:
                    case 102:
                    case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 58:
                    case 271:
                        $limC = $item->Resultado2;
                        break;
                    // case 271:
                    //     $limC = number_format(@$item->Resultado2, 1, ".", "");
                    //     break;
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
                    case 252:
                    case 29:
                    case 51:
                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 71:
                    case 38: //ortofosfato
                    case 36: //fosfatros
                    case 46: //ssv
                    case 137: //Coliformes totales
                    case 251:
                    case 77:
                    case 30:
                    case 90:
                    case 33:
                        // case 271:
                        // audi
                    case 52:
                    case 250:
                    case 54:
                    case 261:
                    case 130:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 227:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 3, ".", "");
                        }
                        break;
                    // case 64:
                    case 358:
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                            case 33:
                            case 9:
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
                                } else {
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 64:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC =  number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 67: //conductividad
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                                if ($puntoMuestreo->Condiciones != 1) {
                                    if ($item->Resultado2 >= 3500) {
                                        $limC = "> 3500";
                                    } else {
                                        $limC = round($item->Resultado2);
                                    }
                                } else {
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
                switch ($solModel->Id_norma) {
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        //comentarios
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            switch (@$solModel->Id_promedio) {
                                case 1:
                                    $aux = $limNo[0]->Instantaneo;
                                    break;
                                case 2:
                                    $aux = $limNo[0]->PromM;
                                    break;
                                case 3:
                                    $aux = $limNo[0]->PromD;
                                    break;
                                default:
                                    $aux = $limNo[0]->PromD;
                                    break;
                            }
                            switch ($item->Id_parametro) {
                                case 14:
                                    $rango = explode("-", $aux);
                                    if (@$rango[0] <= @$item->Resultado2 &&  @$rango >= @$item->Resultado2) {
                                        $auxCon = "CUMPLE";
                                    } else {
                                        $auxCon = "NO CUMPLE";
                                    }
                                    break;
                                case 2:
                                    if (@$item->Resultado2 == 1) {
                                        $auxCon = "NO CUMPLE";
                                    } else {
                                        $auxCon = "CUMPLE";
                                    }
                                    break;
                                default:
                                    if ($aux != "N.N.") {
                                        if ($aux != "N/A") {
                                            if (@$item->Resultado2 <= $aux) {
                                                $auxCon = "CUMPLE";
                                            } else {
                                                $auxCon = "NO CUMPLE";
                                            }
                                        } else {
                                            $auxCon = "N/A";
                                        }
                                    } else {
                                        $auxCon = "N.N.";
                                    }
                                    break;
                            }
                        } else {
                            $aux = "N/A";
                            $auxCon = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_min != "") {
                                $aux = $limNo[0]->Per_min . " - " . $limNo[0]->Per_max;
                            } else {
                                $aux = $limNo[0]->Per_max;
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 7:
                        $limNo = DB::table('limitepnorma_201')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_max != "") {
                                $aux = $limNo[0]->Per_max;
                            } else {
                                $aux = "N/A";
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud->Id_reporte2)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 365:
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
            array_push($limitesCon, $auxCon);
        }
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        //Id Firmas
        //ID 4 Luisita
        //ID 12 Sandy
        //ID 14 Lupita
        //ID 35 Agueda
        //ID 31 elsa

        switch ($solModel->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                //$firma1 = User::find(14) ;
                $firma1 = User::find(31);
                //  $firma1 = User::find(12); // Reviso
                $firma2 = User::find(14); // Autorizo
                //$firma2 = User::find(12); // Autorizo
                //$firma2 = User::find(14);
                break;
            default:
                //$firma1 = User::find(12); // Reviso
                //$firma1 = User::find(14); //reviso
                $firma1 = User::find(14); //reviso
                // $firma2 = User::find(35); //Autorizo
                $firma2 = User::find(31); //Autorizo
                //$firma2 = User::find(12); // Autorizo

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
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        // cambio 
        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $firma1->name . ' | ' . $solicitud->Folio_servicio;
        $dataFirma2 = $firma2->name . ' | ' . $solicitud->Folio_servicio;
        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        if ($tempProceso->Supervicion != 0) {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript1 = "";
        }
        if ($tempProceso->Firma_aut != 0) {
            $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript2 = "";
        }
        $tipo = 2;
        $newTitulo= 1;
        $data = array(
             'incerAux' => $incerAux,
            'newTitulo' => $newTitulo,
            'tipo' => $tipo,
            'url' => url(''),
            'firmaEncript1' => $firmaEncript1,
            'firmaEncript2' => $firmaEncript2,
            'norma' => $norma,
            'limitesCon' => $limitesCon,
            'impresion' => $impresion,
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
            // 'tipo' => $tipo,
            'rfc' => $rfc,
            'reportesInformes' => $reportesInformes,
        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.diario.bodyInforme', $data);
        $htmlHeader = view('exports.informes.diario.headerInforme', $data);
        $htmlFooter = view('exports.informes.diario.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        //Para la proteccion con contraseña, es el segundo parametro de la función SetProtection, el tercer parametro es una contraseña de propietario para permitir más acciones
        //En el caso del ultimo parámetro es la longitud del cifrado
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';

        // $mpdf->SetProtection(array(), 654, null, 128);
        // Establecer protección con contraseña de usuario y propietario

        $folPadre = Solicitud::where('Id_solicitud', $solicitud->Hijo)->first();
        $primeraLetra = substr($cliente->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();

        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $solicitud->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSol->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $solicitud->Fecha_muestreo . '/' . $folioPadre);

        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-informe.pdf';
        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }
    public function exportPdfInformeEbenhochSolo($idSol, $idPunto)
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
        // Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        // $mpdf->showWatermarkImage = true;
        $auxSol = Solicitud::where('Id_solicitud', $idSol)->first();
        $model = Solicitud::where('Id_solicitud', $idPunto)->get();

        $cotModel = Cotizacion::where('Id_cotizacion', $model[0]->Id_cotizacion)->first();
        @$tipoReporte = DB::table('ViewDetalleCuerpos')->where('Id_detalle', $cotModel->Tipo_reporte)->first();
        @$tipoReporte2 = TipoCuerpo::find($cotModel->Tipo_reporte);

        $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        $auxNota = "";
        if ($impresion->count()) {
        } else {
            $simBac = CodigoParametros::where('Id_solicitud', $idPunto)->where('Resultado2', 'LIKE', "%*%")->where('Id_parametro', 32)->get();
            if ($simBac->count()) {
                $auxNota = "<br> * VALOR ESTIMADO";
            }
            $reporteInforme = ReportesInformes::where('Fecha_inicio', '<=', @$model[0]->Fecha_muestreo)->where('Fecha_fin', '>=', @$model[0]->Fecha_muestreo)->get();
            if ($reporteInforme->count()) {
                if ($model[0]->Siralab == 1) {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Nota_siralab' => $reporteInforme[0]->Nota_siralab,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                } else {
                    ImpresionInforme::create([
                        'Id_solicitud' => $idPunto,
                        'Encabezado' => $reporteInforme[0]->Encabezado,
                        'Nota' => $reporteInforme[0]->Nota . "" . $auxNota,
                        'Id_analizo' => $reporteInforme[0]->Id_analizo,
                        'Id_reviso' => $reporteInforme[0]->Id_reviso,
                        'Fecha_inicio' => $reporteInforme[0]->Fecha_inicio,
                        'Fecha_fin' => $reporteInforme[0]->Fecha_fin,
                        'Num_rev' => $reporteInforme[0]->Num_rev,
                        'Obs_impresion' => $reporteInforme[0]->Obs_reimpresion,
                        'Clave' => $reporteInforme[0]->Clave,
                    ]);
                }
            }
            $impresion = ImpresionInforme::where('Id_solicitud', $idPunto)->get();
        }


        // $reportesInformes = DB::table('ViewReportesInformes')->orderBy('Num_rev', 'desc')->first(); //Historicos (Informe)
        $aux = true;

        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud', $idPunto)->first();
        $idSol = $idPunto;

        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_informe = 1;
        $proceso->save();

        $aux = DB::table('viewprocesoanalisis')->where('Hijo', $solModel->Hijo)->where('Impresion_informe', 0)->get();
        if ($aux->count() == 0) {
            $proceso = ProcesoAnalisis::where('Id_solicitud', $solModel->Hijo)->first();
            $proceso->Impresion_informe = 1;
            $proceso->save();
        }

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
            $auxPunto = PuntoMuestreoSir::where('Id_punto', $puntoMuestreo->Id_muestreo)->withTrashed()->first();
            $titTemp = TituloConsecionSir::where('Id_titulo', $auxPunto->Titulo_consecion)->withTrashed()->first();
            $tituloConsecion = $titTemp->Titulo;
        }
        // $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area','!=',9)->where('Reporte', 1)->orderBy('Parametro', 'ASC')->get();
        $model = DB::table('ViewCodigoInforme')
            ->where('Id_solicitud', $idSol)
            ->where('Num_muestra', 1)
            ->where('Id_area', '!=', 9)
            ->whereIn('Id_parametro', ['222', '118', '122', '124']) // ✅ Uso correcto
            ->where('Reporte', 1)
            ->orderBy('Parametro', 'ASC')
            ->get();
            $incerAux = DB::table('ViewCodigoInforme')->where('Id_solicitud', $idSol)->where('Num_muestra', 1)->where('Id_area', '!=', 9)->where('Reporte', 1)->where('Id_parametro','!=', 173)->where('Incertidumbre','!=','')->get();
        // $tempAmbienteProm = DB::table('ViewCodigoParametro')->where('Id_solicitud', $idSol)->where('Id_parametro', 97)->first();
        $auxAmbienteProm = TemperaturaAmbiente::where('Id_solicitud', $idSol)->get();
        $tempAmbienteProm = 0;
        $auxTem = 0;

        if ($auxAmbienteProm->count()) {
            foreach ($auxAmbienteProm as $item) {
                $tempAmbienteProm = $tempAmbienteProm + $item->Temperatura1;
                $auxTem++;
            }
            @$tempAmbienteProm = round($tempAmbienteProm / $auxTem);
        }
        //Recupera la temperatura compuesta
        $temperaturaC = CampoCompuesto::where('Id_solicitud', $idSol)->first();
        //Recupera la obs de campo
        $obsCampo = @$temperaturaC->Observaciones;
        $modelProcesoAnalisis = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $campoGeneral = CampoGenerales::where('Id_solicitud', $idSol)->first();
        $phCampo = PhMuestra::where('Id_solicitud', $idSol)->get();
        $numTomas = PhMuestra::where('Id_solicitud', $idSol)->where('Activo', 1)->get();
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
        $limitesCon = array();
        $aux = 0;
        $limC = 0;
        $auxCon = "";
        foreach ($model as $item) {
            if ($item->Resultado2 != NULL || $item->Resultado2 != "NULL") {
                switch ($item->Id_parametro) {
                    case 97:
                        $limC = round($item->Resultado2);
                        break;
                    case 2:
                    case 42: // salmonela
                    case 57:
                    case 59:
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
                            case 9:
                            case 21:
                            case 20:
                                $limC = number_format(@$item->Resultado2, 2, ".", "");
                                break;
                            default:



                                break;
                        }
                        break;
                    case 110:
                    case 125:
                        $limC = number_format(@$item->Resultado2, 1, ".", "");
                        break;
                    case 26:
                    case 39:
                        @$limC = number_format(@$item->Resultado2, 2, ".", "");
                        break;
                    case 16:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 0, ".", "");
                        }
                        break;
                    case 34:
                    case 84:
                    case 86:
                    case 32:
                    case 111:
                    case 109:
                        // case 67:
                    case 68:
                    case 57:
                        $limC = $item->Resultado2;
                        break;

                    case 78:
                        // case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado2 > 8) {
                                $limC = '>' . 8;
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 135:
                    case 134:
                        // case 132:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "NO DETECTABLE";
                        }
                        break;
                    case 132:
                    case 350:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            // $limC = "<" . $item->Limite;
                            $limC = "< 1.1";
                        }
                        break;
                    case 133:
                        if ($item->Resultado2 > 0) {
                            if ($item->Resultado >= 8) {
                                $limC = "> 8";
                            } else {
                                $limC = $item->Resultado;
                            }
                        } else {
                            $limC = "< " . $item->Limite;
                        }
                        break;
                    case 137:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 65:
                    case 66:
                    case 102:
                    case 361:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 1, ".", "");
                        }
                        break;
                    case 58:
                    case 271:
                        $limC = $item->Resultado2;
                        break;
                    // case 271:
                    //     $limC = number_format(@$item->Resultado2, 1, ".", "");
                    //     break;
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
                    case 252:
                    case 29:
                    case 51:

                    case 58:
                    case 115:
                    case 88:
                    case 161: //DQO soluble
                    case 71:
                    case 38: //ortofosfato
                    case 36: //fosfatros
                    case 46: //ssv
                    case 137: //Coliformes totales
                    case 251:
                    case 77:
                    case 30:
                    case 90:
                    case 33:
                        // case 271:
                        // audi
                    case 52:
                    case 250:
                    case 54:
                    case 261:
                    case 130:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 227:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = $item->Resultado2;
                        }
                        break;
                    case 25:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC = number_format(@$item->Resultado2, 3, ".", "");
                        }
                        break;
                    // case 64:
                    case 358:
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                            case 33:
                            case 9:
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
                                } else {
                                    $limC =  number_format(@$item->Resultado2, 2, ".", "");
                                }
                                break;
                        }
                        break;
                    case 64:
                        if ($item->Resultado2 < $item->Limite) {
                            $limC = "< " . $item->Limite;
                        } else {
                            $limC =  number_format(@$item->Resultado2, 2, ".", "");
                        }
                        break;
                    case 67: //conductividad
                        switch ($solModel->Id_norma) {
                            case 1:
                            case 27:
                                if ($puntoMuestreo->Condiciones != 1) {
                                    if ($item->Resultado2 >= 3500) {
                                        $limC = "> 3500";
                                    } else {
                                        $limC = round($item->Resultado2);
                                    }
                                } else {
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
                switch ($solModel->Id_norma) {
                    case 1:
                        @$limNo = DB::table('limitepnorma_001')->where('Id_categoria', $tipoReporte->Id_detalle)->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Prom_Dmax;
                        } else {
                            $aux = "N/A";
                        }
                        //comentarios
                        break;
                    case 2:
                        $limNo = DB::table('limitepnorma_002')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            switch (@$solModel->Id_promedio) {
                                case 1:
                                    $aux = $limNo[0]->Instantaneo;
                                    break;
                                case 2:
                                    $aux = $limNo[0]->PromM;
                                    break;
                                case 3:
                                    $aux = $limNo[0]->PromD;
                                    break;
                                default:
                                    $aux = $limNo[0]->PromD;
                                    break;
                            }
                            switch ($item->Id_parametro) {
                                case 14:
                                    $rango = explode("-", $aux);
                                    if (@$rango[0] <= @$item->Resultado2 &&  @$rango >= @$item->Resultado2) {
                                        $auxCon = "CUMPLE";
                                    } else {
                                        $auxCon = "NO CUMPLE";
                                    }
                                    break;
                                case 2:
                                    if (@$item->Resultado2 == 1) {
                                        $auxCon = "NO CUMPLE";
                                    } else {
                                        $auxCon = "CUMPLE";
                                    }
                                    break;
                                default:
                                    if ($aux != "N.N.") {
                                        if ($aux != "N/A") {
                                            if (@$item->Resultado2 <= $aux) {
                                                $auxCon = "CUMPLE";
                                            } else {
                                                $auxCon = "NO CUMPLE";
                                            }
                                        } else {
                                            $auxCon = "N/A";
                                        }
                                    } else {
                                        $auxCon = "N.N.";
                                    }
                                    break;
                            }
                        } else {
                            $aux = "N/A";
                            $auxCon = "N/A";
                        }
                        break;
                    case 30:
                        $limNo = DB::table('limitepnorma_127')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_min != "") {
                                $aux = $limNo[0]->Per_min . " - " . $limNo[0]->Per_max;
                            } else {
                                $aux = $limNo[0]->Per_max;
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 7:
                        $limNo = DB::table('limitepnorma_201')->where('Id_parametro', $item->Id_parametro)->get();
                        if ($limNo->count()) {
                            if ($limNo[0]->Per_max != "") {
                                $aux = $limNo[0]->Per_max;
                            } else {
                                $aux = "N/A";
                            }
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 27:
                        $limNo = DB::table('limite001_2021')->where('Id_parametro', $item->Id_parametro)->where('Id_categoria', $solicitud->Id_reporte2)->get();
                        if ($limNo->count()) {
                            $aux = $limNo[0]->Pd;
                        } else {
                            $aux = "N/A";
                        }
                        break;
                    case 365:
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
            array_push($limitesCon, $auxCon);
        }
        $campoCompuesto = CampoCompuesto::where('Id_solicitud', $idSol)->first();

        //Id Firmas
        //ID 4 Luisita
        //ID 12 Sandy
        //ID 14 Lupita
        //ID 35 Agueda
        //ID 31 elsa

        switch ($solModel->Id_norma) {
            case 5:
            case 7:
            case 30:
                //potable y purificada
                //$firma1 = User::find(14) ;
                $firma1 = User::find(31);
                //  $firma1 = User::find(12); // Reviso
                $firma2 = User::find(14); // Autorizo
                //$firma2 = User::find(12); // Autorizo
                //$firma2 = User::find(14);
                break;
            default:
                //$firma1 = User::find(12); // Reviso
                //$firma1 = User::find(14); //reviso
                $firma1 = User::find(14); //reviso
                // $firma2 = User::find(35); //Autorizo
                $firma2 = User::find(31); //Autorizo
                //$firma2 = User::find(12); // Autorizo

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
        $norma = Norma::where('Id_norma', $solicitud->Id_norma)->first();
        // cambio 
        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $firma1->name . ' | ' . $solicitud->Folio_servicio;
        $dataFirma2 = $firma2->name . ' | ' . $solicitud->Folio_servicio;
        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        if ($tempProceso->Supervicion != 0) {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript1 = "";
        }
        if ($tempProceso->Firma_aut != 0) {
            $firmaEncript2 =  openssl_encrypt($dataFirma2, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript2 = "";
        }
        $tipo = 2;
        $newTitulo = 1;
        $data = array(
            'incerAux' => $incerAux,
            'newTitulo' => $newTitulo,
            'tipo' => $tipo,
            'url' => url(''),
            'firmaEncript1' => $firmaEncript1,
            'firmaEncript2' => $firmaEncript2,
            'norma' => $norma,
            'limitesCon' => $limitesCon,
            'impresion' => $impresion,
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
            // 'tipo' => $tipo,
            'rfc' => $rfc,
            'reportesInformes' => $reportesInformes,
        );

        //BODY;Por añadir validaciones, mismas que se irán implementando cuando haya una tabla en la BD para los informes
        $htmlInforme = view('exports.informes.diario.bodyInforme', $data);
        $htmlHeader = view('exports.informes.diario.headerInforme', $data);
        $htmlFooter = view('exports.informes.diario.footerInforme', $data);
        $mpdf->setHeader("{PAGENO} / {nbpg} <br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        //Para la proteccion con contraseña, es el segundo parametro de la función SetProtection, el tercer parametro es una contraseña de propietario para permitir más acciones
        //En el caso del ultimo parámetro es la longitud del cifrado
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';

        // $mpdf->SetProtection(array(), 654, null, 128);
        // Establecer protección con contraseña de usuario y propietario

        $folPadre = Solicitud::where('Id_solicitud', $solicitud->Hijo)->first();
        $primeraLetra = substr($cliente->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();

        // Definir la ruta donde quieres guardar el PDF
        $nombreArchivoSeguro = str_replace('/', '-', $solicitud->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSol->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $solicitud->Fecha_muestreo . '/' . $folioPadre);

        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-informe.pdf';
        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
        $mpdf->Output('Informe de resultados sin comparacion.pdf', 'I');
    }
    public function setfirmaPad(Request $res)
    {
        $msg = "Firma Autorizada";

        $temp = ProcesoAnalisis::where('Id_solicitud', $res->id)->first();
        $temp->Firma_autorizo = $res->firma;
        $temp->save();

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function BuscarMesA(Request $res)
    {
        $fecha = explode('-', $res->mes); // mes viene como "2025-04"
        $año = $fecha[0];
        $mes = $fecha[1];

        $tipoReporte = TipoReporte::all();
        $model = DB::table('ViewProcesoAnalisis')
            ->where('Cancelado', 0)
            ->where('Padre', 1)
            ->whereYear('created_at', $año)
            ->whereMonth('created_at', $mes)->where('Padre', 1)
            ->orderBy("Id_procAnalisis", "desc")
            ->get();

        // Crear un array con los datos para las filas de la tabla
        $rows = $model->map(function ($item) {
            return [
                'id_solicitud' => $item->Id_solicitud,
                'folio' => $item->Folio,
                'empresa' => $item->Empresa,
                'clave_norma' => $item->Clave_norma,
                'servicio' => $item->Servicio,
                'obs_proceso' => $item->Obs_proceso
            ];
        });


        return response()->json(['rows' => $rows]);
    }
    public function exportPdfCustodiaInternaVidrio($idSol)
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
        $mpdf->showWatermarkImage = true;

        $model = DB::table('ViewSolicitud2')->where('Id_solicitud', $idSol)->first();
        $proceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $proceso->Impresion_cadena = 1;
        $proceso->save();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();

        $auxSolPadre = Solicitud::where('Id_solicitud', $model->Hijo)->first();

        $areaParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $idSol)->where('Id_parametro', 173)->get();
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
        $contAux = 0;


        // Comentado temporal
        // $cadenaGenerales = CadenaGenerales::where('Id_solicitud',$idSol)->get();
        // if ($cadenaGenerales->count()) {
        // }else{
        //     $this->setSupervicion($model->Hijo);
        //     $cadenaGenerales = CadenaGenerales::where('Id_solicitud',$idSol)->get();    

        // }

        $this->setSupervicion($model->Hijo);
        $cadenaGenerales = CadenaGenerales::where('Id_solicitud', $idSol)->where('Area', 'LIKE', '%Toxicidad Vibrio fischeri%')->get();


        $paramResultado = DB::table('ViewCodigoParametro')
            ->where('Id_solicitud', $idSol)
            ->where('Id_parametro',  173)
            ->where('Cadena', 1)
            ->where('Id_area', '!=', 9)
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
                                $resTemp = number_format($item->Resultado, 2);
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
                                if ($item->Resultado2 > 8) {
                                    $resTemp = '>' . 8;
                                } else {
                                    $resTemp = $item->Resultado;
                                }
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

                    case 9: //nitrogeno amoniacal
                    case 83: //kejendal
                    case 10: //organico
                    case 11: //nitrogeno total
                    case 15: //Fosforo
                    case 251:
                    case 77:
                    case 46:
                        // case 152:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 2, ".", "");
                            }
                        }

                        break;
                    case 71: //DBO SOLUBLE
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {
                                //$resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 2, ".", "");
                                //$resTemp = @$item->Resultado2;
                            }
                        }

                        break;

                    case 152:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                        }

                        break;
                    case 133:
                    case 58:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {

                                // $resTemp = round($item->Resultado2, 2);
                                $resTemp = number_format(@$item->Resultado2, 1, ".", "");
                            }
                        }
                        break;
                    case 22:
                    case 20: //cobre total
                    case 7:
                    case 8:
                    case 23: //niquel
                    case 24: //plomo total
                    case 25: //zinc total
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
                        } else {
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
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . number_format(@$item->Limite, 3, ".", "");
                            } else {
                                $resTemp = number_format(@$item->Resultado2, 3, ".", "");
                            }
                        }

                        break;
                    case 132:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "-----";
                        } else {
                            if ($item->Resultado2 > 0) {
                                if ($item->Resultado2 >= 8) {
                                    $resTemp = "> 8";
                                } else {
                                    $resTemp = $item->Resultado2;
                                }
                            } else {
                                $resTemp = "< 1.1";
                            }
                        }

                        break;
                    case 32:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "-----";
                        } else {
                            $restTemp = $item->Resultado2;
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
                        switch ($model->Id_norma) {
                            case 1:
                            case 27:
                            case 9:
                            case 2:
                            case 3:
                            case 4:
                            case 33:
                            case 21:
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
                    case 64:

                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite;
                            } else {
                                $resTemp = number_format($item->Resultado2, 2);
                            }
                        }
                        break;
                    case 271:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            $resTemp = number_format($item->Resultado2, 1);
                        }
                        break;
                    case 97:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            $resTemp = round($item->Resultado2);
                        }
                        break;
                    case 365:
                    case 370:
                    case 372:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            if ($item->Resultado2 < $item->Limite) {
                                $resTemp = "< " . $item->Limite . " | pH: " . $item->Ph_muestra;
                            } else {
                                $resTemp = $item->Resultado2 . " | pH: " . $item->Ph_muestra;
                            }
                        }
                        break;
                    case 102:
                        if ($item->Resultado2 == "NULL" || $item->Resultado2 == NULL) {
                            $resTemp = "----";
                        } else {
                            $resTemp = "436nm: " . number_format($item->Resultado, 1, '.', '') . "| 525nm: " . number_format($item->Resultado2, 1, '.', '') . "| 620nm: " . number_format($item->Resultado_aux, 1, '.', '');
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
                                case 33:
                                    if ($puntoMuestreo->Condiciones != 1) {
                                        if ($item->Resultado2 >= 3500) {
                                            $resTemp = "> 3500";
                                        } else {
                                            $resTemp = round($item->Resultado2);
                                        }
                                    } else {
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
                                case 33:
                                case 9:
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
                                    } else {
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

        $recepcion = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();
        $firmaRes = User::where('id', 14)->first();
        // $firmaRes = User::where('id', 4)->first();
        $reportesCadena = DB::table('ViewReportesCadena')->where('Num_rev', 9)->first(); //Condición de busqueda para las configuraciones(Historicos)

        $clave  = 'fol123ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $method = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

        $folioSer = $model->Folio_servicio;
        $folioEncript =  openssl_encrypt($folioSer, $method, $clave, false, $iv);


        $claveFirma = 'folinfdia321ABC!"#Loremipsumdolorsitamet';
        //Metodo de encriptaciÃ³n
        $methodFirma = 'aes-256-cbc';
        // Puedes generar una diferente usando la funcion $getIV()
        $ivFirma = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
        $dataFirma1 = $reportesCadena->Nombre_responsable . ' | ' . $model->Folio_servicio;

        $tempProceso = ProcesoAnalisis::where('Id_solicitud', $idSol)->first();

        if ($tempProceso->Supervicion != 0) {
            $firmaEncript1 =  openssl_encrypt($dataFirma1, $methodFirma, $claveFirma, false, $ivFirma);
        } else {
            $firmaEncript1 = "";
        }


        // $mpdf->showWatermarkImage = true;
        $data = array(
            'firmaEncript1' => $firmaEncript1,
            'cadenaGenerales' => $cadenaGenerales,
            'idParametro' => $idParametro,
            'idArea' => $idArea,

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
            'phMue6stra' => $phMuestra,
            'areaParam' => $areaParam,
            'norma' => $norma,
            'model' => $model,
            'reportesCadena' => $reportesCadena,
        );

        $htmlFooter = view('exports.campo.cadenaCustodiaVidrio.footerCadena', $data);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $htmlInforme = view('exports.campo.cadenaCustodiaVidrio.bodyCadena', $data);
        $mpdf->WriteHTML($htmlInforme);
        $mpdf->CSSselectMedia = 'mpdf';

        // Definir la ruta donde quieres guardar el PDF
        $folPadre = Solicitud::where('Id_solicitud', $model->Hijo)->first();
        $cliente = SucursalCliente::where('Id_sucursal', $model->Id_sucursal)->first();
        $primeraLetra = substr($cliente->Empresa, 0, 1);
        $passUse = $folPadre->Folio_servicio . "" . $primeraLetra;
        $mpdf->SetProtection(array('print', 'copy'), $passUse, '..', 128);

        // echo $passUse;
        $proceso = ProcesoAnalisis::where('Id_solicitud', $folPadre->Id_solicitud)->first();
        $proceso->Pass_archivo = $passUse;
        $proceso->save();

        $nombreArchivoSeguro = str_replace('/', '-', $model->Folio_servicio);
        $folioPadre = str_replace('/', '-', $auxSolPadre->Folio_servicio);

        $rutaDirectorio = storage_path('app/public/clientes/' . $model->Fecha_muestreo . '/' . $folioPadre);

        // Asegúrate de que el directorio existe, si no, créalo
        if (!File::isDirectory($rutaDirectorio)) {
            File::makeDirectory($rutaDirectorio, 0755, true, true);
        }

        $filePath = $rutaDirectorio . '/' . $nombreArchivoSeguro . '-custodia.pdf';

        // Guardar el archivo en la ruta especificada
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);


        $mpdf->Output('Cadena de Custodia Interna.pdf', 'I');
    }
    public function setIncertidumbre(Request $res)
    {
        $model = CodigoParametros::where('Id_codigo',$res->id)->first();
        $model->Incertidumbre = $res->incertidumbre;
        $model->save();
        $data = array(
            'model' => '',
        );
        return response()->json($data);
    }
}
