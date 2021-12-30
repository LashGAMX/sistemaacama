<?php

namespace App\Http\Controllers\laboratorio;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ObservacionMuestra; 
use App\Models\Parametro;
use App\Models\ReportesFq;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales; 
use App\Models\BlancoCurvaMetales;
use App\Models\CalentamientoMatraz;
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
use App\Models\LoteDetalleEspectro;
use App\Models\LoteTecnica;
use App\Models\SecadoCartucho;
use App\Models\Tecnica;
use App\Models\TiempoReflujo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MicroController extends Controller
{
    //
    //
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
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')
        ->orWhere('Id_tipo_formula', 8)
        ->orWhere('Id_tipo_formula',9)
        ->get();
        return view('laboratorio.fq.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        // todo - Area analisis = id 5
        $solicitudModel = DB::table('ViewSolicitud')->get();
        $sw = false;
        foreach($solicitudModel as $item)
        {
            $paramModel = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$item->Id_solicitud)->where('Id_tipo_formula',$request->id)->get();
            $sw = false;
            foreach($paramModel as $item2)
            {
                $areaModel = DB::table('ViewTipoFormulaAreas')->where('Id_formula',$item2->Id_tipo_formula)->where('Id_area',5)->get();
                if($areaModel->count())
                {
                    $sw = true;
                }
            }
            if($sw == true)
            {
                // $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->where('Id_analisis',$item->Id_solicitud)->get();
                $model = ObservacionMuestra::where('Id_analisis',$item->Id_solicitud)->where('Id_area',5)->get();
                if($model->count()){
                }else{
                    ObservacionMuestra::create([
                        'Id_analisis' => $item->Id_solicitud,
                        'Id_area' => 5,
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
        $model = DB::table('ViewObservacionMuestra')->where('Id_area',5)->get();

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

    //*****************************************CAPTURA****************************************************************** */
    public function tipoAnalisis()
    {
        return view('laboratorio.fq.tipoAnalisis');
    }

    public function capturaEspectro()
    {

        $parametro = Parametro::where('Id_area', 5)
            ->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        // var_dump($parametro); 
        return view('laboratorio.fq.capturaEspectro', compact('parametro')); 
    }
    public function getDataCapturaEspectro(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        { 
            $detModel = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $item->Id_lote)->first();
            if($detModel->Id_parametro == $request->formulaTipo)
            { 
                $idLote = $detModel->Id_lote;
            } 
        }

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $idLote)->get();
        $loteModel = DB::table('ViewLoteAnalisis')->where('Id_lote', $idLote)->first();
        $curvaConst = CurvaConstantes::where('Id_lote',$idLote)->first();
        $data = array( 
            'idL' => $idLote,
            'de' => $detModel,
            'lote' => $loteModel,
            'curvaConst' => $curvaConst,
            'detalle' => $detalle,
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

        $detalleModel = LoteDetalle::where('Id_lote',$loteModel->Id_lote)->get();
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

        $detalleModel = LoteDetalle::where('Id_detalle',$request->idDetalle)->first();
        $parametroModel = Parametro::where('Id_matriz',12)->where('Id_parametro',$detalleModel->Id_parametro)->get();
        $curvaConstantes = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        $parametroPurificada = Parametro::where('Id_matriz',9)->where('Id_parametro',$detalleModel->Id_parametro)->get();

        $curva = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        $x = $request->x;
        $y = $request->y;
        $z = $request->z;
        $FD = $request->FD;
        $suma = ($x + $y + $z);
        $promedio = $suma / 3;

        if($parametroPurificada->count()){    //todo:: Verificar filtro con la norma!!!
            $paso1 = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
            $resultado = ($paso1 * 1)/1000;
        }else{

        if($parametroModel->count())
        {
            if($detalleModel->Descripcion != "Resultado"){
                $resultado = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
            }else{
                $resultado = ((($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD) / 1000;
            }
        }else{
            $resultado = (($promedio - $curvaConstantes->B) /$curvaConstantes->M ) * $FD;
        }

        }

        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Vol_muestra = $request->volMuestra;
        $detalle->Abs1 = $request->x;
        $detalle->Abs2 = $request->y;
        $detalle->Abs3 = $request->z;
        $detalle->Abs_promedio = $promedio;
        $detalle->Factor_dilucion = $request->FD;
        $detalle->Factor_conversion = 0;
        $detalle->Vol_disolucion = $resultado;
        $detalle->save();

        $data = array(
            'idDeta' => $request->idDetalle,
            'curva' => $curva,
            'promedio' => $promedio,
            'resultado' => $resultado
        );

        return response()->json($data);
    }

    // todo ******************* Inicio de lote ************************
    public function lote()
    {
        //* Tipo de formulas 
        $formulas = DB::table('tipo_formulas')
        ->orWhere('Id_tipo_formula', 8)
        ->orWhere('Id_tipo_formula',9)
        ->get();
        $tecnica = Tecnica::all();
        $textoRecuperadoPredeterminado = ReportesFq::where('Id_lote', 0)->first();
        return view('laboratorio.fq.lote', compact('formulas', 'textoRecuperadoPredeterminado','tecnica'));
    }

    public function createLote(Request $request) 
    {
        $model = LoteAnalisis::create([
            'Id_tipo' => $request->tipo,
            'Id_area' => 5,
            'Id_tecnica' => $request->tecnica,
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
        //$model = LoteAnalisis::where('Id_tipo',$request->tipo)->where('Fecha',$request->fecha)->get();
        $model = DB::table('ViewLoteAnalisis')->where('Id_tipo', $request->tipo)->where('Id_area',5)->where('Fecha', $request->fecha)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    //RECUPERAR DATOS PARA ENVIARLOS A LA VENTANA MODAL > EQUIPO PARA RELLENAR LOS DATOS ALMACENADOS EN LA BD
    public function getDatalote(Request $request)
    {        
        $idLoteIf = $request->idLote;

        //RECUPERA LA PLANTILLA DEL REPORTE
        $reporte = ReportesFq::where('Id_lote',$request->idLote)->first();
        
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
        $plantillaPredeterminada = ReportesFq::where('Id_lote', $request->idLote)->first();
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
        return view('laboratorio.fq.asignarMuestraLote', compact('lote', 'idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')
        ->orWhere('Id_tipo_formula',8)
        ->orWhere('Id_tipo_formula',9)
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
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->get(); 
                break;
            case 10: //todo Gravimetia
                    # code...
                break;
            case 15: //todo Volumetria
                        # code...
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
            case 9: //todo Espectrofotometria
                $detModel = DB::table('lote_detalle_espectro')->where('Id_detalle',$request->idDetalle)->delete();
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                    # code...
                break;
            case 15: //todo Volumetria
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
    public function liberarMuestraMetal(Request $request)
    {

        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Liberado = 1;
        $detalle->save();

        $detalleModel = LoteDetalle::where('Id_lote',$detalle->Id_lote)->where('Liberado',1)->get();

        $lote = LoteAnalisis::find($detalle->Id_lote);
        $lote->Liberado = $detalleModel->count();
        $lote->save();

        $detalleModel = DB::table('ViewLoteDetalle')->where('Id_lote',$detalle->Id_lote)->get();

        $loteModel = LoteAnalisis::where('Id_lote',$detalle->Id_lote)->first();


        $data = array(
            'detalleModel' => $detalleModel,
            'liberado' => $detalleModel->count(),
            'lote' => $loteModel,
        );
        return response()->json($data);
    }
    //* Asignar parametro a lote
    public function asignarMuestraLote(Request $request)
    {
        $sw = false;
        $loteModel = LoteAnalisis::where('Id_lote',$request->idLote)->first();
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $detModel = LoteDetalleEspectro::where('Id_lote',$request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                    # code...
                break;
            case 15: //todo Volumetria
                        # code...
                break;
            default:
                # code...
                break;
        }
        if($detModel->count())
        {
           if($detModel[0]->Id_parametro == $request->idParametro)
           {
            $sw = true;
           }
        }else{
            $sw = true;
        }
        if($sw = true)
        {
            switch ($loteModel->Id_tecnica) {
                case 9: //todo Espectrofotometria
                    $model = LoteDetalleEspectro::create([
                        'Id_lote' => $request->idLote,
                        'Id_analisis' => $request->idAnalisis,
                        'Id_parametro' => $request->idParametro,
                        'Descripcion' => 'Resultado',
                        'Abs1' => 0,
                        'Abs2' => 0,
                        'Abs3' => 0,
                        'De_color' => 0,
                        'Nitratos' => 0,
                        'Nitritos' => 0,
                        'Sulfuros' => 0,
                        'Vol_aforo' => 0,
                        'Vol_destilacion' => 0,
                        'Vol_muestra' => 0,
                    ]);
                    break;
                case 10: //todo Gravimetia
                        # code...
                    break;
                case 15: //todo Volumetria
                            # code...
                    break;
                default:
                    # code...
                    break;
            }
            $solModel = SolicitudParametro::find($request->idSol);
            $solModel->Asignado = 1;
            $solModel->save();

            $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();

            $loteModel = LoteAnalisis::find($request->idLote);
            $loteModel->Asignado = $detModel->count();
            $loteModel->Liberado = 0;
            $loteModel->save();
        }

        //? Muestra datos de lote detalle
        switch ($loteModel->Id_tecnica) {
            case 9: //todo Espectrofotometria
                $paraModel = DB::table('ViewLoteDetalleEspectro')->where('Id_lote', $request->idLote)->get();
                break;
            case 10: //todo Gravimetia
                    # code...
                break;
            case 15: //todo Volumetria
                        # code...
                break;
            default:
                # code...
                break;
        }

        $data = array(
            'sw' => $sw,
            'model' => $paraModel,
        );
        return response()->json($data);
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request)
    {
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        //$lote = Reportes::where('Id_lote', $idLote)->first();

        $lote = DB::table('reportes_fq')->where('Id_lote', $idLote)->where('Id_area', $request->idArea)->get();

        if ($lote->count()) {
            $texto = ReportesFq::where('Id_lote', $idLote)->where('Id_area', $request->idArea)->first();
            $texto->Texto = $textoPeticion;
            $texto->Id_user_m = Auth::user()->id;            

            $texto->save();
        } else {
            $texto = ReportesFq::create([
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
 
}
  