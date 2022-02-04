<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\AreaAnalisis;
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
use App\Models\CurvaCalibracionMet;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use App\Models\Tecnica;
use Carbon\Carbon;

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
        ->orWhere('Id_tipo_formula', 21)
        ->orWhere('Id_tipo_formula', 22)
        ->orWhere('Id_tipo_formula', 23)
        ->orWhere('Id_tipo_formula', 24)
        ->get(); 
        return view('laboratorio.metales.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        $solicitudModel = DB::table('ViewSolicitud')->get();
        $sw = false;
        foreach($solicitudModel as $item)
        {
            $paramModel = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$item->Id_solicitud)->where('Id_tipo_formula',$request->id)->get();
            $sw = false;
            foreach($paramModel as $item2)
            {
                $areaModel = DB::table('ViewTipoFormulaAreas')->where('Id_formula',$item2->Id_tipo_formula)->where('Id_area',2)->get();
                if($areaModel->count())
                {
                    $sw = true;
                }
            }
            if($sw == true)
            {
                $model = DB::table('ViewObservacionMuestra')->where('Id_area',2)->where('Id_analisis',$item->Id_solicitud)->get();
                if($model->count()){
                }else{
                    ObservacionMuestra::create([
                        'Id_analisis' => $item->Id_solicitud,
                        'Id_area' => 2,
                        'Id_tipo' => $request->id,
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
        $model = DB::table('ViewObservacionMuestra')->where('Id_area',2)->where('Id_tipo',$request->id)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }

    public function aplicarObservacion(Request $request)
    {
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Id_area',2)->where('Folio','LIKE',"%{$request->folioActual}%")->first();
        

        $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
        $observacion->Ph = $request->ph;
        $observacion->Solido = $request->solidos;
        $observacion->Olor = $request->olor;
        $observacion->Color = $request->color;
        $observacion->Observaciones = $request->observacionGeneral;
        $observacion->save();


        $model = DB::table('ViewObservacionMuestra')->where('Id_area',2)->get();

        $data = array(
            'model' => $model,
            'view' => $viewObservacion,
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
        $parametro = Parametro::where('Id_tipo_formula', 20)
            ->orWhere('Id_tipo_formula', 21)
            ->orWhere('Id_tipo_formula', 22)
            ->orWhere('Id_tipo_formula', 23)
            ->orWhere('Id_tipo_formula', 24)
            ->get();
        // $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get(); 
        // var_dump($parametro);
        return view('laboratorio.metales.captura', compact('parametro'));
    }
    public function getDataCaptura(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->get();
        $idLote = 0;
        foreach($lote as $item)
        {
            $detModel = DB::table('ViewLoteDetalle')->where('Id_lote', $item->Id_lote)->first();    
            if($detModel->Id_parametro == $request->formulaTipo)
            {
                $idLote = $detModel->Id_lote; 
            }
        }

        // $detalleModel = DB::tables'ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $idLote)->get();
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

    // todo ************************************************
    // todo Fin de Captura
    // todo ************************************************

    // todo ************************************************
    // todo Inicio de lote
    // todo ************************************************

    public function lote()
    {
        $tecnica = Tecnica::all();
        $formulas = DB::table('ViewTipoFormula')->where('Id_area', 2)->get();
        $textoRecuperadoPredeterminado = Reportes::where('Id_reporte', 0)->first();
        return view('laboratorio.metales.lote', compact('formulas','tecnica','textoRecuperadoPredeterminado'));
    }
    public function createLote(Request $request)
    {
        $tipoModel = TipoFormula::where('Id_tipo_formula',$request->tipo)->first();
        $model = LoteAnalisis::create([
            'Id_tipo' => $request->tipo,
            'Id_area' => $tipoModel->Id_area,
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
        $model = DB::table('ViewLoteAnalisis')->where('Id_tipo', $request->tipo)->where('Fecha', $request->fecha)->get();
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
        $reporte = Reportes::where('Id_lote',$request->idLote)->first();

        $constantesModel = CurvaConstantes::where('Id_lote', $request->idLote)->get();

        if($constantesModel->count()){
            $constantes = CurvaConstantes::where('Id_lote', $request->idLote)->first();

            array_push($data, $constantes);
        }else{
            array_push($data, null);
        }

        $tecnicaLoteMet = DB::table('tecnica_lote_metales')->where('Id_lote', $request->idLote)->get();
        $blancoCurvaMet = DB::table('blanco_curva_metales')->where('Id_lote', $request->idLote)->get();
        $estandarVerificacionMet = DB::table('estandar_verificacion_met')->where('Id_lote', $request->idLote)->get();
        $verificacionMet = DB::table('verificacion_metales')->where('Id_lote', $request->idLote)->get();
        $curvaCalibracionMet = DB::table('curva_calibracion_met')->where('Id_lote', $request->idLote)->get();
        $generadorHidrurosMet = DB::table('generador_hidruros_met')->where('Id_lote', $request->idLote)->get();

        if($tecnicaLoteMet->count() && $blancoCurvaMet->count() && $estandarVerificacionMet->count() && $verificacionMet->count() && $curvaCalibracionMet->count() && $generadorHidrurosMet->count()){
            $tecLotMet = TecnicaLoteMetales::where('Id_lote',$request->idLote)->first();
            $blancCurvaMet = BlancoCurvaMetales::where('Id_lote',$request->idLote)->first();
            $stdVerMet = EstandarVerificacionMet::where('Id_lote',$request->idLote)->first();
            $verMet = VerificacionMetales::where('Id_lote',$request->idLote)->first();
            $curMet = CurvaCalibracionMet::where('Id_lote',$request->idLote)->first();
            $genMet = GeneradorHidrurosMet::where('Id_lote',$request->idLote)->first();

            array_push(                                
                $data,
                $tecLotMet,
                $blancCurvaMet,
                $stdVerMet,
                $verMet,
                $curMet,
                $genMet,                
            );
                        
        }else{
            array_push(
                $data, null, null, null, null, null, null
            );
        }
        
        array_push($data, $reporte, $idLoteIf);
        return response()->json($data);
    }

    public function getPlantillaPred(Request $request){
        $plantillaPredeterminada = Reportes::where('Id_lote', $request->idLote)->first();

        return response()->json($plantillaPredeterminada);
    }


    /* public function getDatalote(Request $request)
    {            
        $idLoteIf = $request->idLote;            
        $reporte = Reportes::where('Id_lote',$request->idLote)->first();
        $constantes = DB::table('constantes')->get();

        $tecnicaLoteMet = DB::table('tecnica_lote_metales')->where('Id_lote', $request->idLote)->get();
        $blancoCurvaMet = DB::table('blanco_curva_metales')->where('Id_lote', $request->idLote)->get();
        $estandarVerificacionMet = DB::table('estandar_verificacion_met')->where('Id_lote', $request->idLote)->get();
        $verificacionMet = DB::table('verificacion_metales')->where('Id_lote', $request->idLote)->get();
        $curvaCalibracionMet = DB::table('curva_calibracion_met')->where('Id_lote', $request->idLote)->get();
        $generadorHidrurosMet = DB::table('generador_hidruros_met')->where('Id_lote', $request->idLote)->get();

        if($tecnicaLoteMet->count() && $blancoCurvaMet->count() && $estandarVerificacionMet->count() && $verificacionMet->count() && $curvaCalibracionMet->count() && $generadorHidrurosMet->count()){
            $tecLotMet = TecnicaLoteMetales::where('Id_lote',$request->idLote)->first();
            $blancCurvaMet = BlancoCurvaMetales::where('Id_lote',$request->idLote)->first();
            $stdVerMet = EstandarVerificacionMet::where('Id_lote',$request->idLote)->first();
            $verMet = VerificacionMetales::where('Id_lote',$request->idLote)->first();
            $curMet = CurvaCalibracionMet::where('Id_lote',$request->idLote)->first();
            $genMet = GeneradorHidrurosMet::where('Id_lote',$request->idLote)->first();

            $data = array(
                'reporte' => $reporte,
                'constantes' => $constantes,
                'tecLotMet' => $tecLotMet,
                'blancCurvaMet' => $blancCurvaMet,
                'stdVerMet' => $stdVerMet,
                'verMet' => $verMet,
                'curMet' => $curMet,
                'genMet' => $genMet,
                'idLote' => $idLoteIf
            );
            
            return response()->json($data);
        }else{
            $data = array(
                'reporte' => $reporte,
                'constantes' => $constantes,
                'idLote' => $idLoteIf
            );

            return response()->json($data);
        }        
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
        return view('laboratorio.metales.asignarMuestraLote', compact('lote', 'idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')
        ->orWhere('Id_tipo_formula',20)
        ->orWhere('Id_tipo_formula',21)
        ->orWhere('Id_tipo_formula',22)
        ->orWhere('Id_tipo_formula',23)
        ->orWhere('Id_tipo_formula',24)
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
        $model = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //! Eliminar parametro muestra
    public function delMuestraLote(Request $request)
    {
        $detModel = DB::table('lote_detalle')->where('Id_detalle',$request->idDetalle)->delete();

        $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();
        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        
        $solModel = SolicitudParametro::where('Id_solicitud',$request->idSol)->where('Id_subnorma',$request->idParametro)->first();
        $solModel->Asignado = 0;
        $solModel->save(); 
        $solModel = SolicitudParametro::find($request->idSol);

        $model = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json();
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
        $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();
        if($detModel->count())
        {
           if($detModel[0]->Id_parametro == $request->idParametro)
           {
            $model = LoteDetalle::create([
                'Id_lote' => $request->idLote,
                'Id_analisis' => $request->idAnalisis,
                'Id_parametro' => $request->idParametro,
                'Id_control' => 1,
                'Descripcion' => 'Resultado',
                'Factor_dilucion' => 1,
                'Factor_conversion' => 0,
                'Liberado' => 0,
            ]);
    
            $solModel = SolicitudParametro::find($request->idSol);
            $solModel->Asignado = 1;
            $solModel->save();
    
            $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();
            
            $loteModel = LoteAnalisis::find($request->idLote);
            $loteModel->Asignado = $detModel->count();
            $loteModel->Liberado = 0;
            $loteModel->save();
            
            $sw = true;
           }
        }else{
            $model = LoteDetalle::create([
                'Id_lote' => $request->idLote,
                'Id_analisis' => $request->idAnalisis,
                'Id_parametro' => $request->idParametro,
                'Descripcion' => 'Resultado',
                'Factor_dilucion' => 1,
                'Factor_conversion' => 0,
                'Liberado' => 0,
            ]);
    
            $solModel = SolicitudParametro::find($request->idSol);
            $solModel->Asignado = 1;
            $solModel->save();
    
            $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();
            
            $loteModel = LoteAnalisis::find($request->idLote);
            $loteModel->Asignado = $detModel->count();
            $loteModel->Liberado = 0;
            $loteModel->save();
            
            $sw = true;
        }

        $paraModel = DB::table('ViewLoteDetalle')->where('Id_lote', $request->idLote)->get();

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
    public function guardarDatosGenerales(Request $request){
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
                    'ABS1' =>$request->blanco_abs1,
                    'ABS2' => $request->blanco_abs2,
                    'ABS3'  =>$request->blanco_abs3,
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
                    'ABS1' =>$request->verif_Abs1,
                    'ABS2' => $request->verif_Abs2,
                    'ABS3'  =>$request->verif_Abs3,
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

        if($curValMet->count()){
            $curVal = CurvaCalibracionMet::where('Id_lote', $request->idLote)->first();            
            
            $curVal->Bitacora_curCal = $request->curva_bitCurvaCal;
            $curVal->Folio_curCal = $request->curva_folioCurvaCal;

            $curVal->save();
        }else{
            CurvaCalibracionMet::create([
                'Id_lote' => $request->idLote,
                'Bitacora_curCal' => $request->curva_bitCurvaCal,
                'Folio_curCal' => $request->curva_folioCurvaCal
            ]);  
        }

        //*******************************************GENERADORHIDRUROSMET****************************************
        $genHidMet = GeneradorHidrurosMet::where('Id_lote', $request->idLote)->get();

        if($genHidMet->count()){
            $genHid = GeneradorHidrurosMet::where('Id_lote', $request->idLote)->first();            
            
            $genHid->Generador_hidruros = $request->gen_genHidruros;

            $genHid->save();
        }else{
            GeneradorHidrurosMet::create([
                'Id_lote' => $request->idLote,
                'Generador_hidruros' => $request->gen_genHidruros                
            ]);  
        }

        //*******************************************************************************************************
        return response()->json(
            compact('tecLoteMet', 'blancoCurvaMet','verMet', 'stdVerMet', 'curValMet', 'genHidMet')
        );
    }

    // todo ************************************************
    // todo Fin de Lote
    // todo ************************************************
}
