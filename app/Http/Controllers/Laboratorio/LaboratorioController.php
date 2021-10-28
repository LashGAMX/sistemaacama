<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\ProcesoAnalisis;
use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\Reportes;
use Illuminate\Support\Facades\DB;
use \Milon\Barcode\DNS2D;

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
        $formulas = DB::table('tipo_formulas')->get();
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
        $model = LoteAnalisis::where('Id_tipo',$request->tipo)->where('Fecha',$request->fecha)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function asignar()
    {
        return view('laboratorio.asignar');
    }
    public function asgnarMuestraLote($id)
    {
        $lote = LoteDetalle::where('Id_lote',$id)->get();
        return view('laboratorio.asignarMuestraLote',compact('lote'));
    }

    //Función LOTE > CREAR O MODIFICAR TEXTO DEL LOTE > PROCEDIMIENTO/VALIDACIÓN
    public function guardarTexto(Request $request){        
        $textoPeticion = $request->texto;
        $idLote = $request->lote;

        $lote = DB::table('reportes')->where('Id_reporte', $idLote)->get();
        
        if($lote->count()){
            $texto = Reportes::find($idLote);
            $texto->Texto = $textoPeticion;
            $texto->save();
        }else{
            $texto = Reportes::create(['Texto' => $textoPeticion]);
        }

        return response()->json(
            compact('texto')
        );
    }

    //Función para recuperar el texto almacenado en la tabla reportes; campo Texto
    public function busquedaPlantilla(Request $request){
        //Recibe el Id del lote para recuperar el texto almacenado en el campo Texto de la tabla reportes 
        $textoRecuperado = Reportes::where('Id_reporte', $request->lote)->first();
        $textoRecuperadoPredeterminado = Reportes::where('Id_reporte' , 0)->first();

        return response()->json(
            compact('textoRecuperado', 'textoRecuperadoPredeterminado')
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
            'margin_bottom' => 30,
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

        //Crea el documento PDF
        $mpdf->Output();
    }
}
