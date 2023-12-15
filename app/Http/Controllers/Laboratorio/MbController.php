<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\BitacoraColiformes;
use App\Models\BitacoraMb;
use App\Models\Bitacoras;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleCloro;
use App\Models\ObservacionMuestra;
use App\Models\Parametro;
use App\Models\ReportesFq;
use App\Models\ReportesMb;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales;
use App\Models\BlancoCurvaMetales;
use App\Models\CalentamientoMatraz;
use App\Models\CodigoParametros;
use App\Models\ControlCalidad;
use App\Models\ConvinacionEcoli;
use App\Models\ConvinacionesEcoli;
use App\Models\CurvaCalibracionMet;
use App\Models\DqoDetalle;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use App\Models\PruebaConfirmativaFq;
use App\Models\PruebaPresuntivaFq;
use App\Models\SembradoFq;
use App\Models\DqoFq;
use App\Models\EnfriadoMatraces;
use App\Models\EnfriadoMatraz;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDboIno;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEnterococos;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleHH;
use App\Models\LoteTecnica;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\Nmp1Micro;
use App\Models\PlantillaBitacora;
use App\Models\PlantillaMb;
use App\Models\TiempoReflujo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MbController extends Controller
{
    // todo ************************************************
    // todo Inicio de anlisis
    // todo ************************************************
    public function analisis()
    {
        $model = DB::table('proceso_analisis')->get();

        //Devuelve el tamaño del arreglo model
        $elements = DB::table('proceso_analisis')->count();

        //Para buscar el punto de muestreo 
        $puntoMuestreo = DB::table('cotizacion_puntos')->get();
        $puntoMuestreoLength = DB::table('cotizacion_puntos')->count();
        $solicitudPuntos = DB::table('solicitud_puntos')->get();
        $solicitudPuntosLength = DB::table('solicitud_puntos')->count();

        //Para select Filtro de vista análisis
        $tecnicas = DB::table('tecnicas')->get();

        //Para buscar la Norma de la solicitud
        $solicitud = DB::table('ViewSolicitud')->get();
        $solicitudLength = DB::table('ViewSolicitud')->count();

        //Para buscar los parámetros de la solicitud
        $parametros = DB::table('parametros')->get();
        $parametrosLength = DB::table('parametros')->count();

        return view('laboratorio.mb.analisis', compact('model', 'elements', 'solicitud', 'solicitudLength', 'tecnicas', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'parametrosLength', 'puntoMuestreo', 'puntoMuestreoLength'));
    }
    // todo ************************************************
    // todo fin de anlisis
    // todo ************************************************

    // todo ************************************************
    // todo Inicio de Observación
    // todo ************************************************
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')
            ->orWhere('Id_tipo_formula', 7)
            ->get();
        return view('laboratorio.mb.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        // todo - Area analisis = id 13
        $solicitudModel = DB::table('ViewSolicitud')->get();
        $sw = false;
        foreach ($solicitudModel as $item) {
            $paramModel = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_tipo_formula', $request->id)->get();
            $sw = false;
            foreach ($paramModel as $item2) {
                $areaModel = DB::table('ViewTipoFormulaAreas')->where('Id_formula', $item2->Id_tipo_formula)->where('Id_area', 13)->get();
                if ($areaModel->count()) {
                    $sw = true;
                }
            }
            if ($sw == true) {
                // $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Id_analisis',$item->Id_solicitud)->get();
                $model = ObservacionMuestra::where('Id_analisis', $item->Id_solicitud)->where('Id_area', 13)->get();
                if ($model->count()) {
                } else {
                    ObservacionMuestra::create([
                        'Id_analisis' => $item->Id_solicitud,
                        'Id_area' => 13,
                        'Ph' => '',
                        'Solido' => '',
                        'Olor' => '',
                        'Color' => '',
                        'Observacion' => '',
                    ]);
                }
                $sw = false;
            }
        }
        $model = DB::table('ViewObservacionMuestra')->where('Id_area', 13)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    //--------------------CREACION DE CONTROLES DE CALIDAD AUTOMATICOS SEGUN EL PARAMETRO-----------------------------
    public function createControlesCalidadMb(Request $request)
    {
        switch ($request->idParametro) {
            case 12:
            case 35:
                $muestra = LoteDetalleColiformes::where('id_lote', $request->idLote)->first();
                //positivo
                $model = $muestra->replicate();
                $model->Id_control = 8;
                $model->Resultado = NULL;
                $model->save();
                //blanco 
                $model = $muestra->replicate();
                $model->Id_control = 5;
                $model->Resultado = NULL;
                $model->save();
                //negativo
                $model = $muestra->replicate();
                $model->Id_control = 18;
                $model->Resultado = NULL;
                $model->save();

                $muestra = LoteDetalleColiformes::where('id_lote', $request->idLote)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Asignado = $muestra->count();
                $loteModel->save();

                break;
            case 253:
                $muestra = LoteDetalleColiformes::where('id_lote', $request->idLote)->first();
                //blanco 
                $$model = $muestra->replicate();
                $model->Id_control = 5;
                $model->Resultado = NULL;
                $model->save();
                //positivo
                $model = $muestra->replicate();
                $model->Id_control = 8;
                $model->Resultado = NULL;
                $model->save();
                break;
            default:

                break;
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function aplicarObservacion(Request $request)
    {
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Id_area', 5)->where('Folio', 'LIKE', "%{$request->folioActual}%")->first();

        $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
        $observacion->Ph = $request->ph;

        $observacion->Solido = $request->solidos;
        $observacion->Olor = $request->olor;
        $observacion->Color = $request->color;
        $observacion->Observaciones = $request->observacionGeneral;
        $observacion->save();


        $model = DB::table('ViewObservacionMuestra')->where('Id_area', 5)->get();

        $data = array(
            'model' => $model,
            'view' => $viewObservacion,
        );
        return response()->json($data);
    }

    // todo ************************************************
    // todo Inicio de Captura
    // todo ************************************************

    public function tipoAnalisis()
    {
        return view('laboratorio.mb.tipoAnalisis');
    }


    //MÉTODO DE PRUEBA
    public function capturaMicro()
    {
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        $controlModel = ControlCalidad::all();
        return view('laboratorio.mb.captura', compact('parametro', 'controlModel'));
    }

    public function getLoteMicro(Request $request)
    {
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->formulaTipo)->where('Fecha', $request->fechaAnalisis)->get();

        $data = array(
            'lote' => $model,
        );
        return response()->json($data);
    }
    public function getLoteCapturaMicro(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $indice = array();
        $detalle = array();
        $resultados = array();
        switch ($loteModel->Id_tecnica) {
            case 12: //todo Coliformes+
            case 132:
            case 133:
            case 135:
            case 134:
                case 35:
                $detalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->get();
                break;
            
            case 253: //todo  ENTEROCOCO FECAL
                # code...
                $detalle = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $request->idLote)->get();
                break;
            case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                # code...
                $detalle = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $request->idLote)->get();
                break;
            case 16: //todo Huevos de Helminto 
                # code...
                $detalle = DB::table('ViewLoteDetalleHH')->where('Id_lote', $request->idLote)->get();
                break;
            case 78: // E. coli
                $detalle = DB::table('ViewLoteDetalleEcoli')->where('Id_lote', $request->idLote)->get();
                foreach ($detalle as $item) {
                    $values = LoteDetalleColiformes::where('Id_analisis', $item->Id_analisis)->first();
                    if ($values != null) {
                        if ($values->Indice == 0) {
                            array_push($indice, 1);
                        }else {
                            array_push($indice, $values->Indice);
                        }
                       
                    } else {
                        $indice = null;
                        break;
                    }
                }
                
                break;
            default:
                # code...
                break;
        }

        $data = array(
            'indice' => $indice,
            'detalle' => $detalle,
            'resul' => $resultados,
        );
        return response()->json($data);
    }
    public function getDetalleColiAlimentos(Request $request)
    {
        $model = DB::table('ViewLoteDetalleColiformes')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleEcoli(Request $request) //tambien para E. coli Alimentos
    {
        $model = DB::table('ViewLoteDetalleEcoli')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
        $convinaciones = ConvinacionesEcoli::where('Id_detalle', $request->idDetalle)->where('Colonia', $request->colonia)->first();
        $data = array(
            'model' => $model,
            'convinaciones' => $convinaciones,
        );
        return response()->json($data);
    }
    public function getDetalleCol(Request $request)
    {
        $model = DB::table('ViewLoteDetalleColiformes')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleHH(Request $request)
    {
        $model = DB::table('ViewLoteDetalleHH')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleDbo(Request $request)
    {
        switch ($request->tipo) {
            case 1:
                $model = DB::table('ViewLoteDetalleDbo')->where('Id_detalle', $request->idDetalle)->first(); // Asi se hara con las otras
                $model2 = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $model->Id_analisis)->first();
                break;
            case 2:
                $model = DB::table('ViewLoteDetalleDbo')->where('Id_detalle', $request->idDetalle)->first();
                $model2 = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $model->Id_analisis)->first();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'model' => $model,
            'model2' => $model2,
        );
        return response()->json($data);
    }

    public function createControlCalidadMb(Request $request)
    {
        switch ($request->idParametro) {
            case 12: // coliformes +
                $muestra = LoteDetalleColiformes::where('Id_detalle', $request->idMuestra)->first();
                break;
            case 5:
                $muestra = LoteDetalleDbo::where('Id_detalle', $request->idMuestra)->first();
                break;
            case 16:
                $muestra = LoteDetalleHH::where('Id_detalle', $request->idMuestra)->first();
                break;
            case 78:
                $muestra = LoteDetalleEcoli::where('Id_detalle', $request->idMuestra)->first();
                break;
            default:
                # code...
                break;
        }

        $model = $muestra->replicate();
        $model->Id_control = $request->idControl;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }


    //NUEVA FUNCIÓN BUSQUEDA FILTROS > CAPTURA.JS
    public function busquedaFiltros(Request $request)
    {
        //REALIZARÁ CONSULTA A LA BASE DE DATOS VIEWLOTEDETALLE PARA RECUPERAR LOS DATOS

        //*************************************
        $idLote = $request->id_Lote;

        $consultas = [
            'Id_lote' => $idLote
        ];
        //*************************************

        //ASIGNACIONES DE PRUEBA
        //$tipoFormula = $request->formulaTipo;
        //$numeroMuestra = $request->numMuestra;
        //$analisisFecha = $request->fechaAnalisis;

        /*$consultas = [
            'Folio_servicio' => $numeroMuestra,
            'Parametro' => $tipoFormula
        ];*/

        $loteDetail = DB::table('ViewLoteDetalle')->where($consultas)->first();

        return response()->json(
            compact('loteDetail')
        );
    }
    public function operacionEcoliFinal(Request $request)
    {
        $muestra = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
        $res = $muestra->Colonia1 + $muestra->Colonia2 + $muestra->Colonia3 + $muestra->Colonia4 + $muestra->Colonia5;
        switch ($res) {
            case 1:
                $resultado = "1.1";
                break;
            case 2:
                $resultado = "2.6";
                break;
            case 3:
                $resultado = "4.6";
                break;
            case 4:
                $resultado = "8.0";
                break;
            case 5:
                $resultado = "9.0";
                break;
            default:
                $resultado = "0";
                break;
        }

        $model = LoteDetalleEcoli::find($request->idDetalle);
        $model->resultado = $resultado;
        $model->save();

        $data = array(
            'res' => $res,
            'muestra' => $muestra,
            'resultado' => $resultado,
        );
        return response()->json($data);
    }

    public function operacionEcoli(Request $request)
    {
        $muestra = LoteDetalleColiformes::where('Id_detalle', $request->idDetalle)->first();
        $view = DB::table('ViewLoteDetalleEcoli')->where('Id_detalle', $request->idDetalle)->first();
        $loteDetalle = "";
        $res1 = "";
        $res2 = "";
        $colonia1 = 0;
        $muestraR = 0;
        $temp1 = "";
        $temp1 = $request->rm1 . "" . $request->vp1 . "" .  $request->citrato1 . "" . $request->bgn1;
        $temp2 = $request->rm2 . $request->vp2 . $request->citrato2 . $request->bgn2;
        switch ($temp1) {
            case "+--BGN":
                $colonia1 = 1;
                break;
            default:
                $muestraR = 0;
                break;
        }
        if ($colonia1 == 1) {
            $muestraR = 1;
        } else {

            switch ($temp2) {
                case "+--BGN":
                    $muestraR = 1;
                    break;
                default:
                    $muestraR = 0;
                    break;
            }
        }

        if ($temp1 == "+--BGN") {
            $res1 = "Positivo";
        } else {
            $res1 = "Negativo";
        }
        if ($temp2 == "+--BGN") {
            $res2 = "Positivo";
        } else {
            $res2 = "Negativo";
        }
        $validacion = ConvinacionesEcoli::where('Id_detalle', $request->idDetalle)
            ->where('Colonia', $request->colonia)->first();

        if ($validacion == null) {
            $model = ConvinacionesEcoli::create([
                'Id_detalle' => $request->idDetalle,
                'Id_lote' => $request->idLote,
                'Codigo' => $view->Codigo,
                'Colonia' => $request->colonia,
                'Indol' => $request->indol1,
                'Rm' => $request->rm1,
                'Vp' => $request->vp1,
                'Citrato' => $request->citrato1,
                'BGN' => $request->bgn1,
                'Indol2' => $request->indol2,
                'Rm2' => $request->rm2,
                'Vp2' => $request->vp2,
                'Citrato2' => $request->citrato2,
                'BGN2' => $request->bgn2,
                'ResUno' => $res1,
                'ResDos' => $res2,
                'Resultado' => $muestraR,
                'Observacion' => $request->observacion,
                

            ]);
        } else {
            $model = ConvinacionesEcoli::where('Id_detalle', $request->idDetalle)
                ->where('Colonia', $request->colonia)->first();
            $model->Codigo = $view->Codigo;
            $model->Indol = $request->indol1;
            $model->Rm = $request->rm1;
            $model->Vp = $request->vp1;
            $model->Citrato = $request->citrato1;
            $model->BGN = $request->bgn1;
            $model->Indol2 = $request->indol2;
            $model->Rm2 = $request->rm2;
            $model->Vp2 = $request->vp2;
            $model->Citrato2 = $request->citrato2;
            $model->BGN2 = $request->bgn2;
            $model->ResUno = $res1;
            $model->ResDos = $res2;
            $model->Resultado = $muestraR;
            $model->Observacion = $request->observacion;
            $model->save();
        }

        $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
        $loteDetalle->Positivos = $request->indice;
        $loteDetalle->save();

        switch ($request->colonia) {
            case 1:
                $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
                $loteDetalle->Colonia1 = $muestraR;
                $loteDetalle->save();
                break;
            case 2:
                $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
                $loteDetalle->Colonia2 = $muestraR;
                $loteDetalle->save();
                break;
            case 3:
                $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
                $loteDetalle->Colonia3 = $muestraR;
                $loteDetalle->save();
                break;
            case 4:
                $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
                $loteDetalle->Colonia4 = $muestraR;
                $loteDetalle->save();
                break;
            case 5:
                $loteDetalle = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
                $loteDetalle->Colonia5 = $muestraR;
                $loteDetalle->save();
                break;
            default:
                $loteDetalle = "default";
        }

        $muestra = LoteDetalleEcoli::where('Id_detalle', $request->idDetalle)->first();
        $res = $muestra->Colonia1 + $muestra->Colonia2 + $muestra->Colonia3 + $muestra->Colonia4 + $muestra->Colonia5;
        switch ($res) {
            case 1:
                $resultado = "1.1";
                break;
            case 2:
                $resultado = "2.6";
                break;
            case 3:
                $resultado = "4.6";
                break;
            case 4:
                $resultado = "8.0";
                break;
            case 5:
                $resultado = "9.0";
                break;
            default:
                $resultado = "0";
                break;
        } 

        $model2 = LoteDetalleEcoli::find($request->idDetalle);
        $model2->resultado = $resultado;
        $model2->save();

        $data = array(
            'model' => $model,
            'Resultado' => $muestraR,
            'string' => $temp1,
            'colonia' => $request->colonia,
            'validacion' => $validacion,
            'loteDetalle' => $loteDetalle,
            'Codigo' => $muestra,

        );
        return response()->json($data);
    }
    public function operacionColAlimentos(Request $request)
    {
        $resultado = "";


        switch ($request->confirmativa2) {
            case 1:
                $resultado = "1.1";
                break;
            case 2:
                $resultado = "2.6";
                break;
            case 3:
                $resultado = "4.6";
                break;
            case 4:
                $resultado = "8.0";
                break;
            case 5:
                $resultado = "9.0";
                break;
            default:
                $resultado = "0";
                break;
        }
        $model = LoteDetalleColiformes::find($request->idDetalle);
        $model->Presuntiva1 = $request->presuntiva1;
        $model->Presuntiva2 = $request->presuntiva2;
        $model->Confirmativa1 = $request->confirmativa1;
        $model->Confirmativa2 = $request->confirmativa2;
        $model->Resultado = $resultado;
        if($request->confirmativa2 == "" || $request->confirmativa2 == "null"){
            $model->Indice = 1;
        } else{
            $model->Indice = $request->confirmativa2;
        }
        $model->save();

        $detalleParametro = Parametro::where('Id_parametro', $model->Id_parametro)->first();

        $data = array(
            'parametros' => $model->Id_parametro,
            'limite' => $detalleParametro->Limite,
            'resultado' => $resultado,
            'model' => $model,

        );

        return response()->json($data);
    }


    public function metodoCortoCol(Request $request)
    {
        $convinacion = Nmp1Micro::where('Nmp', $request->NMP)->first();
        $metodoCorto = 1;
        $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
        if($request->D1 == 10 && $request->D2 == 1 && $request->D3 == 0.1){
            $resultado = $convinacion->Nmp;
        } else {
            $resultado = (10 / $request->D1) * $convinacion->Nmp;
        }
       
                    $metodoCorto = 1;
                    $model = LoteDetalleColiformes::find($request->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $request->D1;
                    $model->Dilucion2 = $request->D2;
                    $model->Dilucion3 = $request->D3;
                    $model->Indice = $request->NMP;
                    $model->Muestra_tubos = $request->G3;
                    $model->Tubos_negativos = $request->G2;
                    $model->Tubos_positivos = $request->G1;

                    $model->Confirmativa1 = $request->con1;
                        $model->Confirmativa2 = $request->con2;
                        $model->Confirmativa3 = $request->con3;
                        $model->Confirmativa4 = $request->con4;
                        $model->Confirmativa5 = $request->con5;
                        $model->Confirmativa6 = $request->con6;
                        $model->Confirmativa7 = $request->con7;
                        $model->Confirmativa8 = $request->con8;
                        $model->Confirmativa9 = $request->con9;

                    $model->Presuntiva1 = $request->pre1;
                    $model->Presuntiva2 = $request->pre2;
                    $model->Presuntiva3 = $request->pre3;
                    $model->Presuntiva4 = $request->pre4;
                    $model->Presuntiva5 = $request->pre5;
                    $model->Presuntiva6 = $request->pre6;
                    $model->Presuntiva7 = $request->pre7;
                    $model->Presuntiva8 = $request->pre8;
                    $model->Presuntiva9 = $request->pre9;

                    $model->Presuntiva10 = $request->pre11;
                    $model->Presuntiva11 = $request->pre22;
                    $model->Presuntiva12 = $request->pre33;
                    $model->Presuntiva13 = $request->pre44;
                    $model->Presuntiva14 = $request->pre55;
                    $model->Presuntiva15 = $request->pre66;
                    $model->Presuntiva16 = $request->pre77;
                    $model->Presuntiva17 = $request->pre88;
                    $model->Presuntiva18 = $request->pre99;

                    $model->Resultado = $resultado;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
        
        $data = array(
            'convinacion' => $convinacion,
            'metodoCorto' => $metodoCorto,
            'positivos' =>  $positivos,
            'resultado' => $resultado,
            'model' => $model,
            'IdDetalle' => $request->idDetalle,  
           
         );

        return response()->json($data);
    }
   
    public function metodoCortoEnt(Request $request)
    {
        $convinacion = Nmp1Micro::where('Nmp', $request->NMP)->first();
        $metodoCorto = 1;
        $positivos = $convinacion->Col1 + $convinacion->Col2 + $convinacion->Col3;
        if($request->d1 == 10 && $request->d2 == 1 && $request->d3 == 0.1){
            $resultado = $convinacion->Nmp;
        } else {
            $resultado = (10 / $request->d3) * $convinacion->Nmp;
        }
        $data = array(
            'convinacion' => $convinacion,
            'metodoCorto' => $metodoCorto,
            'positivos' =>  $positivos,
            'resultado' => $resultado,
        );

        return response()->json($data);
    }
    public function operacion(Request $request)
    {
        $res1 = 0;
        $res = 0;
        $aux = 0;
        $numModel3 = 0;
        $tipo = 0;
        $metodoCorto = 0;
        $loteModel = LoteDetalleColiformes::where('Id_detalle',$request->idDetalle)->first();
        switch ($request->idParametro) {
            case 12: //todo Número más probable (NMP), en tubos múltiples
            case 35: //Escheruchia coli/acreditado
            case 137:
                # Coliformes
                if ($loteModel->Id_control == 1 || $loteModel->Id_control == 11) {
                    if ($request->indicador == 1) {
                        //guarda datos del metodo corto
                        $metodoCorto = 1;
                        $model = LoteDetalleColiformes::find($request->idDetalle);
                        $model->Tipo = 1;
                        $model->Dilucion1 = $request->D1;
                        $model->Dilucion2 = $request->D2;
                        $model->Dilucion3 = $request->D3;
                        $model->Indice = $request->NMP;
                        $model->Muestra_tubos = $request->G3;
                        $model->Tubos_negativos = $request->G2;
                        $model->Tubos_positivos = $request->G1;

                        if ($request->idParametro == 35) {
                            $model->Confirmativa1 = $request->con10;
                            $model->Confirmativa2 = $request->con11;
                            $model->Confirmativa3 = $request->con12;
                            $model->Confirmativa4 = $request->con13;
                            $model->Confirmativa5 = $request->con14;
                            $model->Confirmativa6 = $request->con15;
                            $model->Confirmativa7 = $request->con16;
                            $model->Confirmativa8 = $request->con17;
                            $model->Confirmativa9 = $request->con18;

                            $model->Confirmativa10 = $request->con1;
                            $model->Confirmativa11 = $request->con2;
                            $model->Confirmativa12 = $request->con3;
                            $model->Confirmativa13 = $request->con4;
                            $model->Confirmativa14 = $request->con5;
                            $model->Confirmativa15 = $request->con6;
                            $model->Confirmativa16 = $request->con7;
                            $model->Confirmativa17 = $request->con8;
                            $model->Confirmativa18 = $request->con9;
                        }else{
                            $model->Confirmativa1 = $request->con1;
                            $model->Confirmativa2 = $request->con2;
                            $model->Confirmativa3 = $request->con3;
                            $model->Confirmativa4 = $request->con4;
                            $model->Confirmativa5 = $request->con5;
                            $model->Confirmativa6 = $request->con6;
                            $model->Confirmativa7 = $request->con7;
                            $model->Confirmativa8 = $request->con8;
                            $model->Confirmativa9 = $request->con9;
                        }


                        $model->Presuntiva1 = $request->pre1;
                        $model->Presuntiva2 = $request->pre2;
                        $model->Presuntiva3 = $request->pre3;
                        $model->Presuntiva4 = $request->pre4;
                        $model->Presuntiva5 = $request->pre5;
                        $model->Presuntiva6 = $request->pre6;
                        $model->Presuntiva7 = $request->pre7;
                        $model->Presuntiva8 = $request->pre8;
                        $model->Presuntiva9 = $request->pre9;

                        $model->Presuntiva10 = $request->pre11;
                        $model->Presuntiva11 = $request->pre22;
                        $model->Presuntiva12 = $request->pre33;
                        $model->Presuntiva13 = $request->pre44;
                        $model->Presuntiva14 = $request->pre55;
                        $model->Presuntiva15 = $request->pre66;
                        $model->Presuntiva16 = $request->pre77;
                        $model->Presuntiva17 = $request->pre88;
                        $model->Presuntiva18 = $request->pre99;

                        $model->Resultado = $request->resultadoCol;
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    } else {

                        if ($request->idParametro == 35) {
                            $n1 = $request->con10 + $request->con11 + $request->con12;
                            $n2 = $request->con13 + $request->con14 + $request->con15;
                            $n3 = $request->con16 + $request->con17 + $request->con18;
                        }else{
                            $n1 = $request->con1 + $request->con2 + $request->con3;
                            $n2 = $request->con4 + $request->con5 + $request->con6;
                            $n3 = $request->con7 + $request->con8 + $request->con9;
                        } 
                        
                        

                        $numModel = Nmp1Micro::where('Col1', $n1)->where('Col2', $n2)->where('Col3', $n3)->first();
                        $numModel2 = Nmp1Micro::where('Col1', $n1)->where('Col2', $n2)->where('Col3', $n3)->get();
                        $tipo =  "";
                        if ($numModel2->count()) {
                            if ($request->D1 != 10 && $request->D2 != 1 && $request->D3 != 0.1) {
                                //Formula escrita 1
                                $op1 = 10 / $request->D1;
                                $res = $op1 * $numModel->Nmp;
                                $tipo = 2; // Formula 1
                            } else {
                                //Formula comparación por tabla  
                                $res = $numModel->Nmp;
                                $tipo = 1; // Formula Tabla
                            }
                        } else {
                            //Formula 2
                            $op1 = $request->G1 * 100;
                            $op2 = sqrt($request->G2 * $request->G3);
                            $res1 = $op1 / $op2;
                            $tipo = 3; //Formula 2
                            $numModel3 = Nmp1Micro::orderBy('Nmp', 'asc')->get();

                            foreach ($numModel3 as $item) {
                                if ($item->Nmp <= $res1) {
                                    $aux = $item->Nmp;
                                } else {
                                    $res = $aux;
                                }
                            }
                        }
                        $model = LoteDetalleColiformes::find($request->idDetalle);
                        $model->Tipo = $tipo;
                        $model->Dilucion1 = $request->D1;
                        $model->Dilucion2 = $request->D2;
                        $model->Dilucion3 = $request->D3;
                        $model->Indice = $res;
                        $model->Muestra_tubos = $request->G3;
                        $model->Tubos_negativos = $request->G2;
                        $model->Tubos_positivos = $request->G1;
                
                        if ($request->idParametro == 35) {
                            $model->Confirmativa1 = $request->con10;
                            $model->Confirmativa2 = $request->con11;
                            $model->Confirmativa3 = $request->con12;
                            $model->Confirmativa4 = $request->con13;
                            $model->Confirmativa5 = $request->con14;
                            $model->Confirmativa6 = $request->con15;
                            $model->Confirmativa7 = $request->con16;
                            $model->Confirmativa8 = $request->con17;
                            $model->Confirmativa9 = $request->con18;
    
                            $model->Confirmativa10 = $request->con1;
                            $model->Confirmativa11 = $request->con2; 
                            $model->Confirmativa12 = $request->con3;
                            $model->Confirmativa13 = $request->con4;
                            $model->Confirmativa14 = $request->con5;
                            $model->Confirmativa15 = $request->con6;
                            $model->Confirmativa16 = $request->con7;
                            $model->Confirmativa17 = $request->con8;
                            $model->Confirmativa18 = $request->con9;
                        }else{
                            $model->Confirmativa1 = $request->con1;
                            $model->Confirmativa2 = $request->con2;
                            $model->Confirmativa3 = $request->con3;
                            $model->Confirmativa4 = $request->con4;
                            $model->Confirmativa5 = $request->con5;
                            $model->Confirmativa6 = $request->con6;
                            $model->Confirmativa7 = $request->con7;
                            $model->Confirmativa8 = $request->con8;
                            $model->Confirmativa9 = $request->con9;
                        }
                        $model->Presuntiva1 = $request->pre1;
                        $model->Presuntiva2 = $request->pre2;
                        $model->Presuntiva3 = $request->pre3;
                        $model->Presuntiva4 = $request->pre4;
                        $model->Presuntiva5 = $request->pre5;
                        $model->Presuntiva6 = $request->pre6;
                        $model->Presuntiva7 = $request->pre7;
                        $model->Presuntiva8 = $request->pre8;
                        $model->Presuntiva9 = $request->pre9;
                        $model->Presuntiva10 = $request->pre11;
                        $model->Presuntiva11 = $request->pre22;
                        $model->Presuntiva12 = $request->pre33;
                        $model->Presuntiva13 = $request->pre44;
                        $model->Presuntiva14 = $request->pre55;
                        $model->Presuntiva15 = $request->pre66;
                        $model->Presuntiva16 = $request->pre77;
                        $model->Presuntiva17 = $request->pre88;
                        $model->Presuntiva18 = $request->pre99;
                        $model->Resultado = $res;
                        $model->Analizo = Auth::user()->id;
                        $model->save();
                    } 
                }else{
                    $resAux = 0;
                    $model = LoteDetalleColiformes::find($request->idDetalle);
                    $model->Tipo = 1;
                    $model->Dilucion1 = $request->D1;
                    $model->Dilucion2 = $request->D2;
                    $model->Dilucion3 = $request->D3;
                    $model->Indice = $request->NMP;
                    $model->Muestra_tubos = $request->G3;
                    $model->Tubos_negativos = $request->G2;
                    $model->Tubos_positivos = $request->G1;

                    if ($request->idParametro == 35) {
                        $model->Confirmativa1 = $request->con10;
                        $model->Confirmativa2 = $request->con11;
                        $model->Confirmativa3 = $request->con12;
                        $model->Confirmativa4 = $request->con13;
                        $model->Confirmativa5 = $request->con14;
                        $model->Confirmativa6 = $request->con15;
                        $model->Confirmativa7 = $request->con16;
                        $model->Confirmativa8 = $request->con17;
                        $model->Confirmativa9 = $request->con18;

                        $model->Confirmativa10 = $request->con1;
                        $model->Confirmativa11 = $request->con2;
                        $model->Confirmativa12 = $request->con3;
                        $model->Confirmativa13 = $request->con4;
                        $model->Confirmativa14 = $request->con5;
                        $model->Confirmativa15 = $request->con6;
                        $model->Confirmativa16 = $request->con7;
                        $model->Confirmativa17 = $request->con8;
                        $model->Confirmativa18 = $request->con9;
                    }else{
                        $model->Confirmativa1 = $request->con1;
                        $model->Confirmativa2 = $request->con2;
                        $model->Confirmativa3 = $request->con3;
                        $model->Confirmativa4 = $request->con4;
                        $model->Confirmativa5 = $request->con5;
                        $model->Confirmativa6 = $request->con6;
                        $model->Confirmativa7 = $request->con7;
                        $model->Confirmativa8 = $request->con8;
                        $model->Confirmativa9 = $request->con9;
                    }


                    $model->Presuntiva1 = $request->pre1;
                    $model->Presuntiva2 = $request->pre2;
                    $model->Presuntiva3 = $request->pre3;
                    $model->Presuntiva4 = $request->pre4;
                    $model->Presuntiva5 = $request->pre5;
                    $model->Presuntiva6 = $request->pre6;
                    $model->Presuntiva7 = $request->pre7;
                    $model->Presuntiva8 = $request->pre8;
                    $model->Presuntiva9 = $request->pre9;

                    $model->Presuntiva10 = $request->pre11;
                    $model->Presuntiva11 = $request->pre22;
                    $model->Presuntiva12 = $request->pre33;
                    $model->Presuntiva13 = $request->pre44;
                    $model->Presuntiva14 = $request->pre55;
                    $model->Presuntiva15 = $request->pre66;
                    $model->Presuntiva16 = $request->pre77;
                    $model->Presuntiva17 = $request->pre88;
                    $model->Presuntiva18 = $request->pre99;

                    if ($request->con1 > 0) {
                        $resAux = 3;
                    }else{
                        $resAux = 0;
                    }

                    $model->Resultado = $resAux;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
                    $res = $resAux;
                    // $res = "Entro";
                }


                break;
           
            case 253: //todo Número más probable (NMP), en tubos múltiples
                $loteModel = LoteDetalleEnterococos::where('Id_detalle',$request->idDetalle)->first();
                if ($loteModel->Id_control == 1 || $loteModel->Id_control == 11) {
                    $n1 = $request->Confirmativa21 + $request->Confirmativa22 + $request->Confirmativa23;
                    $n2 = $request->Confirmativa24 + $request->Confirmativa25 + $request->Confirmativa26;
                    $n3 = $request->Confirmativa27 + $request->Confirmativa28 + $request->Confirmativa29;
    
                    $numModel = Nmp1Micro::where('Col1', $n1)->where('Col2', $n2)->where('Col3', $n3)->first();
                    $numModel2 = Nmp1Micro::where('Col1', $n1)->where('Col2', $n2)->where('Col3', $n3)->get();
                    $tipo =  "";
                    if ($numModel2->count()) {
                        if ($request->D1 != 10 && $request->D2 != 1 && $request->D3 != 0.1) {
                            //Formula escrita 1
                            if ($request->idParametro == 35) {
                                $op1 = 10 / $request->D1;
                                $res = $op1 * $request->NMP;
                            } else {
                                $res =  round($request->NMP / $request->D3);
                            }
    
                            $tipo = 2; // Formula 1
                        } else {
                            //Formula comparación por tabla  
                            $res = $numModel->Nmp;
                            $tipo = 1; // Formula Tabla
                        }
                    } else {
                        //Formula 2
                        $op1 = $request->G1 * 100;
                        $op2 = sqrt($request->G2 * $request->G3);
                        $res1 = round(($op1 / $op2));
                        $tipo = 3; //Formula 2
                        $numModel3 = Nmp1Micro::orderBy('Nmp', 'asc')->get();
    
                        foreach ($numModel3 as $item) {
                            if ($item->Nmp <= $res1) {
                                $aux = $item->Nmp;
                            } else {
                                $res = $aux;
                            }
                        }
                        if ($request->idParametro == 35) {
                            $res = $aux;
                        }
                    }
                    $model = LoteDetalleEnterococos::find($request->idDetalle);
                    $model->Tipo = $tipo;
                    $model->Dilucion1 = $request->D1;
                    $model->Dilucion2 = $request->D2;
                    $model->Dilucion3 = $request->D3;
                    $model->Indice = $res;
                    $model->Muestra_tubos = $request->G3;
                    $model->Tubos_negativos = $request->G2;
                    $model->Tubos_positivos = $request->G1;
    
                    $model->Presuntiva11 = $request->Presuntiva11;
                    $model->Presuntiva12 = $request->Presuntiva12;
                    $model->Presuntiva13 = $request->Presuntiva13;
                    $model->Presuntiva14 = $request->Presuntiva14;
                    $model->Presuntiva15 = $request->Presuntiva15;
                    $model->Presuntiva16 = $request->Presuntiva16;
                    $model->Presuntiva17 = $request->Presuntiva17;
                    $model->Presuntiva18 = $request->Presuntiva18;
                    $model->Presuntiva19 = $request->Presuntiva19;
    
                    $model->Presuntiva21 = $request->Presuntiva21;
                    $model->Presuntiva22 = $request->Presuntiva22;
                    $model->Presuntiva23 = $request->Presuntiva23;
                    $model->Presuntiva24 = $request->Presuntiva24;
                    $model->Presuntiva25 = $request->Presuntiva25;
                    $model->Presuntiva26 = $request->Presuntiva26;
                    $model->Presuntiva27 = $request->Presuntiva27;
                    $model->Presuntiva28 = $request->Presuntiva28;
                    $model->Presuntiva29 = $request->Presuntiva29;
    
                    $model->Confirmativa11 = $request->Confirmativa11;
                    $model->Confirmativa12 = $request->Confirmativa12;
                    $model->Confirmativa13 = $request->Confirmativa13;
                    $model->Confirmativa14 = $request->Confirmativa14;
                    $model->Confirmativa15 = $request->Confirmativa15;
                    $model->Confirmativa16 = $request->Confirmativa16;
                    $model->Confirmativa17 = $request->Confirmativa17;
                    $model->Confirmativa18 = $request->Confirmativa18;
                    $model->Confirmativa19 = $request->Confirmativa19;
    
                    $model->Confirmativa21 = $request->Confirmativa21;
                    $model->Confirmativa22 = $request->Confirmativa22;
                    $model->Confirmativa23 = $request->Confirmativa23;
                    $model->Confirmativa24 = $request->Confirmativa24;
                    $model->Confirmativa25 = $request->Confirmativa25;
                    $model->Confirmativa26 = $request->Confirmativa26;
                    $model->Confirmativa27 = $request->Confirmativa27;
                    $model->Confirmativa28 = $request->Confirmativa28;
                    $model->Confirmativa29 = $request->Confirmativa29;
    
                    $model->Resultado = $res;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
                }else{
                    $resAux = 0;
                    $model = LoteDetalleEnterococos::find($request->idDetalle);
                    $model->Tipo = $tipo;
                    $model->Dilucion1 = $request->D1;
                    $model->Dilucion2 = $request->D2;
                    $model->Dilucion3 = $request->D3;
                    $model->Indice = $res;
                    $model->Muestra_tubos = $request->G3;
                    $model->Tubos_negativos = $request->G2;
                    $model->Tubos_positivos = $request->G1;
    
                    $model->Presuntiva11 = $request->Presuntiva11;
                    $model->Presuntiva12 = $request->Presuntiva12;
                    $model->Presuntiva13 = $request->Presuntiva13;
                    $model->Presuntiva14 = $request->Presuntiva14;
                    $model->Presuntiva15 = $request->Presuntiva15;
                    $model->Presuntiva16 = $request->Presuntiva16;
                    $model->Presuntiva17 = $request->Presuntiva17;
                    $model->Presuntiva18 = $request->Presuntiva18;
                    $model->Presuntiva19 = $request->Presuntiva19;
    
                    $model->Presuntiva21 = $request->Presuntiva21;
                    $model->Presuntiva22 = $request->Presuntiva22;
                    $model->Presuntiva23 = $request->Presuntiva23;
                    $model->Presuntiva24 = $request->Presuntiva24;
                    $model->Presuntiva25 = $request->Presuntiva25;
                    $model->Presuntiva26 = $request->Presuntiva26;
                    $model->Presuntiva27 = $request->Presuntiva27;
                    $model->Presuntiva28 = $request->Presuntiva28;
                    $model->Presuntiva29 = $request->Presuntiva29;
    
                    $model->Confirmativa11 = $request->Confirmativa11;
                    $model->Confirmativa12 = $request->Confirmativa12;
                    $model->Confirmativa13 = $request->Confirmativa13;
                    $model->Confirmativa14 = $request->Confirmativa14;
                    $model->Confirmativa15 = $request->Confirmativa15;
                    $model->Confirmativa16 = $request->Confirmativa16;
                    $model->Confirmativa17 = $request->Confirmativa17;
                    $model->Confirmativa18 = $request->Confirmativa18;
                    $model->Confirmativa19 = $request->Confirmativa19;
    
                    $model->Confirmativa21 = $request->Confirmativa21;
                    $model->Confirmativa22 = $request->Confirmativa22;
                    $model->Confirmativa23 = $request->Confirmativa23;
                    $model->Confirmativa24 = $request->Confirmativa24;
                    $model->Confirmativa25 = $request->Confirmativa25;
                    $model->Confirmativa26 = $request->Confirmativa26;
                    $model->Confirmativa27 = $request->Confirmativa27;
                    $model->Confirmativa28 = $request->Confirmativa28;
                    $model->Confirmativa29 = $request->Confirmativa29;
 
                    if ($request->Confirmativa21 > 0) {
                        $resAux = 3;
                    }else{
                        $resAux = 0;
                    }
                    $res = $resAux;
    
                    $model->Resultado = $resAux;
                    $model->Analizo = Auth::user()->id;
                    $model->save();
                }



                break;
            case 5:
            case 71: //todo Metodo electrometrico
                # DBO
                $temp = LoteDetalleDbo::where('Id_detalle',$request->idDetalle)->first();
                switch ($temp->Id_control) {
                    case 5: 
                        $res = $request->OIB - $request->OFB;
                        $d = 300 / $request->VB;
                        $res = round($res / $d,2);
                        $model = LoteDetalleDbo::find($request->idDetalle);
                        $model->Botella_final = $request->H;
                        $model->Botella_od = $request->G;
                        $model->Odf = $request->OFB;
                        $model->Odi = $request->OIB;
                        $model->Ph_final = $request->J;
                        $model->Ph_inicial = $request->I;
                        $model->Vol_muestra = $request->VB;
                        $model->Dilucion = $request->E;
                        $model->Vol_botella = $request->C;
                        $model->Resultado = $res;
                        $model->Analizo = Auth::user()->id;
                        $model->Sugerido = $request->S;
                        $model->save();
                        $tipo = 2;
                        break;
                    
                    default:
                    if ($request->tipo == 1) {
                        if ($request->D <= 0.1) {
                            $E = $request->D / $request->C;
                            $res = ($request->A - $request->B) / $E;
                            $res = round($res,2);    
                        }else{
                            $E = $request->D / $request->C;
                            $res = ($request->A - $request->B) / round($E, 3);
                            $res = round($res,2);    
                        }
                        
                        $model = LoteDetalleDbo::find($request->idDetalle);
                        $model->Botella_final = $request->H;
                        $model->Botella_od = $request->G;
                        $model->Odf = $request->B;
                        $model->Odi = $request->A;
                        $model->Ph_final = $request->J;
                        $model->Ph_inicial = $request->I;
                        $model->Vol_muestra = $request->D;
                        $model->Dilucion = $request->E;
                        $model->Vol_botella = $request->C;
                        $model->Resultado = $res;
                        $model->Analizo = Auth::user()->id;
                        $model->Sugerido = $request->S;
                        $model->save();
                        $tipo = 1;
                    } else {
                        $res = ($request->OI - $request->OF);
                        $model = LoteDetalleDbo::find($request->idDetalle);
                        $model->Odf = $request->OF;
                        $model->Odi = $request->OI;
                        $model->Vol_muestra = $request->V;
                        $model->Dilucion = $request->E;
                        $model->Resultado = $res;
                        $model->Analizo = Auth::user()->id;
                        $model->Sugerido = $request->S;
                        $model->save();
                        $tipo = 2;
                    }
                        break;
                }
            

                break;
            case 70:
                  # DBO
                // $res = (($request->A - $request->B) - (($request->C*($request->D - $request->E)) * $request->G ) / ($request->H * $request->I)) / $request->J;
                $aux = ($request->A - $request->B);
                $aux2 = ($request->C / $request->H) * ($request->D - $request->E);
                $aux3 = ($request->I / $request->K);
                $temp = (($aux - round($aux2,2)) /  round($aux3,3)) * 1;
                $res = number_format($temp,3);
                 
                $model = LoteDetalleDboIno::find($request->idDetalle);
                $model->Oxigeno_inicial = $request->A;
                $model->Oxigeno_final = $request->B;
                $model->Vol_muestra = $request->C; 
                $model->Oxigeno_disueltoini = $request->D;
                $model->Oxigeno_disueltofin = $request->E;
                $model->Vol_total_frasco = $request->G;
                $model->Vol_inoculo = $request->H;
                $model->Vol_muestra_siembra = $request->I;
                $model->Porcentaje_dilucion = $request->J;  
                $model->Vol_winker = $request->K;
                $model->Botella_od = $request->L;
                $model->Botella_fin = $request->M;
                $model->Ph_inicial = $request->N;
                $model->Ph_final = $request->O;
                $model->Resultado = $res;
                $model->Analizo = Auth::user()->id;
                $model->Sugerido = $request->S;
                $model->save();
                $tipo = 1;
                break;
            case 16: //todo Flotación de huevos de helminto
                # code...
                $suma = $request->lum1 + $request->na1 + $request->sp1 + $request->tri1 + $request->uni1;
                $aux = $suma / $request->volH1;
                $res = ceil($aux);


                $model = LoteDetalleHH::find($request->idDetalle);
                $model->A_alumbricoides = $request->lum1;
                $model->H_nana = $request->na1;
                $model->Taenia_sp = $request->sp1;
                $model->T_trichiura = $request->tri1;
                $model->Uncinarias = $request->uni1;
                $model->Vol_muestra = $request->volH1;
                $model->Resultado = $res;
                $model->Analizo = Auth::user()->id;
                $model->save();

                break;
            default:
                # code...
                break;
        }
        $data = array(
            'aux' =>$res,
            'res1' => $aux,
            'res' => $res,
            'tipo' => $tipo,
            'model' => $numModel3,
            'metodoCorto' => $metodoCorto,
        );

        return response()->json($data);
    }
    public function updateObsMuestraEcoli(Request $request)
    {
        $model = LoteDetalleEcoli::find($request->idMuestra);
        $model->Observacion = $request->observacion;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function updateObsMuestra(Request $request)
    {

        switch ($request->caso) {
            case 1:
                # Coliformes
                $model = LoteDetalleColiformes::find($request->idMuestra);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            case 2:
                #DBO
                $model = LoteDetalleDbo::find($request->idMuestra);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            case 3:
                #HH
                $model = LoteDetalleHH::find($request->idMuestra);
                $model->Observacion = $request->observacion;
                $model->save();
                break;
            default:
                # code...
                break;
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }


    // todo ************************************************
    // todo Fin de anlisis
    // todo ************************************************

    // todo ************************************************
    // todo Inicio de Lote
    // todo ************************************************
    public function lote()
    {
        //* Tipo de formulas 
        $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();

        $textoRecuperadoPredeterminado = ReportesMb::where('Id_lote', 0)->first();
        return view('laboratorio.mb.lote', compact('parametro', 'textoRecuperadoPredeterminado'));
    }
    public function getPendientes(Request $res)
    {
        $model = array();
        $temp = array();
        $codigo = DB::table('ViewCodigoParametro')->where('Asignado',0)->get();
        $param = DB::table('ViewParametroUsuarios')->where('Id_user',Auth::user()->id)->get();
        
        foreach ($codigo as $item) {
            $temp = array();
            foreach ($param as $item2) {
                if ($item->Id_parametro == $item2->Id_parametro) {
                    array_push($temp,$item->Codigo);
                    array_push($temp,"(".$item->Id_parametro.") ".$item->Parametro);
                    array_push($temp,$item->Hora_recepcion);
                    array_push($model,$temp);
                    break;
                }
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function createLote(Request $request)
    {
        $model = LoteAnalisis::create([
            'Id_area' => 6,
            'Id_tecnica' => $request->tipo,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $request->fecha,
        ]);
        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function buscarLote(Request $request)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->tipo)->where('Id_area', 6)->where('Fecha', $request->fecha)->get();
        if ($model->count()) {
            $sw = true;
        }

        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }

    //RECUPERAR DATOS PARA ENVIARLOS A LA VENTANA MODAL > EQUIPO PARA RELLENAR LOS DATOS ALMACENADOS EN LA BD
    public function getDatalote(Request $request)
    {
        $idLoteIf = $request->idLote;

        //RECUPERA LA PLANTILLA DEL REPORTE
        $lote = LoteAnalisis::where('Id_lote', $request->idLote)->first();


        //RECUPERA EL APARTADO DE FÓRMULAS GLOBALES;
        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        if ($constantesModel->count()) {
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();
        } else {
            $constantes = null;
        }

        /*Módulo de Grasas*/
        $dataGrasas = array();

        $calentamientoFq = DB::table('calentamiento_matraces')->where('Id_lote', $request->idLote)->get();
        $enfriadoFq = DB::table('enfriado_matraces')->where('Id_lote', $request->idLote)->get();
        $secadoFq = DB::table('secado_cartuchos')->where('Id_lote', $request->idLote)->get();
        $tiempoFq = DB::table('tiempo_reflujo')->where('Id_lote', $request->idLote)->get();
        $enfriadoMatrazFq = DB::table('enfriado_matraz')->where('Id_lote', $request->idLote)->get();

        if ($calentamientoFq->count()) {
            array_push($dataGrasas, $calentamientoFq);
        } else {
            array_push($dataGrasas, null);
        }

        if ($enfriadoFq->count()) {
            array_push($dataGrasas, $enfriadoFq);
        } else {
            array_push($dataGrasas, null);
        }

        if ($secadoFq->count()) {
            $secadoCartucho = SecadoCartucho::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $secadoCartucho);
        } else {
            array_push($dataGrasas, null);
        }

        if ($tiempoFq->count()) {
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $tiempoReflujo);
        } else {
            array_push($dataGrasas, null);
        }

        if ($enfriadoMatrazFq->count()) {
            $enfriadoMatraz = EnfriadoMatraz::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $enfriadoMatraz);
        } else {
            array_push($dataGrasas, null);
        }


        /* Módulo de coliformes */
        $dataColi = array();

        $sembradoFq = DB::table('sembrado_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaPresuntivaFq = DB::table('prueba_presuntiva_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaConfirmativaFq = DB::table('prueba_confirmativa_fq')->where('Id_lote', $request->idLote)->get();

        if ($sembradoFq->count() && $pruebaPresuntivaFq->count() && $pruebaConfirmativaFq->count()) {
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first(); //Array 0
            $pruebaPresunFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first(); //Array 1
            $pruebaConfirFq = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first(); //Array 2

            array_push(
                $dataColi,
                $sembradoFq,
                $pruebaPresunFq,
                $pruebaConfirFq
            );
        } else {
            array_push(
                $dataColi,
                null,
                null,
                null
            );
        }
        //-------------------------------------

        /* Módulo DQO */
        $dqoModel = DqoDetalle::where('Id_lote', $request->idLote)->get();
        if ($dqoModel->count()) {
            $dqo = DqoDetalle::where('Id_lote', $request->idLote)->first();
        } else {
            $dqo = "";
        }
        $plantilla = Bitacoras::where('Id_lote', $request->idLote)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
        }


        //-------------------------------------
        $data = array(
            'curvaConstantes' => $constantes,
            'idLoteIf' => $idLoteIf,
            'dataGrasas' => $dataGrasas,
            'dataColi' => $dataColi,
            'dataDqo' => $dqo,
            'plantilla' => $plantilla,
        );

        return response()->json($data);
    }

    public function getPlantillaPred(Request $request)
    {
        $bandera = '';

        //Obtiene el parámetro que se está consultando
        $parametro = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->first();

        if (is_null($parametro)) {
            $parametro = DB::table('ViewLoteDetalleHH')->where('Id_lote', $request->idLote)->first();

            if (is_null($parametro)) {
                $parametro = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $request->idLote)->first();

                if (!is_null($parametro)) {
                    $bandera = "dbo";
                }
            } else {
                $bandera = 'hh';
            }
        } else {
            $bandera = 'coli';
        }

        if ($bandera == 'coli') {
            if ($parametro->Id_parametro == 13 || $parametro->Id_parametro == 51 || $parametro->Id_parametro == 141 || $parametro->Id_parametro == 143 || $parametro->Id_parametro == 145 || $parametro->Id_parametro == 164 || $parametro->Id_parametro == 279 || $parametro->Id_parametro == 280 || $parametro->Id_parametro == 281) { //COLIFORMES FECALES
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 1)->first();
            } else if ($parametro->Id_parametro == 35 || $parametro->Id_parametro == 52 || $parametro->Id_parametro == 142 || $parametro->Id_parametro == 144 || $parametro->Id_parametro == 146 || $parametro->Id_parametro == 147) { // COLIFORMES TOTALES
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 5)->first();
            }
        } else if ($bandera == 'hh') {
            if ($parametro->Id_parametro == 17 || $parametro->Id_parametro == 82 || $parametro->Id_parametro == 173 || $parametro->Id_parametro == 282 || $parametro->Id_parametro == 283) { // HH
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 0)->first();
            }
        } else if ($bandera == 'dbo') {
            if ($parametro->Id_parametro == 5 || $parametro->Id_parametro == 72) { // DBO5    
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 3)->first();
            } else if ($parametro->Id_parametro == 71) { //DBO5 CON INOCULO
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 2)->first();
            }
        } else if ($bandera == 'oxigeno') {
            if ($parametro->Id_parametro == 40 || $parametro->Id_parametro == 41) { // OXIGENO DISUELTO
                $plantillaPredeterminada = Bitacoras::where('Id_reporte', 4)->first();
            }
        } else {
            $plantillaPredeterminada = Bitacoras::where('Id_reporte', 0)->first();
        }

        return response()->json($plantillaPredeterminada);
    }

    public function asignar()
    {
        $tipoFormula = TipoFormula::all();
        return view('laboratorio.metales.asignar', compact('tipoFormula'));
    }
    public function asgnarMuestraLote($id)
    {
        $lote = LoteDetalle::where('Id_lote', $id)->get();
        $idLote = $id;
        return view('laboratorio.mb.asignarMuestraLote', compact('lote', 'idLote'));
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
        );
        return response()->json($data);
    }
    //* Muestra asigada a lote
    public function getMuestraAsignada(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $model = array();
        switch ($loteModel->Id_tecnica) {
            case 132: //todo Coliformes +
            case 133:
            case 135:
            case 12:
            case 134: //coliformes alimentos
            case 35:  //Ecoli de MicroBiologia
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->where('Id_control', 1)->get();
                break;
            
            case 253: //todo  ENTEROCOCO FECAL
                # code...
                $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $request->idLote)->where('Id_control', 1)->get();
                break;
            case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                # code...
                $model = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $request->idLote)->get();
                break;
            case 16: //todo Huevos de Helminto 
                # code...
                $model = DB::table('ViewLoteDetalleHH')->where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra
    public function delMuestraLote(Request $request)
    {
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 17: //todo Espectrofotometria
                $detModel = DB::table('lote_detalle_espectro')->where('Id_detalle', $request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote', $request->idLote)->get();
                break;
            case 18: //todo Gravimetia
                # code...
                break;
            case 19: //todo Volumetria
                # code...
                break;
            default:
                # code...
                break;
        }


        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();


        $solModel = SolicitudParametro::where('Id_solicitud', $request->idSol)->where('Id_subnorma', $request->idParametro)->first();
        $solModel->Asignado = 0;
        $solModel->save();
        $solModel = SolicitudParametro::find($request->idSol);

        $data = array(
            'idDetalle' => $request->idDetalle,
        );

        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        $paraModel = Parametro::find($loteModel->Id_tecnica);

        switch ($paraModel->Id_parametro) {
            case 135:
            case 132:
            case 133:
            case 12: //todo Coliformes Fecales
            case 134:
                case 35: //todo  E COLI Mb
                $model = LoteDetalleColiformes::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleEspectro::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 51: //todo Coliformes totales
                $model = LoteDetalleColiformes::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleEspectro::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 253: //todo  ENTEROCOCO FECAL
                # code...
                $model = LoteDetalleEnterococos::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleEnterococos::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                # code...
                $model = LoteDetalleDbo::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleDbo::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 16: //todo Huevos de Helminto 
                # code...
                $model = LoteDetalleHH::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleHH::where('Id_lote', $request->idLote)->get();
                $sw = true;
                break;
            case 78:
                $model = LoteDetalleEcoli::create([
                    'Id_lote' => $request->idLote,
                    'Id_analisis' => $request->idAnalisis,
                    'Id_codigo' => $request->idSol,
                    'Id_parametro' => $loteModel->Id_tecnica,
                    'Id_control' => 1,
                ]);
                $detModel = LoteDetalleEcoli::where('Id_lote', $request->idLote)->get();
                break;
            default:
                # code...
                $sw  = false;
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
            'sw' => true,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function liberarMuestra(Request $request)
    {
        $sw = false;
        $mensaje = "";
        $paraModel = LoteAnalisis::find($request->idLote);
        switch ($paraModel->Id_tecnica) {
            case 12:
            case 132:
            case 133:
            case 134:
            case 135:
            case 137:   
            case 35:     //todo Ecoli Enterococos 
                     //todo Número más probable (NMP), en tubos múltiples
                $model = LoteDetalleColiformes::find($request->idMuestra);
                $model->Liberado = 1;
                $model->Analizo = Auth::user()->id;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Analizo = $model->Analizo;
                $modelCod->save();

                $model = LoteDetalleColiformes::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Liberado = $model->count();
                $loteModel->save();
                break;
            case 78: // Ecoli
                $model = LoteDetalleEcoli::find($request->idMuestra);
                $model->Liberado = 1;
                $model->Analizo = Auth::user()->id;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Analizo = $model->Analizo;
                $modelCod->save();

                $model = LoteDetalleEcoli::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Liberado = $model->count();
                $loteModel->save();
                break;
          
            case 253:
                $model = LoteDetalleEnterococos::find($request->idMuestra);
                $model->Liberado = 1;
                $model->Analizo = Auth::user()->id;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Analizo = $model->Analizo;
                $modelCod->save();

                $model = LoteDetalleEnterococos::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Liberado = $model->count();
                $loteModel->save();
                break;
            case 51: //todo Número más probable (NMP), en tubos múltiples //coliformes totales

                $sw = true;
                break;
            case 262: //todo  ENTEROCOCO FECAL
                # code...

                $sw = true;
                break;
            case 5: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                # code...
                $mensaje = "entro a dbo";
                $model = LoteDetalleDbo::find($request->idMuestra);
                $model->Liberado = 1;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Analizo = $model->Analizo;
                $modelCod->save();

                $model = LoteDetalleDbo::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Liberado = $model->count();
                $loteModel->Analizo = Auth::user()->id;
                $loteModel->save();

                break;
            case 16: //todo Huevos de Helminto 
                $model = LoteDetalleHH::find($request->idMuestra);
                $model->Liberado = 1;
                $model->Analizo = Auth::user()->id;
                if ($model->Resultado != null) {
                    $sw = true;
                    $model->save();
                }
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado = $model->Resultado;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();

                $model = LoteDetalleHH::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
                $loteModel = LoteAnalisis::find($request->idLote);
                $loteModel->Liberado = $model->count();
                $loteModel->save();

                break;
            default:
                # code...
                $sw  = false;
                break;
        }


        $data = array(
            'sw' => $sw,
            'mensaje' => $mensaje,
            'codigo_parametro' => $modelCod
        );
        return response()->json($data);
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request)
    {
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        //$lote = Reportes::where('Id_lote', $idLote)->first();

        $lote = DB::table('reportes_mb')->where('Id_lote', $idLote)->where('Id_area', $request->idArea)->get();

        if ($lote->count()) {
            $texto = ReportesMb::where('Id_lote', $idLote)->where('Id_area', $request->idArea)->first();
            $texto->Texto = $textoPeticion;
            $texto->Id_user_m = Auth::user()->id;

            $texto->save();
        } else {
            $texto = ReportesMb::create([
                'Id_lote' => $idLote,
                'Texto' => $textoPeticion,
                'Id_area' => $request->idArea,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        return response()->json(
            compact('texto')
        );
    }

    public function setDetalleLote(Request $res)
    {
        if ($res->idParametro == 12 || $res->idParametro == 35 || $res->idParametro == 51 || $res->idParametro == 52 || $res->idParametro == 141 || $res->idParametro == 137) { //Coliformes
            $model = BitacoraColiformes::where('Id_lote', $res->idLote)->get();
            if ($model->count()) {
                $model = BitacoraColiformes::where('Id_lote',$res->idLote)->first();
                $model->Sembrado = $res->sembrado;
                $model->Fecha_resiembra = $res->fechaResiembra;
                $model->Num_tubo =  $res->numTubo;
                $model->Bitacora = $res->bitacora;
                $model->Preparacion_pre = $res->preparacion;
                $model->Lectura_pre = $res->lectura;
                $model->Medio_con = $res->medio;
                $model->Preparacion_con = $res->preparacionCon;
                $model->Lectura_con = $res->lecturaCon;
                $model->save();
            } else {
                $model = BitacoraColiformes::create([
                    'Id_lote' => $res->idLote,
                    'Sembrado' => $res->sembrado,
                    'Fecha_resiembra' => $res->fechaResiembra,
                    'Num_tubo' => $res->numTubo,
                    'Bitacora' => $res->bitacora,
                    'Preparacion_pre' => $res->preparacion,
                    'Lectura_pre' => $res->lectura,
                    'Medio_con' => $res->medio,
                    'Preparacion_con' => $res->preparacionCon,
                    'Lectura_con' => $res->lecturaCon,
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id,
                ]);
            }
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPlantillaDetalleMb(Request $res)
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
            $model = BitacoraMb::create([  
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

    //*************************************GUARDA LOS DATOS DE LA VENTANA MODAL EN MÓDULO LOTE, PESTAÑA EQUIPO************* */
    public function guardarDatos(Request $request)
    {

        //****************************************************GRASAS*****************************************************************        
        //calentamiento_matraces
        $calentamientoFqModel = CalentamientoMatraz::where('Id_lote', $request->idLote)->get();

        if ($calentamientoFqModel->count()) {
            for ($i = 0; $i < 3; $i++) {
                $calentamientoMatraz = CalentamientoMatraz::find($calentamientoFqModel[$i]->Id_calentamiento);

                $calentamientoMatraz->Id_lote = $request->grasas_calentamiento[$i][0];
                $calentamientoMatraz->Masa_constante = $request->grasas_calentamiento[$i][1];
                $calentamientoMatraz->Temperatura = $request->grasas_calentamiento[$i][2];
                $calentamientoMatraz->Entrada = $request->grasas_calentamiento[$i][3];
                $calentamientoMatraz->Salida = $request->grasas_calentamiento[$i][4];
                $calentamientoMatraz->Id_user_m = Auth::user()->id;

                $calentamientoMatraz->save();
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                CalentamientoMatraz::create([
                    'Id_lote' => $request->grasas_calentamiento[$i][0],
                    'Masa_constante' => $request->grasas_calentamiento[$i][1],
                    'Temperatura' => $request->grasas_calentamiento[$i][2],
                    'Entrada' => $request->grasas_calentamiento[$i][3],
                    'Salida' => $request->grasas_calentamiento[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);
            }
        }

        //enfriado_matraces
        $enfriadoFqModel = EnfriadoMatraces::where('Id_lote', $request->idLote)->get();

        if ($enfriadoFqModel->count()) {
            for ($i = 0; $i < 3; $i++) {
                $enfriadoMatraz = EnfriadoMatraces::find($enfriadoFqModel[$i]->Id_enfriado);
                $enfriadoMatraz->Id_lote = $request->grasas_enfriado[$i][0];
                $enfriadoMatraz->Masa_constante = $request->grasas_enfriado[$i][1];
                $enfriadoMatraz->Entrada = $request->grasas_enfriado[$i][2];
                $enfriadoMatraz->Salida = $request->grasas_enfriado[$i][3];
                $enfriadoMatraz->Pesado_matraz = $request->grasas_enfriado[$i][4];
                $enfriadoMatraz->Id_user_m = Auth::user()->id;

                $enfriadoMatraz->save();
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                EnfriadoMatraces::create([
                    'Id_lote' => $request->grasas_enfriado[$i][0],
                    'Masa_constante' => $request->grasas_enfriado[$i][1],
                    'Entrada' => $request->grasas_enfriado[$i][2],
                    'Salida' => $request->grasas_enfriado[$i][3],
                    'Pesado_matraz' => $request->grasas_enfriado[$i][4],
                    'Id_user_c' => Auth::user()->id,
                    'Id_user_m' => Auth::user()->id
                ]);
            }
        }

        //secado_cartuchos
        $secadoFqModel = SecadoCartucho::where('Id_lote', $request->idLote)->get();

        if ($secadoFqModel->count()) {
            $secadoCartucho = SecadoCartucho::find($secadoFqModel[0]->Id_secado);
            $secadoCartucho->Id_lote = $request->grasas_secadoLote;
            $secadoCartucho->Temperatura = $request->grasas_secadoTemp;
            $secadoCartucho->Entrada = $request->grasas_secadoEntrada;
            $secadoCartucho->Salida = $request->grasas_secadoSalida;
            $secadoCartucho->Id_user_m = Auth::user()->id;

            $secadoCartucho->save();
        } else {
            SecadoCartucho::create([
                'Id_lote' => $request->grasas_secadoLote,
                'Temperatura' => $request->grasas_secadoTemp,
                'Entrada' => $request->grasas_secadoEntrada,
                'Salida' => $request->grasas_secadoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        //tiempo_reflujo
        $tiempoFqModel = TiempoReflujo::where('Id_lote', $request->idLote)->get();

        if ($tiempoFqModel->count()) {
            $tiempoReflujo = TiempoReflujo::find($tiempoFqModel[0]->Id_tiempo);
            $tiempoReflujo->Id_lote = $request->grasas_tiempoLote;
            $tiempoReflujo->Entrada = $request->grasas_tiempoEntrada;
            $tiempoReflujo->Salida = $request->grasas_tiempoSalida;
            $tiempoReflujo->Id_user_m = Auth::user()->id;

            $tiempoReflujo->save();
        } else {
            TiempoReflujo::create([
                'Id_lote' => $request->grasas_tiempoLote,
                'Entrada' => $request->grasas_tiempoEntrada,
                'Salida' => $request->grasas_tiempoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }

        //enfriado_matraz
        $enfriadoFqModel = EnfriadoMatraz::where('Id_lote', $request->idLote)->get();

        if ($enfriadoFqModel->count()) {
            $enfriadoMatraz2 = EnfriadoMatraz::find($enfriadoFqModel[0]->Id_enfriado);
            $enfriadoMatraz2->Id_lote = $request->grasas_enfriadoLote;
            $enfriadoMatraz2->Entrada = $request->grasas_enfriadoEntrada;
            $enfriadoMatraz2->Salida = $request->grasas_enfriadoSalida;
            $enfriadoMatraz2->Id_user_m = Auth::user()->id;

            $enfriadoMatraz2->save();
        } else {
            EnfriadoMatraz::create([
                'Id_lote' => $request->grasas_enfriadoLote,
                'Entrada' => $request->grasas_enfriadoEntrada,
                'Salida' => $request->grasas_enfriadoSalida,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }


        //****************************************************COLIFORMES*************************************************************
        //******************************************************SEMBRADO FQ**********************************************************
        $sembradoFqModel = SembradoFq::where('Id_lote', $request->idLote)->get();

        if ($sembradoFqModel->count()) {
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first();

            $sembradoFq->Sembrado = $request->sembrado_sembrado;
            $sembradoFq->Fecha_resiembra = $request->sembrado_fechaResiembra;
            $sembradoFq->Tubo_n = $request->sembrado_tuboN;
            $sembradoFq->Bitacora = $request->sembrado_bitacora;
            $sembradoFq->Id_user_m = Auth::user()->id;

            $sembradoFq->save();
        } else {
            SembradoFq::create([
                'Id_lote' => $request->idLote,
                'Sembrado' => $request->sembrado_sembrado,
                'Fecha_resiembra' => $request->sembrado_fechaResiembra,
                'Tubo_n' => $request->sembrado_tuboN,
                'Bitacora' => $request->sembrado_bitacora,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id
            ]);
        }
        //*******************************************PRUEBA PRESUNTIVA FQ******************************************/
        $pruebaPresuntivaModel = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->get();

        if ($pruebaPresuntivaModel->count()) {
            $pruebaPresuntivaFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first();

            $pruebaPresuntivaFq->Preparacion = $request->pruebaPresuntiva_preparacion;
            $pruebaPresuntivaFq->Lectura = $request->pruebaPresuntiva_lectura;
            $pruebaPresuntivaFq->Id_user_m = Auth::user()->id;

            $pruebaPresuntivaFq->save();
        } else {
            PruebaPresuntivaFq::create([
                'Id_lote' => $request->idLote,
                'Preparacion' => $request->pruebaPresuntiva_preparacion,
                'Lectura' => $request->pruebaPresuntiva_lectura,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //***********************************************PRUEBA CONFIRMATIVA FQ***********************************
        $pruebaConfirmativaModel = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->get();

        if ($pruebaConfirmativaModel->count()) {
            $pruebaConfirmativa = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first();

            $pruebaConfirmativa->Medio = $request->pruebaConfirmativa_medio;
            $pruebaConfirmativa->Preparacion = $request->pruebaConfirmativa_preparacion;
            $pruebaConfirmativa->Lectura = $request->pruebaConfirmativa_lectura;
            $pruebaConfirmativa->Id_user_m = Auth::user()->id;

            $pruebaConfirmativa->save();
        } else {
            PruebaConfirmativaFq::create([
                'Id_lote' => $request->idLote,
                'Medio' => $request->pruebaConfirmativa_medio,
                'Preparacion' => $request->pruebaConfirmativa_preparacion,
                'Lectura' => $request->pruebaConfirmativa_lectura,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //************************************DQO********************************
        $dqoModel = DqoFq::where('Id_lote', $request->idLote)->get();

        if ($dqoModel->count()) {
            $dqoFq = DqoFq::where('Id_lote', $request->idLote)->first();

            $dqoFq->Inicio = $request->ebullicion_inicio;
            $dqoFq->Fin = $request->ebullicion_fin;
            $dqoFq->Invlab = $request->ebullicion_invlab;
            $dqoFq->Id_user_m = Auth::user()->id;

            $dqoFq->save();
        } else {
            DqoFq::create([
                'Id_lote' => $request->idLote,
                'Inicio' => $request->ebullicion_inicio,
                'Fin' => $request->ebullicion_fin,
                'Invlab' => $request->ebullicion_invlab,
                'Id_user_c' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
            ]);
        }

        //*******************************************************************************************************
        return response()->json(
            compact('sembradoFqModel', 'pruebaPresuntivaModel', 'pruebaConfirmativaModel', 'dqoModel')
        );
    }
    public function guardarDqo(Request $res)
    {

        $model = DqoDetalle::where('Id_lote', $res->idLote)->get();
        if ($model->count()) {
            $model = DqoDetalle::where('Id_lote', $res->idLote)->first();
            $model->Cant_dilucion = $res->cantDilucion;
            $model->De = $res->de;
            $model->A = $res->a;
            $model->Pag = $res->pag;
            $model->N = $res->n;
            $model->Dilucion = $res->dilucion;
            $model->Estandares_bit = $res->estandaresbit;
            $model->save();
        } else {
            $model = DqoDetalle::create([
                'Id_lote' => $res->idLote,
                'Cant_dilucion' => $res->cantDilucion,
                'De' => $res->de,
                'A' => $res->a,
                'Pag' => $res->pag,
                'N' => $res->n,
                'Dilucion' => $res->dilucion,
                'Estandares_bit' => $res->estandaresbit,
            ]);
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    // todo ************************************************
    // todo Fin de Lote
    // todo ************************************************

    public function exportPdfCapturaMb($idLote)
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
        switch ($lote->Id_tecnica) {
            case 35: // Escherichia Coli
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 40,
                    'margin_bottom' => 50,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;
                $loteDetalleControles = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $idLote)->where('Id_control', '!=', 1 )->get();
                $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $idLote)->get();
                $plantilla = Bitacoras::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $idLote)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();


                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'plantilla' => $plantilla, 
                    'loteDetalleControles' => $loteDetalleControles,
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );

                $htmlFooter = view('exports.laboratorio.mb.ecoli.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.mb.ecoli.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.ecoli.bitacoraBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 253: // Enterococos
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 40,
                    'margin_bottom' => 50,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;
               
                $loteDetalle = DB::table('ViewLoteDetalleEnterococos')->where('Id_lote', $idLote)->get();
                $plantilla = Bitacoras::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleEnterococos::where('Liberado', 0)->where('Id_lote', $idLote)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    @$analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();


                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'plantilla' => $plantilla, 
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );

                $htmlFooter = view('exports.laboratorio.mb.enterococos.bitacoraFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.mb.enterococos.bitacoraHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.enterococos.bitacoraBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;

            case 134:
            case 132: //Coliformes fecales
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 31,
                    'margin_bottom' => 45,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;


                $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $idLote)->get();
                $bitacora = PlantillaBitacora::where('Id_parametro', 134)->first();

                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'bitacora' => $bitacora,
                );

                $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody', $data);
                $htmlFooter = view('exports.laboratorio.mb.127.coliformes.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 12:
                return redirect('/admin/laboratorio/micro/captura/exportPdfCaptura/' . $idLote);
                break;
            case 135:
            case 12:
            case 137:
            case 133: //Coliformes totales
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 31,
                    'margin_bottom' => 45,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;

                $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $idLote)->get();
                $bitacora = PlantillaBitacora::where('Id_parametro', 135)->first();

                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'bitacora' => $bitacora,
                );

                $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader2', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody2', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 135:
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 31,
                    'margin_bottom' => 45,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;

                $loteDetalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $idLote)->get();
                $bitacora = PlantillaBitacora::where('Id_parametro', 134)->first();

                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'bitacora' => $bitacora,
                );

                $htmlHeader = view('exports.laboratorio.mb.127.coliformes.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.127.coliformes.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 78: // E.Coli Potable
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 31,
                    'margin_bottom' => 45,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;

                $loteDetalle = DB::table('ViewLoteDetalleEcoli')->where('Id_lote', $idLote)->get();
                $convinaciones = ConvinacionesEcoli::where('Id_lote', $idLote)->get();
                $bitacora = PlantillaBitacora::where('Id_parametro', 78)->first();            

                $data = array(
                    'lote' => $lote,
                    'loteDetalle' => $loteDetalle,
                    'convinaciones' => $convinaciones,
                    'bitacora' => $bitacora,
                );

                $htmlHeader = view('exports.laboratorio.mb.127.ecoli.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.127.ecoli.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 5:
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => "L",
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 40,
                    'margin_bottom' => 50,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/HojaMembretadaHorizontal.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;

                $loteDetalle = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $idLote)->get();
                $plantilla = Bitacoras::where('Id_lote', $idLote)->get();
                if ($plantilla->count()) {
                } else {
                    $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
                }
                $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
                //Comprobación de bitacora analizada
                $comprobacion = LoteDetalleDbo::where('Liberado', 0)->where('Id_lote', $idLote)->get();
                if ($comprobacion->count()) {
                    $analizo = "";
                } else {
                    $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                }
                $reviso = User::where('id', 17)->first();
                $detalleLote = DqoDetalle::where('Id_lote',$idLote)->first();

                $data = array(
                    'lote' => $lote,
                    'detalleLote' => $detalleLote,
                    'procedimiento' => $procedimiento,
                    'loteDetalle' => $loteDetalle,
                    'plantilla' => $plantilla, 
                    'analizo' => $analizo,
                    'reviso' => $reviso,
                    'comprobacion' => $comprobacion,
                );

                $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                break;
            case 16:
                $mpdf = new \Mpdf\Mpdf([
                    'orientation' => 'P',
                    'format' => 'letter',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 35,
                    'margin_bottom' => 45,
                    'defaultheaderfontstyle' => ['normal'],
                    'defaultheaderline' => '0'
                ]);
                $mpdf->SetWatermarkImage(
                    asset('/public/storage/MembreteVertical.png'),
                    1,
                    array(215, 280),
                    array(0, 0),
                );
                $mpdf->showWatermarkImage = true;
                    $loteDetalle = DB::table('ViewLoteDetalleHH')->where('Id_lote', $idLote)->get();
                    $plantilla= Bitacoras::where('Id_lote',$idLote)->get();  
                    if ($plantilla->count()) {
                    }else{
                        $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get(); 
                    }
                    $procedimiento = explode("NUEVASECCION",$plantilla[0]->Texto);
                    $comprobacion = LoteDetalleEspectro::where('Liberado', 0)->where('Id_lote', $idLote)->get(); 
                    if ($comprobacion->count()) {
                        $analizo = "";
                    } else {
                        $analizo = User::where('id', $loteDetalle[0]->Analizo)->first();
                    }
                    $reviso = User::where('id', 17)->first();
                    $data = array(
                        'lote' => $lote,  
                        'loteDetalle' => $loteDetalle,
                        'plantilla' => $plantilla,  
                        'procedimiento' => $procedimiento, 
                        'comprobacion' => $comprobacion,
                        'analizo' => $analizo,
                        'reviso' => $reviso, 
                    );
                    $htmlFooter = view('exports.laboratorio.mb.hh.capturaFooter', $data);
                    $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                    $htmlHeader = view('exports.laboratorio.mb.hh.capturaHeader', $data); 
                    $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                    $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody',$data);
                    $mpdf->WriteHTML($htmlCaptura);
                    $mpdf->CSSselectMedia = 'mpdf';
                break;
            default:
                # code...
                break;
        }



        $mpdf->Output();
    }
    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
    public function exportPdfCaptura($idLote)
{

    $temp = LoteAnalisis::where('Id_lote', $idLote)->first();
    switch ($temp->Id_tecnica) {
        case 16:
        case 5:
            return redirect()->to('admin/laboratorio/micro/captura/exportPdfCapturaMb/' . $idLote);
            break;
        default:
            # code...
            break;
    }
        $bandera = '';
        $horizontal = false;
        $sw = true;
        $id_lote = $idLote;
        $semaforo = true;

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
        if (!is_null($fechaAnalisis)) {
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        } else {
            $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
        }

        //Recupera el parámetro que se está utilizando****************************
        $limiteC = null;
        $parametro = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->first();

        if (is_null($parametro)) {
            $parametro = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->first();

            if (!is_null($parametro)) {
                $bandera = 'hh';
                $limiteC = DB::table('parametros')->where('Id_parametro', $parametro->Id_parametro)->first();
            } else {
                $parametro = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->first();
                if (!is_null($parametro)) {
                    $bandera = 'dbo';
                    $limiteC = DB::table('parametros')->where('Id_parametro', $parametro->Id_parametro)->first();
                }
            }
        } else {
            $bandera = 'coli';
            $limiteC = DB::table('parametros')->where('Id_parametro', $parametro->Id_parametro)->first();
        }
        //************************************************************************        


        //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textProcedimiento = Bitacoras::where('Id_lote', $id_lote)->first();
        $proced = false;
    
        if (!is_null($textProcedimiento)) {
            $proced = true;
            if ($bandera == 'coli') { 
                if ($parametro->Id_parametro == 12 || $parametro->Id_parametro == 137) { //Coliformes Fecales
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();

                        $sembrado = SembradoFq::where('Id_lote', $id_lote)->first();
                        if (!is_null($sembrado)) {
                            $parametroDeterminar = $parametro->Parametro;
                            $simbologiaParam = DB::table('ViewParametros')->where('Id_parametro', $parametro->Id_parametro)->first();
                            $fechaConFormato = date("d/m/Y", strtotime($sembrado->Sembrado));
                            @$hora = date("H:i:s", strtotime($sembrado->Sembrado));
                        }

                        $pruebaPresun = PruebaPresuntivaFq::where('Id_lote', $id_lote)->first();
                        $pruebaConf = PruebaConfirmativaFq::where('Id_lote', $id_lote)->first();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'sembrado', 'pruebaPresun', 'pruebaConf', 'simbologiaParam'));
                    } else {
                        $sw = false;
                    }
                } else if ($parametro->Id_parametro == 35 || $parametro->Id_parametro == 52 || $parametro->Id_parametro == 142 || $parametro->Id_parametro == 144 || $parametro->Id_parametro == 146 || $parametro->Id_parametro == 147) {   // COLIFORMES TOTALES  
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();

                        $sembrado = SembradoFq::where('Id_lote', $id_lote)->first();
                        if (!is_null($sembrado)) {
                            $parametroDeterminar = $parametro->Parametro;
                            $simbologiaParam = DB::table('ViewParametros')->where('Id_parametro', $parametro->Id_parametro)->first();
                            $fechaConFormato = date("d/m/Y", strtotime($sembrado->Sembrado));
                            $hora = date("H:i:s", strtotime($sembrado->Sembrado));
                        }

                        $pruebaPresun = PruebaPresuntivaFq::where('Id_lote', $id_lote)->first();
                        $pruebaConf = PruebaConfirmativaFq::where('Id_lote', $id_lote)->first();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'sembrado', 'pruebaPresun', 'pruebaConf', 'simbologiaParam'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'hh') {
                if ($parametro->Id_parametro == 16) { // HH
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {

                        $controles = array();
                        $controlesCalidad = array();

                        foreach ($data as $item) {
                            array_push(
                                $controles,
                                $item->Id_control,
                            );
                        }

                        foreach ($controles as $item) {
                            array_push(
                                $controlesCalidad,
                                ControlCalidad::where('Id_control', $item)->first()
                            );
                        }

                        $dataLength = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->count();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'controlesCalidad'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'enteroCoco') {    //NO EXISTE BITÁCORA AÚN POR SER UN PARÁMETRO NUEVO
                if ($parametro->Parametro == 'ENTEROCOCO FECAL') { //POR REVISAR EN LA TABLA DE DATOS
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $dataLength = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->count();
                        $htmlCaptura = view('exports.laboratorio.mb.enteroC.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'dbo') {
                if ($parametro->Id_parametro == 5 || $parametro->Id_parametro == 62) { // DBO5
                    $horizontal = 'L';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
                    $detalleLote = DqoDetalle::where('Id_lote', $id_lote)->first();
                    $bitacora = ReportesMb::where('Id_reporte', 3)->first();
                    if (!is_null($data)) {
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();
                        $limites = array();
                        foreach ($data as $item) {
                            if ($item->Resultado < $limiteC->Limite) {
                                $limC = "< " . $limiteC->Limite;
                                array_push($limites, $limC);
                            } else {  //Si es mayor el resultado que el límite de cuantificación
                                $limC = number_format($item->Resultado, 2, ".", ",");

                                array_push($limites, $limC);
                            }
                        }

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', compact('detalleLote', 'bitacora', 'textoProcedimiento', 'data', 'dataLength', 'limites', 'limiteC'));
                    } else {
                        $sw = false;
                    }
                } else if ($parametro->Id_parametro == 70) { // DBO5 CON INOCULO
                    $horizontal = 'L';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.dboIn.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                    } else {
                        $sw = false;
                    }
                }
            }
        } else {  //----------------------
            if ($bandera == 'coli') {
                if ($parametro->Id_parametro == 12 || $parametro->Id_parametro == 137) { // COLIFORMES FECALES
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $sembrado = SembradoFq::where('Id_lote', $id_lote)->first();
                        if (!is_null($sembrado)) {
                            $parametroDeterminar = $parametro->Parametro;
                            $simbologiaParam = DB::table('ViewParametros')->where('Id_parametro', $parametro->Id_parametro)->first();
                            $fechaConFormato = date("d/m/Y", strtotime($sembrado->Sembrado));
                            $hora = date("H:i:s", strtotime($sembrado->Sembrado));
                        }

                        $pruebaPresun = PruebaPresuntivaFq::where('Id_lote', $id_lote)->first();
                        $pruebaConf = PruebaConfirmativaFq::where('Id_lote', $id_lote)->first();

                        $textProcedimiento = ReportesMb::where('Id_reporte', 1)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();
                        $bitacora = BitacoraColiformes::where('Id_lote', $id_lote)->first();
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'bitacora', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'sembrado', 'pruebaPresun', 'pruebaConf'));
                    } else {
                        $sw = false;
                    }
                } else if ($parametro->Id_parametro == 35 || $parametro->Id_parametro == 52 || $parametro->Id_parametro == 142 || $parametro->Id_parametro == 144 || $parametro->Id_parametro == 146 || $parametro->Id_parametro == 147) { // COLIFORMES TOTALES
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $sembrado = SembradoFq::where('Id_lote', $id_lote)->first();
                        if (!is_null($sembrado)) {
                            $parametroDeterminar = $parametro->Parametro;
                            $simbologiaParam = DB::table('ViewParametros')->where('Id_parametro', $parametro->Id_parametro)->first();
                            $fechaConFormato = date("d/m/Y", strtotime($sembrado->Sembrado));
                            $hora = date("H:i:s", strtotime($sembrado->Sembrado));
                        }

                        $pruebaPresun = PruebaPresuntivaFq::where('Id_lote', $id_lote)->first();
                        $pruebaConf = PruebaConfirmativaFq::where('Id_lote', $id_lote)->first();

                        $textProcedimiento = ReportesMb::where('Id_reporte', 5)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();

                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'resultadosPresuntivas', 'resultadosConfirmativas', 'sembrado', 'pruebaConf', 'pruebaPresun', 'simbologiaParam'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'hh') {
                if ($parametro->Id_parametro == 16) { // HH
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $textProcedimiento = ReportesMb::where('Id_reporte', 0)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $controles = array();
                        $controlesCalidad = array();

                        foreach ($data as $item) {
                            array_push(
                                $controles,
                                $item->Id_control,
                            );
                        }

                        foreach ($controles as $item) {
                            array_push(
                                $controlesCalidad,
                                ControlCalidad::where('Id_control', $item)->first()
                            );
                        }

                        $dataLength = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->count();
                        $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'controlesCalidad'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'enteroCoco') { //NO EXISTE AÚN BITÁCORA DEBIDO A QUE ES NUEVO PARÁMETRO
                if ($parametro->Parametro == 'ENTEROCOCO FECAL') { //POR REVISAR EN LA TABLA DE DATOS
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $textoProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                        $dataLength = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->count();
                        $htmlCaptura = view('exports.laboratorio.mb.enteroC.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                    } else {
                        $sw = false;
                    }
                }
            } else if ($bandera == 'dbo') {
                if ($parametro->Id_parametro == 5 || $parametro->Id_parametro == 62) { // DBO5
                    $horizontal = 'L';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
                    $bitacora = ReportesMb::where('Id_reporte', 3)->first();
                    if (!is_null($data)) {
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();
                        $detalleLote = DqoDetalle::where('Id_lote', $id_lote)->first();
                        $limites = array();
                        foreach ($data as $item) {
                            if ($item->Resultado < $limiteC->Limite) {
                                $limC = "< " . $limiteC->Limite;
                                array_push($limites, $limC);
                            } else {  //Si es mayor el resultado que el límite de cuantificación
                                $limC = number_format($item->Resultado, 2, ".", ",");

                                array_push($limites, $limC);
                            }
                        }

                        $textProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', compact('detalleLote', 'textoProcedimiento', 'bitacora', 'data', 'dataLength', 'limites'));
                    } else {
                        $sw = false;
                    }
                } else if ($parametro->Id_parametro == 70) { // DBO5 CON INOCULO
                    $horizontal = 'L';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();

                    if (!is_null($data)) {
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();
                        $textProcedimiento = ReportesMb::where('Id_reporte', 2)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.dboIn.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));
                    } else {
                        $sw = false;
                    }
                }
            }
        }

        //HEADER-FOOTER******************************************************************************************************************         

        if ($parametro->Id_parametro == 12 || $parametro->Id_parametro == 137) { // COLIFORMES FECALES
            $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
            $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();
            $plantilla = BitacoraMb::where('Id_lote', $id_lote)->get();
            if ($plantilla->count()) {
            } else {
                $plantilla = PlantillaMb::where('Id_parametro', $lote->Id_tecnica)->get();
            }
            //Comprobación de bitacora analizada
            $comprobacion = LoteDetalleColiformes::where('Liberado', 0)->where('Id_lote', $id_lote)->get();
            if ($comprobacion->count()) {
                $analizo = "";
            } else {
                $analizo = User::where('id', $model[0]->Analizo)->first();
            }
            $reviso = User::where('id', 17)->first();
            $htmlHeader = view('exports.laboratorio.mb.coliformes.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.coliformes.capturaFooter', compact('analizo','comprobacion','reviso','plantilla'));
        } else if ($parametro->Id_parametro == 35 || $parametro->Id_parametro == 52 || $parametro->Id_parametro == 142 || $parametro->Id_parametro == 144 || $parametro->Id_parametro == 146 || $parametro->Id_parametro == 147) { // COLIFORMES TOTALES
            $htmlHeader = view('exports.laboratorio.mb.espectro.coliformesTotales.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.espectro.coliformesTotales.capturaFooter', compact('usuario', 'firma'));
        } else if ($parametro->Id_parametro == 16) { // HH
            $htmlHeader = view('exports.laboratorio.mb.hh.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.hh.capturaFooter', compact('usuario', 'firma'));
        } else if ($parametro->Parametro == 'ENTEROCOCO FECAL') { //POR REVISAR EN LA TABLA DE DATOS
            $htmlHeader = view('exports.laboratorio.mb.espectro.condElec.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.espectro.condElec.capturaFooter', compact('usuario', 'firma'));
        } else if ($parametro->Id_parametro == 5 || $parametro->Id_parametro == 62) { // DBO5
            $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', compact('usuario', 'firma'));
        } else if ($parametro->Id_parametro == 70) { // DBO5 CON INOCULO
            $htmlHeader = view('exports.laboratorio.mb.dboIn.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.dboIn.capturaFooter', compact('usuario', 'firma'));
        }

        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $horizontal,
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 31,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        if ($horizontal == 'L') {
            //Establece la marca de agua del documento PDF
            $mpdf->SetWatermarkImage(
                asset('/public/storage/HojaMembretadaHorizontal.png'),
                1,
                array(215, 280),
                array(0, 0),
            );
        } else {
            //Establece la marca de agua del documento PDF
            $mpdf->SetWatermarkImage(
                asset('/public/storage/MembreteVertical.png'),
                1,
                array(215, 280),
                array(0, 0),
            );
        }

        $mpdf->showWatermarkImage = true;

        if ($sw === false) {
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }

        $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCaptura);
        $mpdf->CSSselectMedia = 'mpdf';

        //Hoja 2
        $hoja2 = false;
        if ($parametro->Id_parametro == 5 || $parametro->Id_parametro == 62) { // DBO5
            //$mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);
            //$horizontal = 'P';
            $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();

            if (!is_null($data)) {
                $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();

                if ($proced === true) {
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                } else {
                    $textProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                }

                $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', compact('usuario', 'firma'));
                $htmlCaptura1 = view('exports.laboratorio.mb.dbo.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                //$hoja2 = true;

            } else {
                $sw = false;
            }
        }

        /* if($hoja2 === true){
            $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlHeader, 'O', 'E');
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdf->WriteHTML($htmlCaptura1);
        } */

        $mpdf->Output();
    }
}
