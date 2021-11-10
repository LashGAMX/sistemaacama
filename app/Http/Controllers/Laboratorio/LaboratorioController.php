<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\AreaAnalisis;
use App\Models\Constante;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ObservacionMuestra;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\Reportes;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use App\Models\CurvaConstantes;
use App\Models\estandares;
use App\Models\TecnicaLoteMetales;
use App\Models\BlancoCurvaMetales;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use Illuminate\Support\Facades\DB;

class LaboratorioController extends Controller
{
    function index()
    {
        return view('laboratorio.laboratorio');
    }

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

        return view('laboratorio.analisis', compact('model', 'elements', 'solicitud', 'solicitudLength', 'tecnicas', 'solicitudPuntos', 'solicitudPuntosLength', 'parametros', 'parametrosLength', 'puntoMuestreo', 'puntoMuestreoLength'));
    }


    //***********************************************OBSERVACIÓN********************************************** */
    public function observacion()
    {
        $formulas = DB::table('tipo_formulas')->where('Id_area', 2)->get();
        return view('laboratorio.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        $model = DB::table('ViewObservacionMuestra')->where('Id_area', $request->id)->get();
        

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function aplicarObservacion(Request $request)
    {
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Folio','LIKE',"%{$request->folioActual}%")->first();
        

        $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
        $observacion->Ph = $request->ph;
        $observacion->Solido = $request->solidos;
        $observacion->Olor = $request->olor;
        $observacion->Color = $request->color;
        $observacion->Observaciones = $request->observacionGeneral;
        $observacion->save();


        $model = DB::table('ViewObservacionMuestra')->where('Id_area', $request->idTipo)->get();

        $data = array(
            'model' => $model,
            'view' => $viewObservacion,
        );
        return response()->json($data);
    }

    //*****************************************CAPTURA****************************************************************** */
    public function tipoAnalisis()
    {
        return view('laboratorio.tipoAnalisis');
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
        return view('laboratorio.captura', compact('parametro'));
    }
    public function getDataCaptura(Request $request)
    {
        //$parametro = Parametro::where('Id_parametro',$request->formulaTipo)->first();
        $lote = DB::table('ViewLoteAnalisis')->where('Fecha', $request->fechaAnalisis)->first();
        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $lote->Id_lote)->get();
        $curvaConst = CurvaConstantes::where('Id_lote',$lote->Id_lote)->first();
        $data = array(
            'lote' => $lote,
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
        ]);


        $detalle = DB::table('ViewLoteDetalle')->where('Id_lote', $loteModel->Id_lote)->get();
        $data = array(
            'detalle' => $detalle
        );
        return response()->json($data);
    }
    public function operacion(Request $request)
    {
        $curva = CurvaConstantes::where('Id_lote', $request->idlote)->first();
        $x = $request->x;
        $y = $request->y;
        $z = $request->z;
        $FD = $request->FD;
        $suma = ($x + $y + $z);
        $promedio = $suma / 3;
        $resultado = (($promedio - 0.00646) / 0.16929) * $FD;

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

    public function lote()
    {
        $formulas = DB::table('ViewTipoFormula')->where('Id_area', 2)->get();
        $textoRecuperadoPredeterminado = Reportes::where('Id_reporte', 0)->first();
        return view('laboratorio.lote', compact('formulas', 'textoRecuperadoPredeterminado'));
    }
    public function createLote(Request $request)
    {
        $model = LoteAnalisis::create([
            'Id_tipo' => $request->tipo,
            'Id_area' => 0,
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
        $reporte = Reportes::where('Id_lote',$request->idLote)->first();
        $constantes = DB::table('constantes')->get();
        $data = array(
            'reporte' => $reporte,
            'constantes' => $constantes,
        );
        return response()->json($data);
    }

    public function asignar()
    {
        $tipoFormula = TipoFormula::all();
        return view('laboratorio.asignar', compact('tipoFormula'));
    }
    public function asgnarMuestraLote($id)
    {
        $lote = LoteDetalle::where('Id_lote', $id)->get(); 
        $idLote = $id;
        return view('laboratorio.asignarMuestraLote', compact('lote', 'idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')->where('Asignado', '!=', 1)->get();
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
    public function liberarMuestraMetal(Request $request)
    {
        
        $detalle = LoteDetalle::find($request->idDetalle);
        $detalle->Liberado = 1;
        $detalle->save();

        $detalleModel = LoteDetalle::where('Id_lote',$detalle->Id_lote)->where('Liberado',1)->get();
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
        $model = LoteDetalle::create([
            'Id_lote' => $request->idLote,
            'Id_analisis' => $request->idAnalisis,
            'Id_parametro' => $request->idParametro,
            'Descripcion' => 'Resultado',
            'Factor_dilucion' => 1,
            'Factor_conversion' => 0,
        ]);

        $solModel = SolicitudParametro::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();

        $detModel = LoteDetalle::where('Id_lote',$request->idLote)->get();
        
        $loteModel = LoteAnalisis::find($request->idLote);
        $loteModel->Asignado = $detModel->count();
        $loteModel->Liberado = 0;
        $loteModel->save();

        $paraModel = DB::table('ViewLoteDetalle')::where('Id_lote', $request->idLote)->get();

        $data = array(
            // 'cant' => $cant,
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
            $texto->save();
        } else {
            $texto = Reportes::create(['Texto' => $textoPeticion]);
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
                'Oxido_nitroso' => $request->flama_oxidoN
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

        //****************************************************************************************************
        return response()->json(
            compact('tecLoteMet', 'blancoCurvaMet','verMet', 'stdVerMet')
        );
    }


    //*********************************************************************************************************************

    //*************************FUNCIÓN PARA GENERAR EL DOCUMENTO PDF EN VISTA CAPTURA****************************
    public function exportPdfCaptura($idLote)
    {
        $id_lote = $idLote;

        $formula = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->first();
        $formulaSelected = $formula->Parametro;

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
        $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));

        //Hace referencia a la vista capturaHeader y posteriormente le envía el valor de la var.formulaSelected
        $htmlHeader = view('exports.laboratorio.capturaHeader', compact('formulaSelected', 'fechaConFormato'));

        //Hace referencia a la vista capturaPie
        $htmlFooter = view('exports.laboratorio.capturaPie', compact('usuario', 'firma'));

        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 48,
            'margin_bottom' => 45,
            'defaultheaderfontstyle' => ['normal'],
            'defaultheaderline' => '0'
        ]);

        //Establece la marca de agua del documento PDF
        $mpdf->SetWatermarkImage(
            asset('storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;

        $datos = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->get();
        $loteModel = DB::table('observacion_muestra')->where('Id_analisis', 1)->first();

        $datosLength = sizeof($datos);

        //Hace referencia a la vista captura, misma que es el body del documento PDF
        $html = view('exports.laboratorio.captura', compact('datos', 'datosLength', 'loteModel'));

        $mpdf->CSSselectMedia = 'mpdf';

        //Establece el encabezado del documento PDF
        $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);

        //Establece el pie de página del PDF                
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');

        //Escribe el contenido HTML de la var.html en el documento PDF
        $mpdf->WriteHTML($html);


        //*************************************************Segundo juego de documentos PDF***************************************************
        $mpdf->AddPage('', '', '1', '', '', '', '', 40, 35, 6.5, '', '', '', '', '', -1, -1, -1, -1);

        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes
        $textoProcedimiento = Reportes::where('Id_lote', $id_lote)->first();

        //Hoja1
        $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));
        $htmlCurvaHeader = view('exports.laboratorio.curvaHeader', compact('formulaSelected', 'fechaConFormato'));
        $htmlCurvaFooter = view('exports.laboratorio.curvaFooter', compact('usuario'));
        $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlCurvaHeader, 'O', 'E');
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCurva);

        //Hoja2
        $mpdf->AddPage('', '', '', '', '', '', '', 40, '', '', '', '', '', '', '', '', '', '', '');

        $limiteCuantificacion = DB::table('parametros')->where('Parametro', $formulaSelected)->first();
        $estandares = estandares::where('Id_lote', $id_lote)->get();
        $bmr = CurvaConstantes::where('Id_lote', $id_lote)->first();

        $htmlCurva2 = view('exports.laboratorio.curvaBody2', compact('textoProcedimiento', 'estandares', 'limiteCuantificacion', 'bmr'));
        $mpdf->WriteHTML($htmlCurva2);

        
        //Crea el documento PDF final
        $mpdf->Output();
    }
}
