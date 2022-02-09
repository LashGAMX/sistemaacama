<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
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
use App\Models\ControlCalidad;
use App\Models\CurvaCalibracionMet;
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
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleHH;
use App\Models\LoteTecnica;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\Nmp1Micro;
use App\Models\TiempoReflujo;
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
        foreach($solicitudModel as $item)
        {
            $paramModel = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$item->Id_solicitud)->where('Id_tipo_formula',$request->id)->get();
            $sw = false;
            foreach($paramModel as $item2)
            {
                $areaModel = DB::table('ViewTipoFormulaAreas')->where('Id_formula',$item2->Id_tipo_formula)->where('Id_area',13)->get();
                if($areaModel->count())
                {
                    $sw = true;
                }
            }
            if($sw == true)
            {
                // $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Id_analisis',$item->Id_solicitud)->get();
                $model = ObservacionMuestra::where('Id_analisis',$item->Id_solicitud)->where('Id_area',13)->get();
                if($model->count()){
                }else{
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
        $model = DB::table('ViewObservacionMuestra')->where('Id_area',13)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }  


    public function aplicarObservacion(Request $request)
    {
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Folio','LIKE',"%{$request->folioActual}%")->first();

        $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
        $observacion->Ph = $request->ph;
        
        $observacion->Solido = $request->solidos;
        $observacion->Olor = $request->olor;
        $observacion->Color = $request->color;
        $observacion->Observaciones = $request->observacionGeneral;
        $observacion->save();


        $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->get();

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
    public function capturaMicro(){
        $parametro = Parametro::where('Id_area', 6)->get();
        return view('laboratorio.mb.captura', compact('parametro'));
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
        $detalle = array();
        switch ($loteModel->Id_tecnica) {
            case 13: //todo Coliformes+
                $detalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 262: //todo  ENTEROCOCO FECAL
                    # code...
                    $detalle = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 72: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        # code...
                    $detalle = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 17: //todo Huevos de Helminto 
                    # code...
                    $detalle = DB::table('ViewLoteDetalleHH')->where('Id_lote', $request->idLote)->get(); 
                break;
            default:
                # code...
                break;
        }
   
        $data = array(
            'detalle' => $detalle,
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

    // public function setControlCalidad(Request $request)
    // {
  

    // }
    public function operacion(Request $request)
    {
        
        switch ($request->idParametro) {
            case 13: //todo Número más probable (NMP), en tubos múltiples
                    // $nmp1 = $request->G1*100;
                    // $nmp2 = $request->G2*$request->G3;
                    // $nmp3 = sqrt($nmp2);
                    // $nmp = $nmp1 / $nmp3;

                    // $res = (10/$request->D1)*$nmp;
                    $n1 = $request->con1 + $request->con2 +$request->con3;
                    $n2 = $request->con4 + $request->con5 +$request->con6;
                    $n3 = $request->con7 + $request->con8 +$request->con9;

                    $numModel = Nmp1Micro::where('Col1',$n1)->where('Col2',$n2)->where('Col3',$n3)->first();
                    $res = $numModel->Nmp;

                    // $model = LoteDetalleColiformes::find($request->idDetalle)->replicate();
                    // $model->Id_control = 2;
                    // $model->save();
                    
                break;
                case 262: //todo Número más probable (NMP), en tubos múltiples
           
                    $n1 = $request->con1 + $request->con2 +$request->con3;
                    $n2 = $request->con4 + $request->con5 +$request->con6;
                    $n3 = $request->con7 + $request->con8 +$request->con9;

                    $numModel = Nmp1Micro::where('Col1',$n1)->where('Col2',$n2)->where('Col3',$n3)->first();
                    $res = $numModel->Nmp;

                    // $model = LoteDetalleColiformes::find($request->idDetalle)->replicate();
                    // $model->Id_control = 2;
                    // $model->save();
                    
                break;
            case 72: //todo Metodo electrometrico
                    # code...
                    $E = $request->D / $request->C;
                    $res = ($request->A - $request->B) / round($E,3); 
                   
                break;
            case 17: //todo Flotación de huevos de helminto
                        # code...
                    $suma = $request->lum1 + $request->na1 + $request->sp1 + $request->tri1 + $request->uni1;
                    $res = round($suma/$request->volH1);
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'res' => $res, 
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
        $parametro = DB::table('ViewParametros')
        ->orWhere('Id_area', 6)
        ->get();

        $textoRecuperadoPredeterminado = ReportesMb::where('Id_lote', 0)->first();
        return view('laboratorio.mb.lote', compact('parametro', 'textoRecuperadoPredeterminado'));
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
        $reporte = ReportesMb::where('Id_lote',$request->idLote)->first();
        
        //RECUPERA EL APARTADO DE FÓRMULAS GLOBALES;
        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();
        if($constantesModel->count()){
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();            
        }else{
            $constantes = null;
        }

        /*Módulo de Grasas*/
        $dataGrasas = array();

        $calentamientoFq = DB::table('calentamiento_matraces')->where('Id_lote', $request->idLote)->get();
        $enfriadoFq = DB::table('enfriado_matraces')->where('Id_lote', $request->idLote)->get();
        $secadoFq = DB::table('secado_cartuchos')->where('Id_lote', $request->idLote)->get();
        $tiempoFq = DB::table('tiempo_reflujo')->where('Id_lote', $request->idLote)->get();
        $enfriadoMatrazFq = DB::table('enfriado_matraz')->where('Id_lote', $request->idLote)->get();

        if($calentamientoFq->count()){            
            array_push($dataGrasas, $calentamientoFq);
        }else{
            array_push($dataGrasas, null);
        }

        if($enfriadoFq->count()){            
            array_push($dataGrasas, $enfriadoFq);
        }else{
            array_push($dataGrasas, null);
        }

        if($secadoFq->count()){
            $secadoCartucho = SecadoCartucho::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $secadoCartucho);
        }else{
            array_push($dataGrasas, null);
        }

        if($tiempoFq->count()){
            $tiempoReflujo = TiempoReflujo::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $tiempoReflujo);
        }else{
            array_push($dataGrasas, null);
        }

        if($enfriadoMatrazFq->count()){
            $enfriadoMatraz = EnfriadoMatraz::where('Id_lote', $request->idLote)->first();
            array_push($dataGrasas, $enfriadoMatraz);
        }else{
            array_push($dataGrasas, null);
        }


        /* Módulo de coliformes */
        $dataColi = array();

        $sembradoFq = DB::table('sembrado_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaPresuntivaFq = DB::table('prueba_presuntiva_fq')->where('Id_lote', $request->idLote)->get();
        $pruebaConfirmativaFq = DB::table('prueba_confirmativa_fq')->where('Id_lote', $request->idLote)->get();

        if($sembradoFq->count() && $pruebaPresuntivaFq->count() && $pruebaConfirmativaFq->count()){
            $sembradoFq = SembradoFq::where('Id_lote', $request->idLote)->first(); //Array 0
            $pruebaPresunFq = PruebaPresuntivaFq::where('Id_lote', $request->idLote)->first(); //Array 1
            $pruebaConfirFq = PruebaConfirmativaFq::where('Id_lote', $request->idLote)->first(); //Array 2

            array_push(
                $dataColi,
                $sembradoFq,
                $pruebaPresunFq,
                $pruebaConfirFq
            );

        }else{
            array_push(
                $dataColi, null, null, null
            );
        }
        //-------------------------------------

        /* Módulo DQO */                
        $dqoModel = DB::table('dqo_fq')->where('Id_lote', $request->idLote)->get();

        if($dqoModel->count()){
            $dqo = DqoFq::where('Id_lote', $request->idLote)->first();
        }else{
            $dqo = null;
        }


        //-------------------------------------
        $data = array(
            'curvaConstantes' => $constantes,
            'idLoteIf' => $idLoteIf,
            'reporte' => $reporte,
            'dataGrasas' => $dataGrasas,
            'dataColi' => $dataColi,
            'dataDqo' => $dqo
        );

        return response()->json($data);
    }

    public function getPlantillaPred(Request $request){

        //Obtiene el parámetro que se está consultando
        $parametro = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->first();
        
        if($parametro->Parametro == 'COLIFORMES FECALES'){
            $plantillaPredeterminada = ReportesFq::where('Id_reporte', 1)->first();
        }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO CON INOCULO (DBO5)'){
            $plantillaPredeterminada = ReportesFq::where('Id_reporte', 2)->first();
        }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
            $plantillaPredeterminada = ReportesFq::where('Id_reporte', 3)->first();
        }else if($parametro->Parametro == 'OXIGENO DISUELTO'){
            $plantillaPredeterminada = ReportesFq::where('Id_reporte', 4)->first();
        }else if($parametro->Parametro == 'HUEVOS DE HELMINTO'){
            $plantillaPredeterminada = ReportesMb::where('Id_reporte', 0)->first();
        }else{
            $plantillaPredeterminada = ReportesFq::where('Id_reporte', 0)->first();
        }        
        
        return response()->json($plantillaPredeterminada);
    }

    /* public function getPlantillaPred(Request $request){
        $plantillaPredeterminada = ReportesMb::where('Id_lote', $request->idLote)->first();
        return response()->json($plantillaPredeterminada);
    } */

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
        $model = DB::table('ViewSolicitudParametros')
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
            case 13: //todo Coliformes+
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 262: //todo  ENTEROCOCO FECAL
                    # code...
                    $model = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 72: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        # code...
                    $model = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 17: //todo Huevos de Helminto 
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
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 17: //todo Espectrofotometria
                $detModel = DB::table('lote_detalle_espectro')->where('Id_detalle',$request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
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

 
        $solModel = SolicitudParametro::where('Id_solicitud',$request->idSol)->where('Id_subnorma',$request->idParametro)->first();
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
            case 13: //todo Número más probable (NMP), en tubos múltiples
                $model = LoteDetalleColiformes::create([
                                'Id_lote' => $request->idLote,
                                'Id_analisis' => $request->idAnalisis,
                                'Id_parametro' => $loteModel->idParametro,
                                'Id_control' => 1,
                            ]);
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                $sw = true;
                break;
            case 52: //todo Número más probable (NMP), en tubos múltiples
                    $model = LoteDetalleColiformes::create([
                                    'Id_lote' => $request->idLote,
                                    'Id_analisis' => $request->idAnalisis,
                                    'Id_parametro' => $loteModel->idParametro,
                                    'Id_control' => 1,
                                ]);
                    $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                    $sw = true;
                    break;
            case 262: //todo  ENTEROCOCO FECAL
                    # code...
                    $model = LoteDetalleColiformes::create([
                        'Id_lote' => $request->idLote,
                        'Id_analisis' => $request->idAnalisis,
                        'Id_parametro' => $loteModel->idParametro,
                        'Id_control' => 1,
                    ]);
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                $sw = true;
                break;
            case 72: //todo DEMANDA BIOQUIMICA DE OXIGENO (DBO5) 
                        # code...
                        $model = LoteDetalleDbo::create([
                            'Id_lote' => $request->idLote,
                            'Id_analisis' => $request->idAnalisis,
                            'Id_parametro' => $loteModel->idParametro,
                            'Id_control' => 1,
                        ]);
                        $detModel = LoteDetalleDbo::where('Id_lote',$request->idLote)->get();
                        $sw = true;
                break;
            case 17: //todo Huevos de Helminto 
                    # code...
                    $model = LoteDetalleHH::create([
                        'Id_lote' => $request->idLote,
                        'Id_analisis' => $request->idAnalisis,
                        'Id_parametro' => $loteModel->idParametro,
                        'Id_control' => 1,
                ]);
                $detModel = LoteDetalleHH::where('Id_lote',$request->idLote)->get();
                $sw = true;
                break;
            default:
                # code...
                $sw  = false;
                break;
        }
     
            $solModel = SolicitudParametro::find($request->idSol);
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


    //*************************************GUARDA LOS DATOS DE LA VENTANA MODAL EN MÓDULO LOTE, PESTAÑA EQUIPO************* */
    public function guardarDatos(Request $request){

        //****************************************************GRASAS*****************************************************************        
        //calentamiento_matraces
        $calentamientoFqModel = CalentamientoMatraz::where('Id_lote', $request->idLote)->get();

        if($calentamientoFqModel->count()){
            for($i = 0; $i < 3; $i++){
                $calentamientoMatraz = CalentamientoMatraz::find($calentamientoFqModel[$i]->Id_calentamiento);

                $calentamientoMatraz->Id_lote = $request->grasas_calentamiento[$i][0];
                $calentamientoMatraz->Masa_constante = $request->grasas_calentamiento[$i][1];
                $calentamientoMatraz->Temperatura = $request->grasas_calentamiento[$i][2];
                $calentamientoMatraz->Entrada = $request->grasas_calentamiento[$i][3];
                $calentamientoMatraz->Salida = $request->grasas_calentamiento[$i][4];
                $calentamientoMatraz->Id_user_m = Auth::user()->id;

                $calentamientoMatraz->save();
            }
        }else{
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

        if($enfriadoFqModel->count()){
            for($i = 0; $i < 3; $i++){
                $enfriadoMatraz = EnfriadoMatraces::find($enfriadoFqModel[$i]->Id_enfriado);
                $enfriadoMatraz->Id_lote = $request->grasas_enfriado[$i][0];
                $enfriadoMatraz->Masa_constante = $request->grasas_enfriado[$i][1];
                $enfriadoMatraz->Entrada = $request->grasas_enfriado[$i][2];
                $enfriadoMatraz->Salida = $request->grasas_enfriado[$i][3];
                $enfriadoMatraz->Pesado_matraz = $request->grasas_enfriado[$i][4];
                $enfriadoMatraz->Id_user_m = Auth::user()->id;

                $enfriadoMatraz->save();
            }            
        }else{
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

        if($secadoFqModel->count()){
            $secadoCartucho = SecadoCartucho::find($secadoFqModel[0]->Id_secado);
            $secadoCartucho->Id_lote = $request->grasas_secadoLote;
            $secadoCartucho->Temperatura = $request->grasas_secadoTemp;
            $secadoCartucho->Entrada = $request->grasas_secadoEntrada;
            $secadoCartucho->Salida = $request->grasas_secadoSalida;
            $secadoCartucho->Id_user_m = Auth::user()->id;

            $secadoCartucho->save();
        }else{
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
        
        if($tiempoFqModel->count()){
            $tiempoReflujo = TiempoReflujo::find($tiempoFqModel[0]->Id_tiempo);
            $tiempoReflujo->Id_lote = $request->grasas_tiempoLote;
            $tiempoReflujo->Entrada = $request->grasas_tiempoEntrada;
            $tiempoReflujo->Salida = $request->grasas_tiempoSalida;
            $tiempoReflujo->Id_user_m = Auth::user()->id;

            $tiempoReflujo->save();
        }else{
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

        if($enfriadoFqModel->count()){
            $enfriadoMatraz2 = EnfriadoMatraz::find($enfriadoFqModel[0]->Id_enfriado);
            $enfriadoMatraz2->Id_lote = $request->grasas_enfriadoLote;
            $enfriadoMatraz2->Entrada = $request->grasas_enfriadoEntrada;
            $enfriadoMatraz2->Salida = $request->grasas_enfriadoSalida;
            $enfriadoMatraz2->Id_user_m = Auth::user()->id;

            $enfriadoMatraz2->save();
        }else{
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
            compact('sembradoFqModel', 'pruebaPresuntivaModel','pruebaConfirmativaModel', 'dqoModel')
        );
    }

    // todo ************************************************
    // todo Fin de Lote
    // todo ************************************************

    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF; DE MOMENTO NO RECIBE UN IDLOTE
    public function exportPdfCaptura($idLote)
    {        
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
        if(!is_null($fechaAnalisis)){
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
        }else{
            $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            echo '<script> alert("Valores predeterminados para la fecha de análisis. Rellena este campo.") </script>';
        }                           
        
        //Recupera el parámetro que se está utilizando****************************
        $parametro = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->first();

        if(is_null($parametro)){
            $parametro = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->first();

            if(!is_null($parametro)){
                $bandera = 'hh';
            }else{
                $parametro = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->first();
                if(!is_null($parametro)){
                    $bandera = 'dbo';
                }
            }
        }else{
            $bandera = 'coli';
        }        
        //************************************************************************        
  
        //Recupera el texto dinámico Procedimientos de la tabla reportes****************************************************
        $textProcedimiento = ReportesMb::where('Id_lote', $id_lote)->first();
        $proced = false;
        if(!is_null($textProcedimiento)){
            $proced = true;
            if($bandera === 'coli'){
                if($parametro->Parametro == 'COLIFORMES FECALES' || $parametro->Parametro == 'Coliformes Fecales +'){                    
                    $horizontal = 'P';                    
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    //Formatea la fecha
                    $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
                    if(!is_null($fechaAnalisis)){
                        $parametroDeterminar = $fechaAnalisis->Parametro;
                        $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
                        $hora = date("h:j:s", strtotime($fechaAnalisis->created_at));
                    }
     
                    if(!is_null($data)){                                             
                        $resultadosPresuntivas = array();
                        $resultadosConfirmativas = array();

                        foreach($data as $item){
                            array_push(
                                $resultadosPresuntivas,
                                $item->Presuntiva1 + $item->Presuntiva2 + $item->Presuntiva3,
                                $item->Presuntiva4 + $item->Presuntiva5 + $item->Presuntiva6,
                                $item->Presuntiva7 + $item->Presuntiva8 + $item->Presuntiva9,
                            );

                            array_push(
                                $resultadosConfirmativas,
                                $item->Confirmativa1 + $item->confirmativa2 + $item->confirmativa3,
                                $item->confirmativa4 + $item->confirmativa5 + $item->confirmativa6,
                                $item->confirmativa7 + $item->confirmativa8 + $item->confirmativa9,
                            );
                        }
        
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);                        
                        
                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();                        
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'resultadosPresuntivas', 'resultadosConfirmativas'));

                    }else{
                        $sw = false;                        
                    }                                                            
                }else if($parametro->Parametro == 'COLIFORMES TOTALES'){    
                    $horizontal = 'P';                    
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();

                    //Formatea la fecha
                    $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
                    if(!is_null($fechaAnalisis)){
                        $parametroDeterminar = $fechaAnalisis->Parametro;
                        $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
                        $hora = date("h:j:s", strtotime($fechaAnalisis->created_at));
                    }
     
                    if(!is_null($data)){                                             
                        $resultadosPresuntivas = array();
                        $resultadosConfirmativas = array();

                        foreach($data as $item){
                            array_push(
                                $resultadosPresuntivas,
                                $item->Presuntiva1 + $item->Presuntiva2 + $item->Presuntiva3,
                                $item->Presuntiva4 + $item->Presuntiva5 + $item->Presuntiva6,
                                $item->Presuntiva7 + $item->Presuntiva8 + $item->Presuntiva9,
                            );

                            array_push(
                                $resultadosConfirmativas,
                                $item->Confirmativa1 + $item->confirmativa2 + $item->confirmativa3,
                                $item->confirmativa4 + $item->confirmativa5 + $item->confirmativa6,
                                $item->confirmativa7 + $item->confirmativa8 + $item->confirmativa9,
                            );
                        }
        
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();

                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);                        
                        
                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();                        
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'resultadosPresuntivas', 'resultadosConfirmativas'));

                    }else{
                        $sw = false;                        
                    }
                }
            }else if($bandera === 'hh'){
                if($parametro->Parametro == 'HUEVOS DE HELMINTO' || $parametro->Parametro == 'Huevos de Helminto'){ //POR REVISAR EN LA TABLA DE DATOS
                    $horizontal = 'P';                    
                    $data = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 

                        $controles = array();
                        $controlesCalidad = array();

                        foreach($data as $item){                            
                            array_push(
                                $controles,
                                $item->Id_control,                                
                            );                            
                        }

                        foreach($controles as $item){
                            array_push(
                                $controlesCalidad,
                                ControlCalidad::where('Id_control', $item)->first()
                            );
                        }

                        $dataLength = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->count();                        
                        
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);  
                        
                        $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'controlesCalidad'));

                    }else{
                        $sw = false;                        
                    }
                }
            }else if($bandera === 'enteroCoco'){    //NO EXISTE BITÁCORA AÚN POR SER UN PARÁMETRO NUEVO
                if($parametro->Parametro == 'ENTEROCOCO FECAL'){ //POR REVISAR EN LA TABLA DE DATOS
                    $horizontal = 'P';                    
                    $data = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 
                        $dataLength = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->count();                        
                        $htmlCaptura = view('exports.laboratorio.mb.enteroC.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;                        
                    }                                        
                }
            }else if($bandera === 'dbo'){
                if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();               
                        
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                        
                        $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;                        
                    }                
                }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO CON INOCULO (DBO5)'){
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();               
                        
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);   
                        
                        $htmlCaptura = view('exports.laboratorio.mb.dboIn.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;                        
                    }                
                }
            }                                                     
        }else{  //----------------------
            if($bandera === 'coli'){
                if($parametro->Parametro == 'COLIFORMES FECALES' || $parametro->Parametro == 'Coliformes Fecales +'){                    
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 
                        //Formatea la fecha
                        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
                        if(!is_null($fechaAnalisis)){
                            $parametroDeterminar = $fechaAnalisis->Parametro;
                            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
                            $hora = date("h:i:s", strtotime($fechaAnalisis->created_at));
                        }
                        
                        $textProcedimiento = ReportesMb::where('Id_reporte', 1)->first();                        
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                        
                        $resultadosPresuntivas = array();
                        $resultadosConfirmativas = array();

                        foreach($data as $item){
                            array_push(
                                $resultadosPresuntivas,
                                $item->Presuntiva1 + $item->Presuntiva2 + $item->Presuntiva3,
                                $item->Presuntiva4 + $item->Presuntiva5 + $item->Presuntiva6,
                                $item->Presuntiva7 + $item->Presuntiva8 + $item->Presuntiva9,
                            );

                            var_dump($resultadosPresuntivas);                            

                            array_push(
                                $resultadosConfirmativas,
                                $item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa3,
                                $item->Confirmativa4 + $item->Confirmativa5 + $item->Confirmativa6,
                                $item->Confirmativa7 + $item->Confirmativa8 + $item->Confirmativa9,
                            );

                            var_dump($resultadosConfirmativas);
                        }
                        
                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();  
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();
                        
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'resultadosPresuntivas', 'resultadosConfirmativas'));

                    }else{
                        $sw = false;
                    }
                }else if($parametro->Parametro == 'COLIFORMES TOTALES'){
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                                                 
                        //Formatea la fecha
                        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
                        if(!is_null($fechaAnalisis)){
                            $parametroDeterminar = $fechaAnalisis->Parametro;
                            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
                            $hora = date("h:i:s", strtotime($fechaAnalisis->created_at));
                        }
                        
                        $textProcedimiento = ReportesMb::where('Id_reporte', 5)->first();                        
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                        
                        $resultadosPresuntivas = array();
                        $resultadosConfirmativas = array();

                        foreach($data as $item){
                            array_push(
                                $resultadosPresuntivas,
                                $item->Presuntiva1 + $item->Presuntiva2 + $item->Presuntiva3,
                                $item->Presuntiva4 + $item->Presuntiva5 + $item->Presuntiva6,
                                $item->Presuntiva7 + $item->Presuntiva8 + $item->Presuntiva9,
                            );

                            var_dump($resultadosPresuntivas);                            

                            array_push(
                                $resultadosConfirmativas,
                                $item->Confirmativa1 + $item->Confirmativa2 + $item->Confirmativa3,
                                $item->Confirmativa4 + $item->Confirmativa5 + $item->Confirmativa6,
                                $item->Confirmativa7 + $item->Confirmativa8 + $item->Confirmativa9,
                            );

                            var_dump($resultadosConfirmativas);
                        }
                        
                        $dataLength = DB::table('ViewLoteDetalleColiformes')->where('Id_lote', $id_lote)->count();  
                        //Recupera el campo Resultado
                        $loteColi = LoteDetalleColiformes::where('Id_lote', $id_lote)->get();
                        
                        $htmlCaptura = view('exports.laboratorio.mb.coliformes.capturaBody', compact('textoProcedimiento', 'loteColi', 'data', 'dataLength', 'fechaConFormato', 'hora', 'parametroDeterminar', 'resultadosPresuntivas', 'resultadosConfirmativas'));

                    }else{
                        $sw = false;
                    }
                }
            }else if($bandera === 'hh'){ 
                if($parametro->Parametro == 'HUEVOS DE HELMINTO' || $parametro->Parametro == 'Huevos de Helminto'){                
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->get();
        
                    if(!is_null($data)){                                                 
                        $textProcedimiento = ReportesMb::where('Id_reporte', 0)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $controles = array();
                        $controlesCalidad = array();

                        foreach($data as $item){                            
                            array_push(
                                $controles,
                                $item->Id_control,                                
                            );                            
                        }

                        foreach($controles as $item){
                            array_push(
                                $controlesCalidad,
                                ControlCalidad::where('Id_control', $item)->first()
                            );
                        }
                        
                        $dataLength = DB::table('ViewLoteDetalleHH')->where('Id_lote', $id_lote)->count();                        
                        $htmlCaptura = view('exports.laboratorio.mb.hh.capturaBody', compact('textoProcedimiento', 'data', 'dataLength', 'controlesCalidad'));

                    }else{
                        $sw = false;
                    }
                }
            }else if($bandera === 'enteroCoco'){ //NO EXISTE AÚN BITÁCORA DEBIDO A QUE ES NUEVO PARÁMETRO
                if($parametro->Parametro == 'ENTEROCOCO FECAL'){ //POR REVISAR EN LA TABLA DE DATOS
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->get();
        
                    if(!is_null($data)){                                                 
                        $textoProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                        $dataLength = DB::table('ViewLoteDetalleMicro')->where('Id_lote', $id_lote)->count();                        
                        $htmlCaptura = view('exports.laboratorio.mb.enteroC.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;
                    }
                }
            }else if($bandera === 'dbo'){
                if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
                    $horizontal = 'P';
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                        
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();
                        
                        $textProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                        
                        $htmlCaptura = view('exports.laboratorio.mb.dbo.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;                        
                    }                                                
                }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO CON INOCULO (DBO5)'){
                    $horizontal = 'P';                
                    $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();
     
                    if(!is_null($data)){                        
                        $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();
                        $textProcedimiento = ReportesMb::where('Id_reporte', 2)->first();
                        $separador = "Valoración";
                        $textoProcedimiento = explode($separador, $textProcedimiento->Texto);

                        $htmlCaptura = view('exports.laboratorio.mb.dboIn.capturaBody', compact('textoProcedimiento', 'data', 'dataLength'));

                    }else{
                        $sw = false;                        
                    }
                }
            }                        
         }   
 
         //HEADER-FOOTER******************************************************************************************************************         
               
        if($parametro->Parametro == 'COLIFORMES FECALES' || $parametro->Parametro == 'Coliformes Fecales +'){
            $htmlHeader = view('exports.laboratorio.mb.coliformes.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.coliformes.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'COLIFORMES TOTALES'){
            $htmlHeader = view('exports.laboratorio.mb.espectro.coliformesTotales.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.espectro.coliformesTotales.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'HUEVOS DE HELMINTO' || $parametro->Parametro == 'Huevos de Helminto'){
            $htmlHeader = view('exports.laboratorio.mb.hh.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.hh.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'ENTEROCOCO FECAL'){ //POR REVISAR EN LA TABLA DE DATOS
            $htmlHeader = view('exports.laboratorio.mb.espectro.condElec.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.espectro.condElec.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
            $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', compact('fechaConFormato'));
            $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', compact('usuario', 'firma'));
        }else if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO CON INOCULO (DBO5)'){
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

        if($horizontal == 'L'){
            //Establece la marca de agua del documento PDF
            $mpdf->SetWatermarkImage(
                asset('/public/storage/HojaMembretadaHorizontal.png'),
                1,
                array(215, 280),
                array(0, 0),
            );
        }else{
            //Establece la marca de agua del documento PDF
            $mpdf->SetWatermarkImage(
                asset('/public/storage/MembreteVertical.png'),
                1,
                array(215, 280),
                array(0, 0),
            );
        }

        $mpdf->showWatermarkImage = true;

        if($sw === false){
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }
        
        $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCaptura);
        $mpdf->CSSselectMedia = 'mpdf'; 
        
        //Hoja 2
        $hoja2 = false;
        if($parametro->Parametro == 'DEMANDA BIOQUIMICA DE OXIGENO (DBO5)'){
            $mpdf->AddPage('', '', '', '', '', '', '', 35, 45, 6.5, '', '', '', '', '', -1, -1, -1, -1);
            //$horizontal = 'P';
            $data = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->get();

            if(!is_null($data)){                                                 
                $dataLength = DB::table('ViewLoteDetalleDbo')->where('Id_lote', $id_lote)->count();               
                
                if($proced === true){
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                }else{
                    $textProcedimiento = ReportesMb::where('Id_reporte', 3)->first();
                    $separador = "Valoración";
                    $textoProcedimiento = explode($separador, $textProcedimiento->Texto);
                }                
                
                $htmlHeader = view('exports.laboratorio.mb.dbo.capturaHeader', compact('fechaConFormato'));
                $htmlFooter = view('exports.laboratorio.mb.dbo.capturaFooter', compact('usuario', 'firma'));
                $htmlCaptura1 = view('exports.laboratorio.mb.dbo.capturaBody1', compact('textoProcedimiento', 'data', 'dataLength'));
                $hoja2 = true;

            }else{
                $sw = false;                        
            }                
        }

        if($hoja2 === true){
            $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlHeader, 'O', 'E');
            $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
            $mpdf->WriteHTML($htmlCaptura1);
        }

        $mpdf->Output();
    }
}
  