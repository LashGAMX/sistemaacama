<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
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
    public function asignar()
    {
        return view('laboratorio.asignar');
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

    //FUNCIÓN PARA GENERAR EL DOCUMENTO PDF EN VISTA CAPTURA
    public function exportPdfCaptura($formulaTipo) 
    {
        //$headerMPDF = file_get_contents('/home/siste168/public_html/acamasys_dev/storage/framework/views/64c92ca226484687dcf7dbeeaa6287cc7a0e8ad6.php');

        $formulaSelected = $formulaTipo;

        //$qr = new DNS2D();
        //$model = DB::table('ViewSolicitud')->where('Id_cotizacion')->first();
        //$parametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud',$model->Id_solicitud)->get();

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 30
        ]);
        
        $mpdf->SetWatermarkImage(
            asset('storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;
        $html = view('exports.laboratorio.captura', compact('formulaSelected'));
        //$html = view('exports.laboratorio.headerCaptura', compact('formulaSelected'));
        //$html = view('exports.cotizacion.ordenServicio', compact('model','parametros','qr'));
        $mpdf->CSSselectMedia = 'mpdf';

        //$mpdf->setHeader('{PAGENO}');

        //$mpdf->setHeader($headerMPDF, compact('formulaSelected'));
        
        //Establece el pie de página
        $mpdf->SetHTMLFooter(
            '
            <footer>
            <div id="pie1">
                <div id="pie2">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <td id="analizo">ANALIZÓ</td>
                                <td id="superviso">SUPERVISÓ</td>
                            </tr>
                        </thead>            
        
                        <tbody>
                            <tr>
                                <td>AQUÍ VA LA FIRMA</td>
                                <td>AQUÍ VA LA FIRMA</td>
                            </tr>
                            <tr>
                                <td id="nombreAnalizo">NOMBRE ANALIZÓ</td>
                                <td id="nombreSuperviso">NOMBRE SUPERVISÓ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>   
            </footer>             
        '
        , 'O', 'E');        

        $mpdf->WriteHTML($html);
        //$mpdf->AddPage('HOLA MUNDO');
        $mpdf->Output();
    }
}
