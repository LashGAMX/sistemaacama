<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ObservacionMuestra;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\Reportes;
use App\Models\SolicitudParametro;
use App\Models\TipoFormula;
use Illuminate\Support\Facades\DB;

class LaboratorioController extends Controller
{
    function index(){ 
        return view('laboratorio.laboratorio');  
    }
  
    public function analisis(){        
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
    public function observacion(){
        $formulas = DB::table('tipo_formulas')->get();
        return view('laboratorio.observacion', compact('formulas'));
    }

    public function getObservacionanalisis(Request $request)
    {
        $model = DB::table('ViewObservacionMuestra')->where('Id_area',$request->id)->get();
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }

    public function aplicarObservacion(Request $request){
        $viewObservacion = DB::table('ViewObservacionMuestra')->where('Folio',$request->folioActual)->first();
                
            $observacion = ObservacionMuestra::find($viewObservacion->Id_observacion);
            $observacion->Ph = $request->ph;
            $observacion->Solido = $request->solidos;
            $observacion->Olor = $request->olor;
            $observacion->Color = $request->color;
            $observacion->Observaciones = $request->observacionGeneral;
            $observacion->save();                

        return response()->json(
            compact('observacion')
        );
    }

    //*********************************************************************************************************** */
    public function tipoAnalisis(){ 
        return view('laboratorio.tipoAnalisis');
    }
    
    public function captura()
    {
        $parametro = Parametro::all();
        return view('laboratorio.captura',compact('parametro'));
    }

    public function lote()
    {
        $formulas = DB::table('ViewTipoFormula')->where('Id_area',2)->get();
        $textoRecuperadoPredeterminado = Reportes::where('Id_reporte' , 0)->first();
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
        $model = DB::table('ViewLoteAnalisis')->where('Id_tipo',$request->tipo)->where('Fecha',$request->fecha)->get();
        $data = array(
            'model' => $model,
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
        $lote = LoteDetalle::where('Id_lote',$id)->get();
        $idLote = $id;
        return view('laboratorio.asignarMuestraLote',compact('lote','idLote'));
    }
    //* Muestra los parametros sin asignar a lote
    public function muestraSinAsignar(Request $request)
    {
        $model = DB::table('ViewSolicitudParametros')->where('Asignado','!=',1)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    //* Muestra asigada a lote
    public function getMuestraAsignada(Request $request)
    {
        $model = DB::table('ViewLoteDetalle')->where('Id_lote',$request->idLote)->get();
        $data = array(
            'model' => $model,
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
        ]);

        $solModel = SolicitudParametro::find($request->idSol);
        $solModel->Asignado = 1;
        $solModel->save();

        $paraModel = DB::table('ViewLoteDetalle')::where('Id_lote',$request->idLote)->get();

        $data = array(
            'model' => $paraModel,
        );
        return response()->json($data);
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request){        
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        //$lote = Reportes::where('Id_lote', $idLote)->first();

        $lote = DB::table('reportes')->where('Id_lote', $idLote)->get();
        
        if($lote->count()){
            $texto = Reportes::where('Id_lote', $idLote)->first();
            $texto->Texto = $textoPeticion;
            $texto->save();
        }else{
            $texto = Reportes::create(['Texto' => $textoPeticion]);
        }

        return response()->json(
            compact('texto')
        );
    }


    //NUEVA FUNCIÓN BUSQUEDA FILTROS > CAPTURA.JS
    public function busquedaFiltros($numMuestra){

    }



    //Función para recuperar el texto almacenado en la tabla reportes; campo Texto
    public function busquedaPlantilla(Request $request){
        //Recibe el Id del lote para recuperar el texto almacenado en el campo Texto de la tabla reportes         
        
        $textoRecuperado = Reportes::where('Id_lote', $request->lote)->first();

        $textoRecuperadoPredeterminado = Reportes::where('Id_lote' , 0)->first();

        $textoEncontrado = html_entity_decode($textoRecuperado->Texto);
        //$prueba = strip_tags($prueb);


        $textoDefault = html_entity_decode($textoRecuperadoPredeterminado->Texto);
        //$prueba2 = strip_tags($prueb1);

        return response()->json(
            compact('textoRecuperado', 'textoRecuperadoPredeterminado', 'textoEncontrado', 'textoDefault')
        );
    }

    //*************************FUNCIÓN PARA GENERAR EL DOCUMENTO PDF EN VISTA CAPTURA****************************
    public function exportPdfCaptura($formulaTipo) 
    {        
        $formulaSelected = $formulaTipo;        

        //Hace referencia a la vista capturaHeader y posteriormente le envía el valor de la var.formulaSelected
        $htmlHeader = view('exports.laboratorio.capturaHeader', compact('formulaSelected'));
        
        //Hace referencia a la vista capturaPie
        $htmlFooter = view('exports.laboratorio.capturaPie');

        //Opciones del documento PDF
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 48,
            'margin_bottom' => 37,
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
        
        //Hace referencia a la vista captura, misma que es el body del documento PDF
        $html = view('exports.laboratorio.captura');
        
        $mpdf->CSSselectMedia = 'mpdf';  
        
        //Establece el encabezado del documento PDF
        $mpdf->setHeader("{PAGENO}<br><br>".$htmlHeader);
        
        //Establece el pie de página del PDF                
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');
        
        //Escribe el contenido HTML de la var.html en el documento PDF
        $mpdf->WriteHTML($html);


        //*************************************************Segundo juego de documentos PDF***************************************************
        $mpdf->AddPage('', '', '1', '', '', '', '', 44, '', 6.5, '', '', '', '', '', -1, -1, -1, -1);

        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes
        $textoProcedimiento = Reportes::where('Id_muestra' , '298-1/21')->first();
        
        //Hoja1
        $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));
        $htmlCurvaHeader = view('exports.laboratorio.curvaHeader', compact('formulaSelected'));
        $htmlCurvaFooter = view('exports.laboratorio.curvaFooter');
        $mpdf->SetHTMLHeader('{PAGENO}<br><br>'.$htmlCurvaHeader, 'O', 'E');
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');
        $mpdf->WriteHTML($htmlCurva);

        //Hoja2
        $mpdf->AddPage('', '', '', '', '', '', '', 50, '', '', '', '', '', '', '', '', '', '', '');
        $htmlCurva2 = view('exports.laboratorio.curvaBody2', compact('textoProcedimiento'));                
        /*$mpdf->SetHTMLHeader($htmlCurvaHeader, 'O', 'E');
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');*/
        $mpdf->WriteHTML($htmlCurva2);

        //Crea el documento PDF final
        $mpdf->Output();
    }
}
