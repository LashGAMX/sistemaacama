<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Config\ConfiguracionesController;
use App\Http\Controllers\Controller;
use App\Imports\AnalisisQ\IcpImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\AreaAnalisis;
use App\Models\BitacoraMetales;
use App\Models\Bitacoras;
use App\Models\Constante;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ObservacionMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\Parametro;
use App\Models\Reportes;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales;
use App\Models\BlancoCurvaMetales;
use App\Models\CodigoParametros;
use App\Models\ConfiguracionMetales;
use App\Models\ControlCalidad;
use App\Models\CurvaCalibracionMet;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use App\Models\LoteDetalleIcp;
use App\Models\MetalesDetalle;
use App\Models\Norma;
use App\Models\PlantillaBitacora;
use App\Models\PlantillaMetales;
use App\Models\Solicitud;
use App\Models\SolicitudPuntos;
use App\Models\Tecnica;
use App\Models\TempIcp;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Cast\Array_;

class MetalesController extends Controller
{
    //  
    // todo ************************************************
    // todo Inicio analisis
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

        return view('laboratorio.metales.analisis', compact('model', 'elements', 'solicitud', 'solicitudLength', 'tecnicas', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'parametrosLength', 'puntoMuestreo', 'puntoMuestreoLength'));
    }
    // todo ************************************************
    // todo Fin de Analisis
    // todo ************************************************


    // todo ************************************************
    // todo Inicio de Observacion
    // todo ************************************************
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')
            ->where('Id_tipo_formula',20)
            ->orWhere('Id_tipo_formula',21)
            ->orWhere('Id_tipo_formula',22)
            ->orWhere('Id_tipo_formula',23)
            ->orWhere('Id_tipo_formula',24)
            ->orWhere('Id_tipo_formula',58)
            ->orWhere('Id_tipo_formula',59)
            ->get();
        return view('laboratorio.metales.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $res)
    {
        $ids = array();
        $folios = array();
        $empresas = array();
        $recepciones = array();
        $recepciones2 = array();
        $aux = 0;
        $aux2 = 0;
        switch ($res->tipo) {
            case 1:
                $model = DB::table('ViewParametroProceso')
                ->where('Id_tipo_formula',20)
                ->orWhere('Id_tipo_formula',21)
                ->orWhere('Id_tipo_formula',22) 
                ->orWhere('Id_tipo_formula',23)
                ->orWhere('Id_tipo_formula',24)
                ->orWhere('Id_tipo_formula',58)
                ->orWhere('Id_tipo_formula',59)
                ->where('Asignado','!=',1)
                ->get();
                foreach ($model as $item) {
                    $aux = 0;
                    for ($i=0; $i < sizeof($ids); $i++) { 
                        if ($ids[$i] == $item->Id_solicitud) {
                            $aux = 1;
                        }
                    }
                    if ($aux == 0) {
                        $temp = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
                        $solTemp = Solicitud::where('Id_solicitud',$item->Id_solicitud)->first();
                        $solAux = Solicitud::where('Id_solicitud',$solTemp->Hijo)->first();
                        array_push($ids,$item->Id_solicitud);
                        array_push($folios,$solAux->Folio_servicio); 
                        array_push($empresas,$temp->Empresa);
                        array_push($recepciones,$temp->Hora_recepcion);
                        array_push($recepciones2,$temp->Hora_entrada);
                    }
                }
                break;
            case 2:
                $model = DB::table('ViewParametroProceso')
                ->where('Id_tipo_formula',$res->id)
                ->where('Asignado','!=',1)
                ->get();
                foreach ($model as $item) {
                    $aux = 0;
                    for ($i=0; $i < sizeof($ids); $i++) { 
                        if ($ids[$i] == $item->Id_solicitud) {
                            $aux = 1;
                        }
                    }
                    if ($aux == 0) {
                        $temp = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
                            $solTemp = Solicitud::where('Id_solicitud',$item->Id_solicitud)->first();
                            $solAux = Solicitud::where('Id_solicitud',$solTemp->Hijo)->first();
                        $aux2 = 0;
                        for ($i=0; $i < sizeof($folios); $i++) { 
                            if ($folios[$i] == @$solAux->Folio_servicio) {
                                $aux2 = 1;
                            }
                        } 
                        if ($aux2 == 0) {
                            array_push($ids,$item->Id_solicitud);
                            array_push($folios,@$solAux->Folio_servicio); 
                            array_push($empresas,$temp->Empresa);
                            array_push($recepciones,$temp->Hora_recepcion);
                            array_push($recepciones2,$temp->Hora_entrada);
                            }

                        }
                      
                }
                break;
            default:

                break;
        }

        $data = array(
            'ids' => $ids,
            'folios' => $folios,
            'empresas' => $empresas,
            'recepciones' => $recepciones,
            'recepciones2' => $recepciones2,
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getPuntoAnalisis(Request $res)
    {
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud',$res->idSol)->first();
        $punto = SolicitudPuntos::where('Id_solPadre',$solModel->Hijo)->get();

            // $punto = DB::table('ViewPuntoMuestreoGen')->where('Id_solPadre',$res->idSol)->get();
        $model = array();
        $temp = array();
        foreach($punto as $item)
        {
            $temp = array();
            $tempExtra = "";
            $solTemp = Solicitud::where('Id_solicitud',$item->Id_solicitud)->first();
            $paramExtra = DB::table('viewsolicitudparametros')->where('Extra','!=',0)->where('Id_area',2)->where('Id_solicitud',$item->Id_solicitud)->get();
            array_push($temp,$solTemp->Folio_servicio);
            array_push($temp,$item->Punto);   
            array_push($temp,$solModel->Clave_norma); 
            array_push($temp,$item->Obs_metales);
            array_push($temp,$item->Ph_metales); 
            array_push($temp,$item->Id_solicitud); 
            foreach ($paramExtra as $item2) {
                $tempExtra = $tempExtra . ", (".$item2->Id_parametro .")".$item2->Parametro;
            }
            array_push($temp,$tempExtra); 

            array_push($model,$temp);  
        }
        
        $data = array(
            'punto' => $punto,
            'solModel' => $solModel,
            'model' => $model,
        );
        return response()->json($data);
    }

    public function aplicarObservacion(Request $res)
    {
        $solModel = DB::table('ViewSolicitud2')->where('Id_solicitud',$res->idSol)->first();
        $punto = SolicitudPuntos::where('Id_solPadre',$solModel->Hijo)->get();

        foreach($punto as $item) 
        {
            $puntoModel = SolicitudPuntos::find($item->Id_punto);
            $puntoModel->Obs_metales = $res->obs;
            $puntoModel->Ph_metales = $res->ph;
            $puntoModel->save();
        }
        
        $data = array(
            'punto' => $punto,
            'solModel' => $solModel,
        );
        return response()->json($data);
    }
    // todo ************************************************
    // todo Fin de Observacion
    // todo ************************************************

    // todo ************************************************
    // todo Inicio de captura
    // todo ************************************************
    public function tipoAnalisis()
    {
        return view('laboratorio.metales.tipoAnalisis');
    }

    public function captura()
    {
        // $parametro = DB::table('ViewParametroUsuarios')->where('Id_user', Auth::user()->id)->get();
        $parametro = DB::table('ViewParametros')->where('Id_area',2)->orWhere('Id_area',17)->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get(); 
        // var_dump($parametro);
        $controlModel = ControlCalidad::all();
        return view('laboratorio.metales.captura', compact('parametro', 'controlModel'));
    }
    public function capturaIcp()
    {
        return view('laboratorio.metales.capturaIcp');
    }
    public function getDataCaptura(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach ($lote as $item) {
            $detModel = DB::table('ViewLoteDetalle')->where('Id_lote', $item->Id_lote)->first();
            if ($detModel->Id_parametro == $request->formulaTipo) {
                $idLote = $detModel->Id_lote;
            }
        }

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Fecha_inicio', $idLote )->first();
        $data = array(
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'curvaConst' => $curvaConst,
            'detalle' => $detalle,
        );
        return response()->json($data);
    }
    public function createControlCalidad(Request $request)
    {
        $muestra = LoteDetalle::where('Id_detalle', $request->idMuestra)->first();
        $temp = LoteDetalle::where('Id_codigo',$muestra->Id_codigo)->where('Id_control',$request->idControl)->get();
        $msg = "";
        if ($temp->count()) {
            $msg = "Ya existe este control de calidad sobre este folio";
        }else{
            $model = $muestra->replicate();
            $model->Id_control = $request->idControl;
            $model->Liberado = 0;
            $model->Vol_disolucion = null;  
            $model->save(); 

            $detalleModel = LoteDetalle::where('Id_lote', $request->idLote)->get();
            $lote = LoteAnalisis::find($request->idLote);
            $lote->Asignado = $detalleModel->count();
            $lote->save();
            $msg = "Control de calidad creado";
        }

        $data = array(
            'msg' => $msg,
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

        $loteDetail = DB::table('ViewLoteDetalle')->where($consultas)->first();

        return response()->json(
            compact('loteDetail')
        );
    }
    public function createControlCalidadMetales(Request $res)
    {
        $msg = 'Controles creados';
        $model = LoteDetalle::where('Id_lote',$res->idLote)->where('Id_control',1)->get();
        $lote = LoteAnalisis::where('Id_lote',$res->idLote)->first();
        $cont = 0;
        $aux = 0;
        $orden = 1;
        $idDetalle = 0;
        $sw = $model->count();
        $comprobacion = array();

        switch ($lote->Id_tecnica) {
            case 20:
            case 21:
            case 23:
            case 18:
            case 24:
            case 25:
            case 232:
            case 187:
            case 226:
            case 197:
            case 351:
            case 41:
            case 354:
            case 353:
            case 355:
            case 356:
            case 17:
            case 22:
            case 352:
                foreach ($model as $item) {
                    $sw--;
                    if ($aux == 0) {
                        //Estandar de verificacion
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',14)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 14;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        //Estandar
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',4)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 4;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        // Blanco Reactivo
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',9)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 9;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        $aux = 1;
                    }
                    if ($cont == 0) {
                        $idDetalle = $item->Id_detalle;
                    }
                    
                    $temp = LoteDetalle::where('Id_detalle',$item->Id_detalle)->first();
                    $temp->Orden = $orden;
                    $temp->save();
                    $orden++;
        
                    if ($cont >= 9 || $sw == 0) {
                        // Muestra adicionada
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',3)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 3;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                         // Muestra duplicada
                         $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',2)->get();
                         if ($comprobacion->count()) {
                            $orden++;
                         }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 2;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                         }
                    }
                    if ($cont == 9) {
                        $cont = 0;
                    }else{
                        $cont++;
                    }
                }
                break;
            case 216:
            case 210:
            case 208:
            case 215:
            case 191:
            case 194:
            case 189:
            case 192:
            case 204:
            case 190:
            case 196:
            case 188:
            case 219:
            case 195:
            case 230:
                foreach ($model as $item) {
                    $sw--;
                    if ($aux == 0) {
                        //MC1
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',22)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 22;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        //Blanco.
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',5)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 5;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        // Estandar control
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',4)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 4;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        // Blanco reactivo
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',9)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 9;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                        $aux = 1;
                        $idDetalle = $item->Id_detalle;
                    }
              
                    $temp = LoteDetalle::where('Id_detalle',$item->Id_detalle)->first();
                    $temp->Orden = $orden;
                    $temp->save();
                    $orden++;
        
                    if ($cont >= 9 || $sw == 0) {
                        // Muestra duplicado
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',2)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 2;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                         // Muestra adicionada
                         $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',3)->get();
                         if ($comprobacion->count()) {
                            $orden++;
                         }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 3;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                         }
                         // Blanco 2
                        $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',15)->get();
                        if ($comprobacion->count()) {
                            $orden++;
                        }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 15;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                        }
                         // MCI2
                         $comprobacion = LoteDetalle::where('Id_codigo',$item->Id_codigo)->where('Id_control',23)->get();
                         if ($comprobacion->count()) {
                            $orden++;
                         }else{
                            $temp = $item->replicate();
                            $temp->Id_control = 23;
                            $temp->Liberado = 0;
                            $temp->Orden = $orden;
                            $temp->save();
                            $orden++;
                         }
                    }
                    if ($cont == 9) {
                        $cont = 0;
                    }else{
                        $cont++;
                    }
                }
                break;
            default:
                # code...
                break;
        }


    
        $detalleModel = LoteDetalle::where('Id_lote', $res->idLote)->get();
        $lote = LoteAnalisis::find($res->idLote);
        $lote->Asignado = $detalleModel->count();
        $lote->save();


        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }

    public function setControlCalidad(Request $request)
    {
        //! Blanco reactivo
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Blanco reactivo",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Estandar de verificacion
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Estandar de verificacion",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Muestra duplicada
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Muestra duplicada",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Muestra adicionada
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Muestra adicionada",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);
        //! Estandar
        $loteModel = LoteDetalle::where('Id_detalle', $request->numMuestra[$request->ranCon[0]])->first();
        LoteDetalle::create([
            'Id_lote' => $loteModel->Id_lote,
            'Id_analisis' => $loteModel->Id_analisis,
            'Id_parametro' => $loteModel->Id_parametro,
            'Descripcion' => "Estandar",
            'Vol_muestra' => 50,
            'Abs1' => 0,
            'Abs2' => 0,
            'Abs3' => 0,
            'Abs_promedio' => 0,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Vol_disolucion' => 0,
            'Liberado' => 0,
        ]);

        $detalleModel = LoteDetalle::where('Id_lote', $loteModel->Id_lote)->get();
        $lote = LoteAnalisis::find($loteModel->Id_lote);
        $lote->Asignado = $detalleModel->count();
        $lote->save();

        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $loteModel->Id_lote)->get();


        $data = array(
            'detalle' => $detalle
        );
        return response()->json($data);
    }
    public function operacion(Request $request)
    {
        //! Cambio de busqueda de BMR de Lote a rango de fechas.
        

        $detalleModel = LoteDetalle::where('Id_detalle', $request->idDetalle)->first();
        $sw = false;
        if($detalleModel->Liberado != 1){
            $sw = true;
        $loteTemp = LoteAnalisis::where('Id_lote',$detalleModel->Id_lote)->first();
        $fecha = new Carbon($loteTemp->Fecha);
        $today = $fecha->toDateString();
        $parametroModel = Parametro::where('Id_matriz', 12)->where('Id_parametro', $detalleModel->Id_parametro)->get();

        //Buscar la BMR 
        $parametro = Parametro::where('Id_parametro', $request->idParametro)->first();
        //$curvaConstantes = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        $curvaConstantes  = CurvaConstantes::whereDate('Fecha_inicio', '<=', $today)->whereDate('Fecha_fin', '>=', $today)
            // ->where('Id_area', $parametro->Id_area)
            ->where('Id_parametro', $parametro->Id_parametro)->first();

        $parametroPurificada = Parametro::where('Id_matriz', 14)->where('Id_parametro', $detalleModel->Id_parametro)->get();

        $x = $request->x;
        $y = $request->y;
        $z = $request->z;
        $FD = $request->FD;
        $FC = $request->FC;
        $suma = ($x + $y + $z);
        $promedio = round(($suma / 3),4);
        $volFinal = 0;
        $resMicro = 0;
        
        $resultado = "";
      
        switch ($parametro->Id_matriz) {
            case 14: // MetalPotable
            case 8:
                switch ($parametro->Id_parametro) {
                    case  215:
                    $promedio = round(($suma / 3),3);
                    $temp =   (($promedio - $curvaConstantes->B) / $curvaConstantes->M);
                    $resMicro = $temp;
                    $temp = $temp * $FD * $request->volFinal;  
                    $resultado = ($temp ) / ($request->volMuestra * $FC);
                        break;
                    case 197:
                    case 232:
                    case 226:
                    case 187:
                    case 351:
                    case 41:
                    case 354:
                    case 353:
                    case 55:
                        $fechaResidual = \Carbon\Carbon::parse(@$loteTemp->Fecha)->format('Y-m-d');
                        $fechaLimite = \Carbon\Carbon::parse("2024-01-08")->format('Y-m-d');
                        if ($fechaResidual <= $fechaLimite) {
                            $sw = false;
                            $promedio = round(($suma / 3),4);
                        }else{
                            $sw = true;
                            $promedio = round(($suma / 3),3);
                        }
                
                        if ($parametroModel->count()) {
                            if ($detalleModel->Descripcion != "Resultado") {
                                $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;
                                $resMicro = $resultado;
                            } else {
                                $resultado = ((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD);
                                $resMicro = $resultado;
                                $resultado = $resultado / $FC;
                            }
                        } else {
                                $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;      
                        } 
                        break;
                    default:
                    $paso1 = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;
                    $resMicro = $paso1;
                    $resultado = ($paso1 * 1) / $FC;   
                        break;
                }
                break;
            case 13: //Metal purificado
            case 9:
                $promedio = round(($suma / 3),3);
                switch ($parametro->Id_parametro) {
                    case 190: 
                    case 192:
                    case 204:
                    case 196:
                    case 191:
                    case 194:
                    case 189:
                        $volFinal = $request->volFinal;
                        $temp =   (((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD) * $request->volFinal);   
                        $resMicro = (($promedio - $curvaConstantes->B) / $curvaConstantes->M);
                        $resultado = (($temp ) / ($request->volMuestra * $FC)); 
                        break;
                    default:
                        $temp =   (((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD) * $request->volFinal);   
                        $resMicro = (($promedio - $curvaConstantes->B) / $curvaConstantes->M);
                        $resultado = ($temp ) / ($request->volMuestra * $FC); 
                        break;
                }
                break;
            default:            
                $fechaResidual = \Carbon\Carbon::parse(@$loteTemp->Fecha)->format('Y-m-d');
                $fechaLimite = \Carbon\Carbon::parse("2024-01-08")->format('Y-m-d');
                if ($fechaResidual <= $fechaLimite) {
                    $sw = false;
                    $promedio = round(($suma / 3),4);
                }else{
                    $sw = true;
                    $promedio = round(($suma / 3),3);
                }
        
                if ($parametroModel->count()) {
                    if ($detalleModel->Descripcion != "Resultado") {
                        $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;
                        $resMicro = ($promedio - $curvaConstantes->B) / $curvaConstantes->M;
                    } else {
                        $resultado = ((($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD);
                        $resMicro =(($promedio - $curvaConstantes->B) / $curvaConstantes->M);
                        $resultado = $resultado / $FC;
                    }
                } else {
                        $resultado = (($promedio - $curvaConstantes->B) / $curvaConstantes->M) * $FD;      
                } 
            break;
        }

        $resultadoRound = round($resultado, 3);

       
        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Vol_muestra = $request->volMuestra;
        $detalle->Vol_final = $request->volFinal;
        $detalle->Vol_dirigido = $request->volDirigido;
        $detalle->Abs1 = $request->x; 
        $detalle->Abs2 = $request->y;
        $detalle->Abs3 = $request->z;
        $detalle->Abs_promedio = $promedio;
        $detalle->Factor_dilucion = $request->FD;
        $detalle->Factor_conversion = $request->FC;
        $detalle->Resultado_microgramo = $resMicro;
        $detalle->Vol_disolucion = $resultadoRound;
        $detalle->Observacion = $request->obs;
        $detalle->Analizo = Auth::user()->id;
        $detalle->save();
       }

        $data = array(
            'resMicro' => $resMicro,
            'sw' => $sw,
            'parametro' =>  @$parametro,
            'idDeta' => @$request->idDetalle,
            'curva' => @$curvaConstantes,
            'promedio' => @$promedio,
            'resultado' => @$resultado,
            'resultadoRound' => @$resultadoRound,
            'FC' => @$FC,
            'obs' => @$request->obs,
        );

        return response()->json($data);
    }
    public function enviarObservacion(Request $request)
    {       
            
            $detalle = LoteDetalle::find($request->idMuestra);
            if ($detalle->Liberado != 1) {
                $detalle->Observacion = $request->observacion;   
            }
            $detalle->save();  
      
        $data = array(
            'idDetalle' => $request->idMuestra,
            'model' => $detalle,
        );
        return response()->json($data);
    }

    public function liberarMuestraMetal(Request $request)
    {
        $sw = false;
        $model = LoteDetalle::find($request->idMuestra);
        $model->Liberado = 1; 
        $model->Analizo = Auth::user()->id;
        if (strval($model->Vol_disolucion) != null) {
            $sw = true;
            $model->save();
        }
        if ($model->Id_control == 1) {
            $modelCod = CodigoParametros::find($model->Id_codigo);
            $modelCod->Resultado = $model->Vol_disolucion;
            $modelCod->Resultado2 = $model->Vol_disolucion;
            $modelCod->Analizo = Auth::user()->id;
            $modelCod->save();
        } 

        $model = LoteDetalle::where('Id_lote', $request->idLote)->where('Liberado', 1)->get();
        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();

        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }

    public function liberarTodo(Request $res)
    {
        $sw = false;

        $muestras = LoteDetalle::where('Id_lote',$res->idLote)->where('Liberado',0)->get();
        foreach ($muestras as $item) {
            $model = LoteDetalle::find($item->Id_detalle);
            $model->Liberado = 1;
            $model->Analizo = Auth::user()->id;
            if (strval($model->Vol_disolucion) != NULL) {
                $sw = true;
                $model->save(); 
            }   
            if($item->Id_control == 1)
            {
                $modelCod = CodigoParametros::find($model->Id_codigo);
                $modelCod->Resultado2 = $model->Vol_disolucion;
                $modelCod->Resultado = $model->Vol_disolucion;
                $modelCod->Analizo = Auth::user()->id;
                $modelCod->save();
            }
        }
        


        $model = LoteDetalle::where('Id_lote', $res->idLote)->where('Liberado', 1)->get();
        $loteModel = LoteAnalisis::find($res->idLote);
        $loteModel->Liberado = $model->count();
        $loteModel->save();


        $data = array(
            'model' => $model,
            'sw' => $sw,
        );
        return response()->json($data);
    }

    // todo ************************************************
    // todo Fin de Captura
    // todo ************************************************

    // todo ************************************************
    // todo Inicio de lote
    // todo ************************************************

    public function lote()
    {
        // $parametro = DB::table('ViewParametroUsuarios')->where('Id_user',Auth::user()->id)->get();
        $tipo = DB::table('tipo_formulas')
        ->where('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
        ->orWhere('Id_tipo_formula',58)
        ->orWhere('Id_tipo_formula',59)
        ->get();

        $data = array(
            'tipo'=> $tipo 
        );
        return view('laboratorio.metales.lote',$data);
    }
    public function createLote(Request $res)
    {
          for ($i=0; $i < sizeof($res->ids) ; $i++) { 
           if($res->horas[$i] != "")
           {
            $model = LoteAnalisis::where('Id_tecnica',$res->ids[$i])->where('Fecha',$res->fechas[$i])->get();
            if($model->count())
            {
                $temp = LoteAnalisis::where('Id_tecnica',$res->ids[$i])->where('Fecha',$res->fechas[$i])->first();
                $temp->Hora = $res->horas[$i];
                $temp->save();
            }else{
                $tempLote = LoteAnalisis::create([
                    'Id_area' => 2,
                    'Id_tecnica' => $res->ids[$i],
                    'Asignado' => 0,
                    'Liberado' => 0,
                    'Fecha' => $res->fechas[$i],
                    'Hora' => $res->horas[$i],
                ]);
                $confModel = ConfiguracionMetales::where('Id_parametro',$res->ids[$i])->get();
                if ($confModel->count()) {
                    MetalesDetalle::create([
                        'Id_lote'=> $tempLote->Id_lote,
                        'Fecha_digestion' => $confModel[0]->Fecha_digestion,
                        'Longitud_onda' => $confModel[0]->Longitud_onda,
                        'No_inventario' => $confModel[0]->No_Inventario,
                        'Corriente' => $confModel[0]->Lampara,
                        'Gas' => $confModel[0]->Acetileno,
                        'Flujo_gas' => $confModel[0]->Flujo_gas,
                        'No_lampara' => $confModel[0]->No_lampara,
                        'Energia' => $confModel[0]->Energia,
                        'Aire' => $confModel[0]->Aire,
                        'Equipo' => $confModel[0]->Equipo,
                        'Slit' => $confModel[0]->Slit,
                        'Conc_std' => $confModel[0]->Concentracion,
                        'Oxido_nitroso' => $confModel[0]->Oxido_nitroso,
                        'Bitacora' => $confModel[0]->Bitacora_curva,
                        'Folio' => $confModel[0]->Folio,
                        'Valor' => $confModel[0]->Valor,
                    ]);
                }else{
                    MetalesDetalle::create([
                        'Id_lote'=> $tempLote->Id_lote,
                    ]);
                }
 
            }
           }
        }
        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function buscarLote(Request $request)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->tipo)->where('Id_area', 2)->where('Fecha', $request->fecha)->get();
        if ($model->count()) {
            $sw = true;
        }

        $data = array(
            'model' => $model, 
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function getCapturaLote(Request $request)
    {

        if ($request->folio != "") {
            $tempCod = CodigoParametros::where('Codigo',$request->folio)->where('Id_parametro',$request->formulaTipo)->first();
            $tempLote = LoteDetalle::where('Id_codigo',$tempCod->Id_codigo)->first();
            $model = DB::table('ViewLoteAnalisis')->where('Id_lote', $tempLote->Id_lote)->get();

            $fecha = new Carbon($request->fechaAnalisis); 
            $today = $fecha->toDateString();

            $parametro = Parametro::where('Id_parametro', $request->formulaTipo)->first();
            $estandares = estandares::where('Id_parametro', $request->formulaTipo)->whereDate('Fecha_inicio','<=',$today)->whereDate('Fecha_fin','>=',$today)->get();
            $curva  = CurvaConstantes::whereDate('Fecha_inicio', '<=', $model[0]->Fecha)->whereDate('Fecha_fin', '>=', $model[0]->Fecha)
            ->where('Id_parametro', $parametro->Id_parametro)->first(); 
        }else{
            $model = DB::table('ViewLoteAnalisis')->where('Id_tecnica', $request->formulaTipo)->whereDate('Fecha', $request->fechaAnalisis)->get();
            $fecha = new Carbon($request->fechaAnalisis); 
            $today = $fecha->toDateString();

            $parametro = Parametro::where('Id_parametro', $request->formulaTipo)->first();
            $estandares = estandares::where('Id_parametro', $request->formulaTipo)->whereDate('Fecha_inicio','<=',$today)->whereDate('Fecha_fin','>=',$today)->get();
            $curva  = CurvaConstantes::whereDate('Fecha_inicio', '<=', $request->fechaAnalisis)->whereDate('Fecha_fin', '>=', $request->fechaAnalisis)
            ->where('Id_parametro', $parametro->Id_parametro)->first(); 
        }

        $data = array(
            'today'  => $today,
            'lote' => $model,
            'curva' => $curva,
            'estandares' => $estandares,
        );
        return response()->json($data);
    }
    public function getLote(Request $res)
    {
        $parametros = DB::table('ViewParametros')
        ->where('Id_area',2)
        ->where(function($query) use ($res) {
            $query->where('Id_tipo_formula',$res->tipo)
                  ->orWhere(function($query) use ($res) {
                      $query->where('Id_tipo_formula',58)
                            ->orWhere('Id_tipo_formula',59)
                            ->when($res->tipo == 22, function($query) {
                                $query->orWhere('Id_tipo_formula',22);
                            });
                  });
        })
        ->get();
    

        $model = array();
        $temp = array();
        foreach($parametros as $item)
        {
            $temp = array();
            $lote = LoteAnalisis::where('Id_tecnica',$item->Id_parametro)
            ->where('Fecha',$res->fecha)->get();
            if ($lote->count()) {
                array_push($temp,$lote[0]->Id_lote);
                array_push($temp,$item->Id_parametro);
                array_push($temp,$item->Parametro);
                array_push($temp,$item->Tipo_formula);
                array_push($temp,$res->fecha);
                array_push($temp,$lote[0]->Hora);
            } else { 
                array_push($temp,"N/A");
                array_push($temp,$item->Id_parametro);
                array_push($temp,$item->Parametro);
                array_push($temp,$item->Tipo_formula);
                array_push($temp,$res->fecha);
                array_push($temp,"");
            }
            array_push($model,$temp); 
        }

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getLoteCaptura(Request $request)
    { 
        // $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)->where('Liberado',0)->get(); // Asi se hara con las otras
        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)
        ->orderBy('Orden','asc')->get();
        $obs = array();
        $punto = array();
        $temp = "";
        foreach ($detalle as $item) {
            $temp = SolicitudPuntos::where('Id_solicitud',$item->Id_analisis)->first();
            array_push($obs,$temp->Obs_metales);
            array_push($punto,$temp->Punto);
        }
        $data = array(
            'obs' => $obs,
            'punto' => $punto,
            'detalle' => $detalle,
            'temp' => $temp,
        );
        return response()->json($data);
    }
    public function getDetalleLote(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote',$res->id)->first();
        $parametro = Parametro::where('Id_parametro',$lote->Id_tecnica)->first();
        $model = MetalesDetalle::where('Id_lote',$res->id)->get();
        $plantilla = Bitacoras::where('Id_lote', $res->id)->get(); 
        $configuracion = ConfiguracionMetales::where('Id_parametro',$lote->Id_tecnica)->first();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
        }

        $data = array( 
            'parametro' => $parametro,
            'plantilla' => $plantilla,
            'model' => $model,
            'configuracion' => $configuracion,
        );
        return response()->json($data);
    }
    public function setDetalleLote(Request $res)
    {
        $model = MetalesDetalle::where('Id_lote',$res->id)->get();
        if ($model->count()) {
            $model[0]->Fecha_digestion = $res->fechaDigestion;
            $model[0]->Fecha_preparacion = $res->fechaPreparacion;
            $model[0]->Longitud_onda = $res->longitudOnda;
            $model[0]->No_inventario = $res->noInventario;
            $model[0]->Corriente = $res->corriente;
            $model[0]->Gas = $res->gas;
            $model[0]->Flujo_gas = $res->flujoGas;
            $model[0]->No_lampara = $res->noLampara;
            $model[0]->Energia = $res->energia;
            $model[0]->Aire = $res->aire;
            $model[0]->Equipo = $res->equipo;
            $model[0]->Slit = $res->slit;
            $model[0]->Conc_std = $res->conStd;
            $model[0]->Oxido_nitroso = $res->oxidoNitroso;
            $model[0]->Verificacion_blanco = $res->verificacionBlanco;
            $model[0]->Abs_teoricoB = $res->absTeoricaB;
            $model[0]->Abs1B = $res->abs1B;
            $model[0]->Abs2B = $res->abs2B;
            $model[0]->Abs3B = $res->abs3B;
            $model[0]->Abs4B = $res->abs4B;
            $model[0]->Abs5B = $res->abs5B;
            $model[0]->PromedioB = $res->promedioB;
            $model[0]->ConclusionB = $res->conclusionB;
            $model[0]->Std_calE = $res->stdCalE;
            $model[0]->Abs_teoricoE = $res->absTeoricaE;
            $model[0]->ConcE = $res->concE;
            $model[0]->Abs1E = $res->abs1E;
            $model[0]->Abs2E = $res->abs2E;
            $model[0]->Abs3E = $res->abs3E;
            $model[0]->Abs4E = $res->abs4E;
            $model[0]->Abs5E = $res->abs5E;
            $model[0]->PromedioE = $res->promedioE;
            $model[0]->MasaE = $res->masaE;
            $model[0]->ConclusionE = $res->conclusionE;
            $model[0]->Conc_obtenidaE = $res->concObtenidaE;
            $model[0]->RecuperacionE = $res->recE;
            $model[0]->CumpleE = $res->cumpleE;
            $model[0]->ConcI = $res->concI;
            $model[0]->DesvI = $res->desvI;
            $model[0]->CumpleI = $res->cumpleI;
            $model[0]->Abs1I = $res->abs1I;
            $model[0]->Abs2I = $res->abs2I;
            $model[0]->Abs3I = $res->abs3I;
            $model[0]->Abs4I = $res->abs4I;
            $model[0]->Abs5I = $res->abs5I; 
            $model[0]->Bitacora = $res->bitacora;
            $model[0]->Folio = $res->folio;
            $model[0]->Valor = $res->valor;
            $model[0]->save();
        }else{
            MetalesDetalle::create([
                'Id_lote'=> $res->id,
                'Fecha_digestion' => $res->fechaDigestion,
                'Fecha_preparacion' => $res->fechaPreparacion,
                'Longitud_onda' => $res->longitudOnda,
                'No_inventario' => $res->noInventario,
                'Corriente' => $res->corriente,
                'Gas' => $res->gas,
                'Flujo_gas' => $res->flujoGas,
                'No_lampara' => $res->noLampara,
                'Energia' => $res->energia,
                'Aire' => $res->aire,
                'Equipo' => $res->equipo,
                'Slit' => $res->slit,
                'Conc_std' => $res->conStd,
                'Oxido_nitroso' => $res->oxidoNitroso,
                'Verificacion_blanco' => $res->verificacionBlanco,
                'Abs_teoricoB' => $res->absTeoricaB,
                'Abs1B' => $res->abs1B,
                'Abs2B' => $res->abs2B,
                'Abs3B' => $res->abs3B,
                'Abs4B' => $res->abs4B,
                'Abs5B' => $res->abs5B,
                'PromedioB' => $res->promedioB,
                'ConclusionB' => $res->conclusionB,
                'Std_calE' => $res->stdCalE,
                'Abs_teoricoE' => $res->absTeoricaE,
                'ConcE' => $res->concE,
                'Abs1E' => $res->abs1E,
                'Abs2E' => $res->abs2E,
                'Abs3E' => $res->abs3E,
                'Abs4E' => $res->abs4E,
                'Abs5E' => $res->abs5E,
                'PromedioE' => $res->promedioE,
                'MasaE' => $res->masaE,
                'ConclusionE' => $res->conclusionE,
                'Conc_obtenidaE' => $res->concObtenidaE,
                'RecuperacionE' => $res->recE,
                'CumpleE' => $res->cumpleE,
                'ConcI' => $res->concI,
                'DesvI' => $res->desvI,
                'CumpleI' => $res->cumpleI,
                'Abs1I' => $res->abs1I,
                'Abs2I' => $res->abs2I,
                'Abs3I' => $res->abs3I,
                'Abs4I' => $res->abs4I,
                'Abs5I' => $res->abs5I,
                'Bitacora' => $res->bitacora,
                'Folio' => $res->folio,
                'Valor' => $res->valor,
            ]);
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //RECUPERAR DATOS PARA ENVIARLOS A LA VENTANA MODAL > EQUIPO PARA RELLENAR LOS DATOS ALMACENADOS EN LA BD
    public function getDatalote(Request $request)
    {
        $data = array();

        $idLoteIf = $request->idLote;
        $reporte = Reportes::where('Id_lote', $request->idLote)->first();

        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();

        if ($constantesModel->count()) {
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();

            array_push($data, $constantes);
        } else {
            array_push($data, null);
        }

        $tecnicaLoteMet = DB::table('tecnica_lote_metales')->where('Id_lote', $request->idLote)->get();
        $blancoCurvaMet = DB::table('blanco_curva_metales')->where('Id_lote', $request->idLote)->get();
        $estandarVerificacionMet = DB::table('estandar_verificacion_met')->where('Id_lote', $request->idLote)->get();
        $verificacionMet = DB::table('verificacion_metales')->where('Id_lote', $request->idLote)->get();
        $curvaCalibracionMet = DB::table('curva_calibracion_met')->where('Id_lote', $request->idLote)->get();
        $generadorHidrurosMet = DB::table('generador_hidruros_met')->where('Id_lote', $request->idLote)->get();

        if ($tecnicaLoteMet->count() && $blancoCurvaMet->count() && $estandarVerificacionMet->count() && $verificacionMet->count() && $curvaCalibracionMet->count() && $generadorHidrurosMet->count()) {
            $tecLotMet = TecnicaLoteMetales::where('Id_lote', $request->idLote)->first();
            $blancCurvaMet = BlancoCurvaMetales::where('Id_lote', $request->idLote)->first();
            $stdVerMet = EstandarVerificacionMet::where('Id_lote', $request->idLote)->first();
            $verMet = VerificacionMetales::where('Id_lote', $request->idLote)->first();
            $curMet = CurvaCalibracionMet::where('Id_lote', $request->idLote)->first();
            $genMet = GeneradorHidrurosMet::where('Id_lote', $request->idLote)->first();

            array_push(
                $data,
                $tecLotMet,
                $blancCurvaMet,
                $stdVerMet,
                $verMet,
                $curMet,
                $genMet,
            );
        } else {
            array_push(
                $data,
                null,
                null,
                null,
                null,
                null, 
                null
            );
        }

        array_push($data, $reporte, $idLoteIf);
        return response()->json($data);
    }

    public function getPlantillaPred(Request $request)
    {
        $plantillaPredeterminada = Reportes::where('Id_lote', $request->idLote)->first();

        return response()->json($plantillaPredeterminada);
    }


    public function asignar()
    {
        $tipo = DB::table('tipo_formulas')
        ->where('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
        ->orWhere('Id_tipo_formula',58)
        ->orWhere('Id_tipo_formula',59)
        ->get();
        $tecnica = DB::table('tecnicas')
        ->where('Id_tecnica',20)
        ->orWhere('Id_tecnica',21)
        ->orWhere('Id_tecnica',22)
        ->get();
        $norma = Norma::all();
        $data = array(
            'tecnica' => $tecnica,
            'tipo' => $tipo,
            'norma' => $norma,
        );
        return view('laboratorio.metales.asignar', $data);
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
    public function getMuestras(Request $res)
    {
        $temp = array();
        $model = array();
        $codigo = array();
        switch ($res->sw) {
            case 1:
                $codigo = DB::table('ViewCodigoPendientes')->where('Id_area',2)->where('Hijo','!=',0)->where('Asignado',0)->get();
                foreach ($codigo as $item) {
                    $temp = array();
                    array_push($temp,$item->Id_codigo);
                    array_push($temp,$item->Codigo);
                    array_push($temp,$item->Empresa);
                    if ($item->Siralab == 1) {
                        $punto = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solicitud',$item->Id_solicitud)->first();
                        array_push($temp,$punto->Punto);
                    } else {
                        $punto = DB::table('ViewPuntoMuestreoGen')->where('Id_solicitud',$item->Id_solicitud)->first();
                        array_push($temp,$punto->Punto_muestreo);
                    }
                    array_push($temp,"");
                    array_push($temp,$item->Parametro);
                    $lote = DB::table('ViewLoteDetalle')->where('Id_analisis',$item->Id_solicitud)->where('Id_parametro',$item->Id_parametro)->get();
                    if($lote->count())
                    {
                        array_push($temp,$lote[0]->Id_lote);
                        $loteDetalle = LoteAnalisis::find($lote[0]->Id_lote);
                        array_push($temp,$loteDetalle->Fecha);
                        // array_push($temp,$loteDetalle->Hora);
                    }else{
                        array_push($temp,"");
                        array_push($temp,"");
                        // // array_push($temp,"");  
                    }
                    array_push($model,$temp);
                }
                break;
            case 2:
                $codigo = DB::table('ViewCodigoInforme')
                ->where('Id_area', 2)
                ->where('Asignado', 0)
                ->when($res->tipo != 0, function ($query) use ($res) {
                    $query->where('Id_tipo_formula', $res->tipo);
                })
                ->when($res->tecnica != 0, function ($query) use ($res) {
                    $query->where('Id_tecnica', $res->tecnica);
                }) 
                ->get();
                foreach ($codigo as $item) {
                    if ($res->fechaRecepcion != '') {
                        $aux = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->whereDate('Hora_recepcion',$res->fechaRecepcion)->get();
                    }else{
                        $aux = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->get();
                    }
                    if ($aux->count()) {
                        $temp = array();
                        array_push($temp,$item->Id_codigo);
                        array_push($temp,$item->Codigo);
                        array_push($temp,$aux[0]->Empresa);
                        
                        $punto = SolicitudPuntos::where('Id_solicitud',$item->Id_solicitud)->first();
                        $solTemp = DB::table('ViewSolicitud2')->where('Id_solicitud',$item->Id_solicitud)->first();
                        array_push($temp,@$punto->Punto);
    
                        array_push($temp,$solTemp->Clave_norma);
                        array_push($temp,"(".$item->Id_parametro.") ".$item->Parametro . " (".@$item->Tecnica.")");
                        $lote = LoteAnalisis::whereDate('Fecha',$res->fechaLote)->where('Id_tecnica',$item->Id_parametro)->get();
                        if($lote->count())
                        {
                            array_push($temp,$lote[0]->Id_lote);
                            array_push($temp,$lote[0]->Fecha);
                            // array_push($temp,$loteDetalle->Hora);
                        }else{
                            array_push($temp,"");
                            array_push($temp,""); 
                            // array_push($temp,""); 
                        }
                        array_push($model,$temp);
                    }
              
                }

                break;
            default:
                # code...
                break;
        }
        
        $data = array(
            'codigo' => $codigo,
            'model' => $model,
            // 'codigo2' => $codigo2,
            
        );
        return response()->json($data);
    }
    public function asgnarMuestraLote($id)
    {
        $lote = LoteDetalle::where('Id_lote', $id)->get();
        $idLote = $id;
        return view('laboratorio.metales.asignarMuestraLote', compact('lote', 'idLote'));
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
    //* Muestra asigada a lote
    public function getMuestraAsignada(Request $request)
    {
        $model = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra
    public function delMuestraLote(Request $request)
    {
        $detModel = DB::table('lote_detalle')->where('Id_detalle', $request->idDetalle)->delete();

        $detModel = LoteDetalle::where('Id_lote', $request->idLote)->get();
        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();


        $solModel = CodigoParametros::where('Id_solicitud', $request->idSol)->where('Id_parametro', $request->idParametro)->first();
        $solModel->Asignado = 0;
        $solModel->save();

        $sw = true;

        $data = array(
            'sw' => $sw,
            'idSol' => $request->idSol,
            'idDetalle' => $request->idDetalle,
            'idParam' => $request->idParam,
        );
        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $valFecha = false;
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote', $request->idLote)->first();
        


        LoteDetalle::create([
            'Id_lote' => $request->idLote,
            'Id_analisis' => $request->idAnalisis,
            'Id_parametro' => $loteModel->Id_tecnica,
            'Id_control' => 1,
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
            'Liberado' => 0,
        ]);
        $detModel = LoteDetalle::where('Id_lote', $request->idLote)->get();
        $sw = true;

        $solModel = CodigoParametros::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();


        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $data = array(
            'idSol' => $request->idSol,
            'idAnalisis' => $request->idAnalisis,
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function sendMuestrasLote(Request $res)
    {
        $sw = false; 
        $msg = "";
        $conversion = 0;
        $volMuestra = 100;
        $volFinal = 100;
        $dilucion = 1;

        for ($i=0; $i <  sizeof($res->ids); $i++) { 
            $parametro = CodigoParametros::where('Id_codigo',$res->ids[$i])->first();
            $proceso = ProcesoAnalisis::where('Id_solicitud',$parametro->Id_solicitud)->first();
            $fechaLote = \Carbon\Carbon::parse(@$res->fechaLote)->format('Y-m-d');
            $fechaRecep = \Carbon\Carbon::parse(@$proceso->Hora_recepcion)->format('Y-m-d');
            // $today = $fecha->toDateString();
        //    if ($fechaLote < $fechaRecep) {
        //     $msg = "Es Mayor y entra";
        //     $lote = LoteAnalisis::where('Fecha',$res->fechaLote)
        //     ->where('Id_tecnica',$parametro->Id_parametro)->get();
        //     // $msg = "Entro a for";
        //     switch ($parametro->Id_parametro) {
        //         case 20:
        //         case 21:
        //         case 23:
        //         case 18:
        //         case 24:
        //         case 25:
        //         case 232:
        //         case 187:
        //         case 226:
        //         case 197:
        //         case 351:
        //         case 41:
        //         case 354:
        //         case 353:

        //         case 355:
        //         case 356:
        //         case 22:
        //         case 352:
        //             $conversion = 1;
        //             $volMuestra = 50;
        //             break;
        //         case 216:
        //         case 210:
        //         case 208:
        //             $conversion = 1;
        //             $volMuestra = 45;
        //             $volFinal = 50;
        //             $dilucion = 1.111111;
        //             break;
        //         case 215:
        //             $conversion = 1;
        //             $volMuestra = 45;
        //             $volFinal = 100;
        //             break;
        //         case 191:
        //         case 194:
        //         case 189:
        //         case 192:
        //         case 204:
        //         case 190:
        //         case 196:
        //             $conversion = 1;
        //             $volMuestra = 100;
        //             $volFinal = 100;
        //         break;
        //         case 188:
        //         case 219:
        //         case 195:
        //             $conversion = 1;
        //             $volMuestra = 80;
        //             $volFinal = 100;
        //         break;
        //         case 230:
        //             $conversion = 1;
        //             $volMuestra = 100;
        //             $volFinal = 100;
        //             break;
        //         case 215:
        //             $conversion = 1;
        //             $volMuestra = 45;
        //             $volFinal = 100;
        //             break;
        //         default:
        //             $volMuestra = 100;
        //             $conversion = 1000;
        //             $volFinal = 100;
        //             break;
        //     }
        //     if ($lote->count()) {
        //         $msg = "Entro a if";
        //         $detalle = LoteDetalle::where('Id_codigo',$res->ids[$i])->get();
        //         if($detalle->count()){}
        //         else{
        //             LoteDetalle::create([
        //                 'Id_lote' => $lote[0]->Id_lote,
        //                 'Id_analisis' => $parametro->Id_solicitud,
        //                 'Id_codigo' => $res->ids[$i],
        //                 'Id_parametro' => $parametro->Id_parametro,
        //                 'Id_control' => 1,
        //                 'Factor_dilucion' => 1,
        //                 'Vol_muestra' => $volMuestra,
        //                 'Factor_conversion' => $conversion,
        //                 'Factor_dilucion' => $dilucion,
        //                 'Liberado' => 0,
        //                 'Analisis' => 1,
                        
        //             ]);
        //             $solModel = CodigoParametros::find($res->ids[$i]);
        //             $solModel->Asignado = 1;
        //             $solModel->save();
    
        //             $detModel = LoteDetalle::where('Id_lote', $lote[0]->Id_lote)->get();
            
        //             $loteModel = LoteAnalisis::find($lote[0]->Id_lote);
        //             $loteModel->Asignado = $detModel->count();
        //             $loteModel->Liberado = 0;
        //             $loteModel->save();
        //             $sw = true;
        //         }
        //     }
        //    }else{

        //     // $sw = "Es Menor y no paso";
        //    }

        
            $msg = "Es Mayor y entra";
            // $fechaLote =  \Carbon\Carbon::parse(@$res->fechaLote)->format('Y-m-d');
            $lote = LoteAnalisis::where('Fecha',$res->fechaLote)
            ->where('Id_tecnica',$parametro->Id_parametro)->get();
            // $msg = "Entro a for";
            switch ($parametro->Id_parametro) {
                case 20:
                case 21:
                case 23:
                case 18:
                case 24:
                case 25:
                case 232:
                case 187:
                case 226:
                case 197:
                case 351:
                case 41:
                case 354:
                case 353:

                case 355:
                case 356:
                case 22:
                case 352:
                    $conversion = 1;
                    $volMuestra = 50;
                    break;
                case 216:
                case 210:
                case 208:
                    $conversion = 1000;
                    $volMuestra = 45;
                    $volFinal = 50;
                    $dilucion = 1.111111;
                    break;
                case 215:
                    $conversion = 1000;
                    $volMuestra = 80;
                    $volFinal = 100;
                    break;
                case 191:
                case 194:
                case 189:
                case 192:
                case 204:
                case 190:
                case 196:
                    $conversion = 10000;
                    $volMuestra = 100;
                    $volFinal = 100;
                break;
                case 188:
                case 219:
                case 195:
                    $conversion = 1000;
                    $volMuestra = 80;
                    $volFinal = 100;
                break;
                case 230:
                    $conversion = 1000;
                    $volMuestra = 100;
                    $volFinal = 100;
                    break;

                default:
                    $volMuestra = 100;
                    $conversion = 1000;
                    $volFinal = 100;
                    break;
            }
            if ($lote->count()) {
                $msg = "Entro a if";
                $detalle = LoteDetalle::where('Id_codigo',$res->ids[$i])->get();
                if($detalle->count()){}
                else{
                    LoteDetalle::create([
                        'Id_lote' => $lote[0]->Id_lote,
                        'Id_analisis' => $parametro->Id_solicitud,
                        'Id_codigo' => $res->ids[$i],
                        'Id_parametro' => $parametro->Id_parametro,
                        'Id_control' => 1,
                        'Factor_dilucion' => 1,
                        'Vol_muestra' => $volMuestra,
                        'Vol_final' => $volFinal,
                        'Factor_conversion' => $conversion,
                        'Factor_dilucion' => $dilucion,
                        'Liberado' => 0,
                        'Analisis' => 1,
                         
                    ]);
                    $solModel = CodigoParametros::find($res->ids[$i]);
                    $solModel->Asignado = 1;
                    $solModel->save();
    
                    $detModel = LoteDetalle::where('Id_lote', $lote[0]->Id_lote)->get();
            
                    $loteModel = LoteAnalisis::find($lote[0]->Id_lote);
                    $loteModel->Asignado = $detModel->count();
                    $loteModel->Liberado = 0;
                    $loteModel->save();
                    $sw = true;
                }
            }
      
       
        } 
        
        $data = array(
            'lote' => $lote,
            'fechaLote' => $res->fechaLote,
            'parametro' => $parametro,
            'msg' => $msg,
            'sw' => $sw,
        );
        return response()->json($data);
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request)
    {
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        //$lote = Reportes::where('Id_lote', $idLote)->first();

        $lote = DB::table('reportes')->where('Id_lote', $idLote)->get();

        if ($lote->count()) {
            $texto = Reportes::where('Id_lote', $idLote)->first();
            $texto->Texto = $textoPeticion;
            $texto->Id_user_m = Auth::user()->id;

            $texto->save();
        } else {
            $texto = Reportes::create([
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

    //*************************************GUARDA LOS DATOS DE LA VENTANA MODAL EN MÓDULO LOTE, PESTAÑA EQUIPO************* */
    public function guardarDatosGenerales(Request $request)
    {
        //******************************************************Técnica Lote Metales**********************************************************
        $tecLoteMet = TecnicaLoteMetales::where('Id_lote', $request->idLote)->get();

        if ($tecLoteMet->count()) {
            $tecLote = TecnicaLoteMetales::where('Id_lote', $request->idLote)->first();

            $tecLote->Fecha_hora_dig = $request->flama_fechaHoraDig;
            $tecLote->Longitud_onda = $request->flama_longOnda;
            $tecLote->Flujo_gas = $request->flama_flujoGas;
            $tecLote->Equipo = $request->flama_equipoForm;
            $tecLote->Num_inventario = $request->flama_numInventario;
            $tecLote->Num_invent_lamp = $request->flama_numInvLamp;
            $tecLote->Slit = $request->flama_slit;
            $tecLote->Corriente = $request->flama_corriente;
            $tecLote->Energia = $request->flama_energia;
            $tecLote->Conc_std = $request->flama_concStd;
            $tecLote->Gas = $request->flama_gas;
            $tecLote->Aire = $request->flama_aire;
            $tecLote->Oxido_nitroso = $request->flama_oxidoN;
            $tecLote->Fecha_preparacion = $request->flama_fechaPrep;

            $tecLote->save();
        } else {
            TecnicaLoteMetales::create([
                'Id_lote' => $request->flama_loteId,
                'Fecha_hora_dig' => $request->flama_fechaHoraDig,
                'Longitud_onda' => $request->flama_longOnda,
                'Flujo_gas' => $request->flama_flujoGas,
                'Equipo' => $request->flama_equipoForm,
                'Num_inventario' => $request->flama_numInventario,
                'Num_invent_lamp' => $request->flama_numInvLamp,
                'Slit' => $request->flama_slit,
                'Corriente' => $request->flama_corriente,
                'Energia' => $request->flama_energia,
                'Conc_std' => $request->flama_concStd,
                'Gas' => $request->flama_gas,
                'Aire' => $request->flama_aire,
                'Oxido_nitroso' => $request->flama_oxidoN,
                'Fecha_preparacion' => $request->flama_fechaPrep
            ]);
        }
        //*******************************************BLANCOCURVAMETALES******************************************/
        $blancoCurvaMet = BlancoCurvaMetales::where('Id_lote', $request->idLote)->get();

        if ($blancoCurvaMet->count()) {
            $blancoCurva = BlancoCurvaMetales::where('Id_lote', $request->idLote)->first();

            $blancoCurva->Verif_blanco = $request->blanco_verifBlanco;
            $blancoCurva->ABS_teor_blanco = $request->blanco_absTeoBlanco;
            $blancoCurva->ABS1 = $request->blanco_abs1;
            $blancoCurva->ABS2 = $request->blanco_abs2;
            $blancoCurva->ABS3 = $request->blanco_abs3;
            $blancoCurva->ABS4 = $request->blanco_abs4;
            $blancoCurva->ABS5 = $request->blanco_abs5;
            $blancoCurva->ABS_prom = $request->blanco_absProm;
            $blancoCurva->Concl_blanco = $request->blanco_concBlanco;

            $blancoCurva->save();
        } else {
            BlancoCurvaMetales::create([
                'Id_lote' => $request->idLote,
                'Verif_blanco' => $request->blanco_verifBlanco,
                'ABS_teor_blanco' => $request->blanco_absTeoBlanco,
                'ABS1' => $request->blanco_abs1,
                'ABS2' => $request->blanco_abs2,
                'ABS3'  => $request->blanco_abs3,
                'ABS4' => $request->blanco_abs4,
                'ABS5' => $request->blanco_abs5,
                'ABS_prom' => $request->blanco_absProm,
                'Concl_blanco' => $request->concBlanco
            ]);
        }

        //***********************************************VERIFICACIONMETALES***********************************
        $verMet = VerificacionMetales::where('Id_lote', $request->idLote)->get();

        if ($verMet->count()) {
            $verifMetales = VerificacionMetales::where('Id_lote', $request->idLote)->first();

            $verifMetales->STD_cal = $request->verif_stdCal;
            $verifMetales->ABS_teorica = $request->verif_absTeorica;
            $verifMetales->Conc_mgL = $request->verif_concMgL;
            $verifMetales->ABS1 = $request->verif_Abs1;
            $verifMetales->ABS2 = $request->verif_Abs2;
            $verifMetales->ABS3 = $request->verif_Abs3;
            $verifMetales->ABS4 = $request->verif_Abs4;
            $verifMetales->ABS5 = $request->verif_Abs5;
            $verifMetales->ABS_prom = $request->verif_AbsProm;
            $verifMetales->Masa_caract = $request->verif_masaCarac;
            $verifMetales->Conclusion = $request->verif_conclusion;
            $verifMetales->Conc_obtenida = $request->verif_conclusionObtenida;
            $verifMetales->Porc_rec = $request->verif_rec;
            $verifMetales->Cumple = $request->verif_cumple;

            $verifMetales->save();
        } else {
            VerificacionMetales::create([
                'Id_lote' => $request->idLote,
                'STD_cal' => $request->verif_stdCal,
                'ABS_teorica' => $request->verif_absTeorica,
                'Conc_mgL' => $request->verif_concMgL,
                'ABS1' => $request->verif_Abs1,
                'ABS2' => $request->verif_Abs2,
                'ABS3'  => $request->verif_Abs3,
                'ABS4' => $request->verif_Abs4,
                'ABS5' => $request->verif_Abs5,
                'ABS_prom' => $request->verif_AbsProm,
                'Masa_caract' => $request->verif_masaCarac,
                'Conclusion' => $request->verif_conclusion,
                'Conc_obtenida' => $request->verif_conclusionObtenida,
                'Porc_rec' => $request->verif_rec,
                'Cumple' => $request->verif_cumple
            ]);
        }

        //*************************************************ESTANDARVERIFICACIONMET****************************
        $stdVerMet = EstandarVerificacionMet::where('Id_lote', $request->idLote)->get();

        if ($stdVerMet->count()) {
            $stdVer = EstandarVerificacionMet::where('Id_lote', $request->idLote)->first();

            $stdVer->Conc_mgL = $request->std_conc;
            $stdVer->DESV_std = $request->std_desvStd;
            $stdVer->Cumple = $request->std_cumple;
            $stdVer->ABS1 = $request->std_abs1;
            $stdVer->ABS2 = $request->std_abs2;
            $stdVer->ABS3 = $request->std_abs3;
            $stdVer->ABS4 = $request->std_abs4;
            $stdVer->ABS5 = $request->std_abs5;

            $stdVer->save();
        } else {
            EstandarVerificacionMet::create([
                'Id_lote' => $request->idLote,
                'Conc_mgL' => $request->std_conc,
                'DESV_std' => $request->std_desvStd,
                'Cumple' => $request->std_cumple,
                'ABS1' => $request->std_abs1,
                'ABS2' => $request->std_abs2,
                'ABS3' => $request->std_abs3,
                'ABS4' => $request->std_abs4,
                'ABS5' => $request->std_abs5
            ]);
        }

        //*******************************************CURVACALIBRACIONMET*****************************************
        $curValMet = CurvaCalibracionMet::where('Id_lote', $request->idLote)->get();

        if ($curValMet->count()) {
            $curVal = CurvaCalibracionMet::where('Id_lote', $request->idLote)->first();

            $curVal->Bitacora_curCal = $request->curva_bitCurvaCal;
            $curVal->Folio_curCal = $request->curva_folioCurvaCal;

            $curVal->save();
        } else {
            CurvaCalibracionMet::create([
                'Id_lote' => $request->idLote,
                'Bitacora_curCal' => $request->curva_bitCurvaCal,
                'Folio_curCal' => $request->curva_folioCurvaCal
            ]);
        }

        //*******************************************GENERADORHIDRUROSMET****************************************
        $genHidMet = GeneradorHidrurosMet::where('Id_lote', $request->idLote)->get();

        if ($genHidMet->count()) {
            $genHid = GeneradorHidrurosMet::where('Id_lote', $request->idLote)->first();

            $genHid->Generador_hidruros = $request->gen_genHidruros;

            $genHid->save();
        } else {
            GeneradorHidrurosMet::create([
                'Id_lote' => $request->idLote,
                'Generador_hidruros' => $request->gen_genHidruros
            ]);
        }

        //*******************************************************************************************************
        return response()->json(
            compact('tecLoteMet', 'blancoCurvaMet', 'verMet', 'stdVerMet', 'curValMet', 'genHidMet')
        );
    }
    public function setPlantillaDetalleMetales(Request $res)
    {
        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $res->id)->first();
        $temp = Bitacoras::where('Id_lote', $res->id)->get();
        if ($temp->count()) {
            $model = Bitacoras::where('Id_lote', $res->id)->first();
            $model->Titulo = $res->titulo;
            $model->Texto = $res->texto;
            $model->Rev = $res->rev;
            $model->save();
            $aux = "Mod";
        } else {
            $model = Bitacoras::create([
                'Id_lote' => $res->id,
                'Id_parametro' => $lote->Id_tecnica,
                'Titulo' => $res->titulo,
                'Texto' => $res->texto,
                'Rev' => $res->rev,
            ]);
            $aux = "Cre";
        }
        $data = array(
            'aux' => $aux,
            'temp' => $temp,
            'lote' => $lote,
            'model' => $model,
        );
        return response()->json($data);
    }

    public function exportPdfCaptura($id)
    {
        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => "L",
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 50,
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

        $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $id)->first();
        // $loteVal = LoteAnalisis::where('Id_lote', $id)->first();
        $model = DB::table('ViewLoteDetalle')->where('Id_lote', $id)->where('Id_control',1)->get();
        $tecnica = Tecnica::where('Id_tecnica',$model[0]->Id_tecnica)->first();
        $tipoFormula = TipoFormula::where('Id_tipo_formula',$model[0]->Id_tipo_formula)->first();
        $solModel = Solicitud::where('Id_solicitud',$model[0]->Id_analisis)->first();
        $controles = DB::table('ViewLoteDetalle')->where('Id_lote', $id)->where('Id_control','!=',1)->get();
        $plantilla = Bitacoras::where('Id_lote', $id)->get();
        $swValResidual = false;
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', $lote->Id_tecnica)->get();
        }
        $fechaResidual = \Carbon\Carbon::parse(@$lote->Fecha)->format('Y-m-d');
        $fechaLimite = \Carbon\Carbon::parse("2024-01-08")->format('Y-m-d');
        if ($fechaResidual <= $fechaLimite) {
            $sw = false;
        }else{
            $sw = true;
        }

        $curva = CurvaConstantes::where('Id_parametro', $lote->Id_tecnica)->where('Fecha_inicio', '<=', $lote->Fecha)->where('Fecha_fin', '>=', $lote->Fecha)->first();
        $estandares = estandares::where('Id_parametro', $lote->Id_tecnica)->whereDate('Fecha_inicio','<=',$lote->Fecha)->whereDate('Fecha_fin','>=',$lote->Fecha)->get();
        $detalle = MetalesDetalle::where('Id_lote',$id)->first();
        //Comprobación de bitacora analizada
        $comprobacion = LoteDetalle::where('Liberado', 0)->where('Id_lote', $id)->get();
        if ($comprobacion->count()) {
            $analizo = "";
        } else {
            @$analizo = User::where('id', $model[0]->Analizo)->first();
        }
        switch ($solModel->Id_norma) {
            case 7:
                $fechaHora = Carbon::parse(@$detalle->Fecha_preparacion);
                $fechaPreparacion = Carbon::parse(@$detalle->Fecha_preparacion);
                $hora = $fechaHora->isoFormat('h:mm A');
                $today = @$fechaHora->toDateString();        
                break;
            default:
                $fechaHora = Carbon::parse(@$detalle->Fecha_digestion);
                $fechaPreparacion = Carbon::parse(@$detalle->Fecha_preparacion);
                $hora = $fechaHora->isoFormat('h:mm A');
                $today = $fechaHora->toDateString();    
                break;
        }
 
        $reviso = User::where('id', @$lote->Id_superviso)->first();

        $data = array(
            'sw' => $sw,
            'fechaPreparacion' => $fechaPreparacion,
            'solModel' => $solModel,
            'tipoFormula' => $tipoFormula,
            'tecnica' => $tecnica,
            'controles' => $controles,
            'estandares' => $estandares,
            'detalle' => $detalle,
            'lote' => $lote,
            'hora' =>$hora,
            'fechaHora' => $fechaHora,
            'model' => $model,
            'curva' => $curva,
            'plantilla' => $plantilla,
            'analizo' => $analizo,
            'reviso' => $reviso,
            'comprobacion' => $comprobacion,
        );

        switch ($lote->Id_tecnica) {
            case 20:
            case 21:
            case 23:
            case 18:
            case 24:
            case 25:
            case 232:
            case 187:
            case 226:
            case 197:
            case 351:
            case 41:
            case 354:
            case 353:
            case 55:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.flamaResidual.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.flamaResidual.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.flamaResidual.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();
                break;
                // 051 como horno de grafito a 4 decimales
            case 355:
            case 356:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.hornoResidual.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.hornoResidual.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.hornoResidual.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();
                break;
            case 22:
            case 17:
            case 352:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.hg22.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.hg22.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.hg22.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();
                break;
            case 188:
            case 219:
            case 219:
            case 195:
            case 230:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.201Hidruros.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.201Hidruros.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.201Hidruros.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();
                break;
                // 201 Flama
            case 191:
            case 194:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.201Flama.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.201Flama.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.201Flama.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();    
                break;
            case 189:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.201Ba.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.201Ba.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.201Ba.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();    
                break;
                // Horno de grafito 201
            case 192:
            case 204:
            case 190:
            case 196:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.201Horno.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.201Horno.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.201Horno.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();    
                break;
            case 216:
            case 208:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.127Horno.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.127Horno.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.127Horno.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();    
                break;
            case 210:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.127Cd.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.127Cd.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.127Cd.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();                    
                break;
            case 215:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.127Hidruros.capturaFooter', $data); 
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.127Hidruros.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.127Hidruros.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();    
                break;
            default:
                $htmlFooter = view('exports.laboratorio.metales.absorcion.capturaFooter', $data);
                $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
                $htmlHeader = view('exports.laboratorio.metales.absorcion.capturaHeader', $data);
                $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
                $htmlCaptura = view('exports.laboratorio.metales.absorcion.capturaBody', $data);
                $mpdf->CSSselectMedia = 'mpdf';
                $mpdf->WriteHTML($htmlCaptura);
                $mpdf->Output();
                break;
        }
       
    }

    // todo ************************************************
    // todo Fin de Lote
    // todo ************************************************

    public function createLoteIcp(Request $res)
    {
        $model = LoteAnalisis::create([
            'Id_area' => 17,
            'Id_tecnica' => 2,
            'Asignado' => 0,
            'Liberado' => 0,
            'Fecha' => $res->fecha,
        ]);
        $data = array(
            'model' => $model, 
        );

        return response()->json($data);
    }
    public function buscarLoteIcp(Request $res)
    {
        $sw = false;
        $model = DB::table('ViewLoteAnalisis')->where('Id_area', 17)->where('Fecha', $res->fecha)->get();
        if ($model->count()) {
            $sw = true;
        }

        $data = array(
            'model' => $model,
            'sw' => $sw, 
        );
        return response()->json($data);
    }
    public function importCvs(Request $res)
    {
        // $model = DB::table('lote_detalle_icp')->where('Id_lote',$res->idLote)->get();
        // if ($model->count()) {
        //     $model = DB::table('lote_detalle_icp')->where('Id_lote',$res->idLote)->delete();
        // }
        TempIcp::create([
            'Temp' => $res->idLote,
        ]);
        $data = array("data" => $res->idLote);
        Excel::import(new IcpImport,$res->file('file')); 
        return response()->json($data);
    }
    public function getLoteCapturaIcp(Request $res) 
    {
        $model = LoteDetalleIcp::where('Id_lote',$res->idLote)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function liberarIcp(Request $res)
    {
        $model = LoteDetalleIcp::where('Id_lote',$res->id)->where('Id_control',1)->get();
        foreach ($model as $item) {
            switch ($item->Id_parametro) {
                case 207:// Al
                case 212://Cr
                case 300://Ni
                case 209://Ba    
                case 211://Cu
                case 214://Mn
                case 233://Se
                case 217://Ag
                    if ($item->Dilucio < 30) {
                        $temp = CodigoParametros::where('Codigo',$item->Id_codigo)->where('Id_parametro',$item->Id_parametro)->first();
                        $temp->Resultado = $item->Resultado;
                        $temp->Resultado2 = $item->Resultado;
                        $temp->Analizo = Auth::user()->id;
                        $temp->save();
                        $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                        $temp2->Liberado = 1;
                        $temp2->save();
                    }else{
                        if ($item->Dilucion >= 30 && $item->Dilucion <= 390) {
                            $temp = CodigoParametros::where('Codigo',$item->Id_codigo)->where('Id_parametro',$item->Id_parametro)->first();
                            $temp->Resultado = $item->Resultado;
                            $temp->Resultado2 = $item->Resultado;
                            $temp->Analizo = Auth::user()->id;
                            $temp->save();
                            $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                            $temp2->Liberado = 1;
                            $temp2->save();
                        }else{
                            $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                            $temp2->Liberado = 1;
                            $temp2->save();
                        }
                    }
                    break;
                case 213:// Fe
                    if ($item->Dilucio < 30) {
                        $temp = CodigoParametros::where('Codigo',$item->Id_codigo)->where('Id_parametro',$item->Id_parametro)->first();
                        $temp->Resultado = $item->Resultado;
                        $temp->Resultado2 = $item->Resultado;
                        $temp->Analizo = Auth::user()->id;
                        $temp->save();
                        $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                        $temp2->Liberado = 1;
                        $temp2->save();
                    }else{
                        if ($item->Resultado >= 90 && $item->Resultado <= 1170) {
                            $temp = CodigoParametros::where('Codigo',$item->Id_codigo)->where('Id_parametro',$item->Id_parametro)->first();
                            $temp->Resultado = $item->Resultado;
                            $temp->Resultado2 = $item->Resultado;
                            $temp->Analizo = Auth::user()->id;
                            $temp->save();
                            $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                            $temp2->Liberado = 1;
                            $temp2->save();
                        }else{
                            $temp2 = LoteDetalleIcp::find($item->Id_detalle);
                            $temp2->Liberado = 1;
                            $temp2->save();
                        }
                    }

                    break;
                default:
                    break;
            }

        } 
        $data = array(
            'model' => $model,
        );
        return response()->json($data); 
    }
    public function bitacoraIcp($id)
    {
              //Opciones del documento PDF
              $mpdf = new \Mpdf\Mpdf([  
                'orientation' => 'P',
                'format' => 'letter', 
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 39, 
                'margin_bottom' => 40,
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

            $lote = DB::table('ViewLoteAnalisis')->where('Id_lote', $id)->first();
            $resultados = LoteDetalleIcp::where('Id_lote',$id)->where('Id_control',1)->get();
            $controles = LoteDetalleIcp::where('Id_lote',$id)->where('Id_control',NULL)->get();


            $plantilla = Bitacoras::where('Id_lote', $id)->get();
            if ($plantilla->count()) {
            } else {
                $plantilla = PlantillaBitacora::where('Id_parametro', 207)->get();
            }
            $procedimiento = explode("NUEVASECCION", $plantilla[0]->Texto);
            //Comprobación de bitacora analizada
            $comprobacion = LoteDetalleIcp::where('Liberado', 0)->where('Id_lote', $id)->get();
            if ($comprobacion->count()) {
                $analizo = "";
            } else {
                @$analizo = User::where('id', $resultados[0]->Analizo)->first();
            }
            // $reviso = User::where('id', 46)->first();
            $reviso = User::where('id', @$lote->Id_superviso)->first();


            $data = array(
                'lote' => $lote,
                'comprobacion' => $comprobacion,
                'plantilla' => $plantilla,
                'resultados' => $resultados,
                'controles' => $controles,
                'reviso' => $reviso,
                'analizo' => $analizo,
                // 'textoProcedimiento' => $textoProcedimiento,
            );
            $htmlFooter = view('exports.laboratorio.metales.icp.capturaFooter', $data);
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $htmlHeader = view('exports.laboratorio.metales.icp.capturaHeader', $data);
            $mpdf->setHeader('<p style="text-align:right">{PAGENO} / {nbpg}<br><br></p>' . $htmlHeader);
            $htmlCaptura = view('exports.laboratorio.metales.icp.capturaBody', $data);
            $mpdf->CSSselectMedia = 'mpdf';
            $mpdf->WriteHTML($htmlCaptura);
            $mpdf->Output();
    }
    public function configuracionMetales()
    {
        $parametros = DB::table('ViewParametros')
        ->where('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
        ->orWhere('Id_tipo_formula',58)
        ->orWhere('Id_tipo_formula',59)
        ->get();
        return view('laboratorio.metales.configuracionMetales', compact('parametros'));
    }
    public function getConfiguraciones(Request $res)
    {
        $model = ConfiguracionMetales::where('Id_parametro',$res->id)->get();
        if ($model->count()) {
        }else{
            ConfiguracionMetales::create([
                'Id_parametro' => $res->id,
            ]);
        }
        $model = ConfiguracionMetales::where('Id_parametro',$res->id)->get();
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$res->id)->first();
        $data = array(
            'aux' => "Hola",
            'model' => $model[0],
            'parametro' => $parametro,
        );
        return response()->json($data);
    }
    public function setConfiguraciones(Request $res)
    {
        $model = ConfiguracionMetales::where('Id_parametro',$res->id)->first();
        $model->Equipo = $res->Equipo;
        $model->Lampara = $res->Lampara;
        $model->No_inventario = $res->No_inventario;
        $model->Energia = $res->Energia;
        $model->No_lampara = $res->No_lampara;
        $model->Concentracion = $res->Concentracion;
        $model->Longitud_onda = $res->Longitud_onda;
        $model->Slit = $res->Slit;
        $model->Acetileno = $res->Acetileno;
        $model->Aire = $res->Aire;
        $model->Oxido_nitroso = $res->Oxido_nitroso;
        $model->Hidruros = $res->Hidruros;
        $model->Bitacora_curva = $res->Bitacora_curva;
        $model->Sup_std1 = $res->Sup_std1;
        $model->Sup_std2 = $res->Sup_std2;
        $model->Sup_std3 = $res->Sup_std3;
        $model->Sup_std4 = $res->Sup_std4;
        $model->Sup_std5 = $res->Sup_std5;
        $model->Abs_std1 = $res->Abs_std1;
        $model->Abs_std2 = $res->Abs_std2;
        $model->Abs_std3 = $res->Abs_std3;
        $model->Abs_std4 = $res->Abs_std4;
        $model->Abs_std5 = $res->Abs_std5;
        $model->Inf_std1 = $res->Inf_std1;
        $model->Inf_std2 = $res->Inf_std2;
        $model->Inf_std3 = $res->Inf_std3;
        $model->Inf_std4 = $res->Inf_std4;
        $model->Inf_std5 = $res->Inf_std5;
        $model->save();
        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getHistorial(Request $res)
    {
        $temp = LoteDetalle::where('Id_detalle',$res->idDetalle)->first();
        $solicitud = Solicitud::where('Id_solicitud',$temp->Id_analisis)->first();
        $punto = SolicitudPuntos::where('Id_solicitud',$solicitud->Id_solicitud)->first();
        $solModel = Solicitud::where('Id_cliente',$solicitud->Id_cliente)->where('Id_sucursal',$solicitud->Id_sucursal)
        ->where('Id_solicitud','!=',$solicitud->Id_solicitud)->where('Padre',1)->limit(3)->orderBy('Id_solicitud','DESC')->get();

        $resultado = array();
        $lote = array();
        $fechaLote = array();
        foreach ($solModel as $item) {
            $aux =SolicitudPuntos::where('Id_solPadre',$item->Id_solicitud)->get();
            foreach ($aux as $item2) {
                if ($punto->Id_muestreo == $item2->Id_muestreo) {
                    $codigo = CodigoParametros::where('Id_solicitud',$item2->Id_solicitud)->where('Id_parametro',$temp->Id_parametro)->first();
                    $loteDet = LoteDetalle::where('Id_analisis',$codigo->Id_solicitud)->where('Id_control',1)->first();
                    $loteModel = LoteAnalisis::where('Id_lote',$loteDet->Id_lote)->first();
                    array_push($resultado,$codigo->Resultado2);
                    array_push($lote,$loteDet->Id_lote);
                    array_push($fechaLote,$loteModel->Fecha);
                    
                }
            }
        }
        
        $data = array(
            'solModel' => $solModel,
            'temp' => $temp,
            'resultado' => $resultado,
            'lote' => $lote,
            'fechaLote' => $fechaLote,
        );
        return response()->json($data);
    }
    public function setTituloBit(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote',$res->id)->first();
        $plantilla = PlantillaBitacora::where('Id_parametro',$lote->Id_tecnica)->first();
        $model = Bitacoras::where('Id_lote',$res->id)->first();
        $model->Titulo = $plantilla->Titulo;
        $model->Rev = $plantilla->Rev;
        $model->save();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function actualizarLiberaciones()
    {
        
        $loteTemp = LoteAnalisis::where('Id_area',2)->get();
        foreach ($loteTemp as $item) {
            $model = LoteDetalle::where('Id_lote', $item->Id_lote)->where('Liberado', 1)->get();
            $loteModel = LoteAnalisis::find($item->Id_lote);
            $loteModel->Liberado = $model->count();
            $loteModel->save();
            echo "Id lote: ".$item->Id_lote ."<br>";
        }

    }
    public function getPlantilla(Request $res)
    {
        $lote = LoteAnalisis::where('Id_lote',$res->id)->first();
        $plantilla = Bitacoras::where('Id_lote', $res->id)->get();
        if ($plantilla->count()) {
        } else {
            $plantilla = PlantillaBitacora::where('Id_parametro', 207)->get();
        }
        $data = array(
            'plantilla' => $plantilla,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    { 
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
                'Id_parametro' => 207,
                'Titulo' => $res->titulo,
                'Texto' => $res->texto,
                'Rev' => $res->rev,
            ]);
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setObsIndividual(Request $res)
    {
        $punto = SolicitudPuntos::where('Id_solicitud',$res->idSol)->first();
        $punto->Obs_metales = $res->obs;
        $punto->save();
 
        $data = array(
            'model' => $punto,
        );
        return response()->json($data); 
    }
    public function listaFoliosSinRellenar()
    {
        $model = MetalesDetalle::where('Folio',NULL)->get();
        echo "Lista Total: ".$model->count();
        echo "<br>";
        foreach ($model as $item) {
            echo "<br>";
            echo "Id lote: ".$item->Id_lote;
            echo "<br>";
            echo "----------";
            echo "<br>";
        }
    }
    public function getUltimoLote(Request $res)
    {
        $model = LoteAnalisis::where('Id_tecnica',$res->id)->orderBy('Id_lote','DESC')->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function eliminarContro(Request $res)
    {
        $model = LoteDetalle::where('Id_detalle',$res->idMuestra)->first();
        $msg = "";
        if ($model->Id_control != 1) {
            DB::table('lote_detalle')->where('Id_detalle', $res->idMuestra)->delete();
            $msg = "Control eliminado";
        }else{
            $msg = "No se puede eliminar porque no es un control";
        }

        $contLote = LoteDetalle::where('Id_lote',$model->Id_lote)->get();
        $contLib = LoteDetalle::where('Id_lote',$model->Id_lote)->where('Liberado',1)->get();
        $temp = LoteAnalisis::where('Id_lote',$model->Id_lote)->first();
        $temp->Asignado = $contLote->count();
        $temp->Liberado = $contLib->count();
        $temp->save();

        $data = array(
            'model' => $model,
            'msg' => $msg,
        );
        return response()->json($data);
    }
    
}
