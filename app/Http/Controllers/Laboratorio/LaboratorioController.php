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
use App\Models\CurvaCalibracionMet;
use App\Models\VerificacionMetales;
use App\Models\EstandarVerificacionMet;
use App\Models\GeneradorHidrurosMet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboratorioController extends Controller
{
    function index()
    {
        return view('laboratorio.laboratorio');
    }


    //*************************FUNCIÓN PARA GENERAR EL DOCUMENTO PDF EN VISTA CAPTURA****************************
    /* public function exportPdfCaptura($idLote)
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
        $tecnicaMetales = TecnicaLoteMetales::where('Id_lote', $id_lote)->first();

        //Recupera la fecha de preparación y le da un formato d/m/Y        
        $fechaPreparacion = date("d/m/Y", strtotime($tecnicaMetales->Fecha_preparacion));

        //Instancia Carbon
        $fechaHora = Carbon::parse($tecnicaMetales->Fecha_hora_dig);        

        //Separa de la hora la fecha y aplica un formato DD/MM/AAAA
        $soloFecha = $fechaHora->toDateString();
        $soloFechaFormateada = date("d/m/Y", strtotime($soloFecha));

        //Separa la hora de la fecha dando un formato de HH:mm:ss
        $soloHoraFormateada = $fechaHora->toTimeString();

        //Recupera los datos de las tablas filtrándolas por Id del lote
        $blancoMetales = BlancoCurvaMetales::where('Id_lote', $id_lote)->first();
        $estandarMetales = EstandarVerificacionMet::where('Id_lote', $id_lote)->first();
        $verificacionMetales = VerificacionMetales::where('Id_lote', $id_lote)->first();

        $htmlCurva2 = view('exports.laboratorio.curvaBody2', compact('textoProcedimiento', 'estandares', 'limiteCuantificacion', 'bmr', 
        'tecnicaMetales', 'blancoMetales', 'estandarMetales', 'verificacionMetales', 'fechaConFormato', 'soloFechaFormateada', 
        'soloHoraFormateada', 'fechaPreparacion'));
        $mpdf->WriteHTML($htmlCurva2);

        
        //Crea el documento PDF final
        $mpdf->Output();
    } */

    public function exportPdfCaptura($idLote)
    {
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
            asset('/public/storage/HojaMembretadaHorizontal.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $mpdf->showWatermarkImage = true;
        $mpdf->CSSselectMedia = 'mpdf';                
        
        $id_lote = $idLote;
        $semaforo = true;

        //$curvaCalibracion = DB::table('curva_calibracion_met')->where('Id_lote', $id_lote)->first();
        //$generadorHidruros = DB::table('generador_hidruros_met')->where('Id_lote', $id_lote)->first();
        
        $formula = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->first();
        if(!is_null($formula)){
            $formulaSelected = $formula->Parametro;
        }else{
            $formula = DB::table('ViewLoteDetalle')->where('Id_lote', 0)->first();
            $formulaSelected = $formula->Parametro;
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }

        //Recupera el nombre de usuario y firma
        $usuario = DB::table('users')->where('id', auth()->user()->id)->first();
        $firma = $usuario->firma;

        //Formatea la fecha
        $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', $id_lote)->first();
        if(!is_null($fechaAnalisis)){
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            $hora = date("h:j:s", strtotime($fechaAnalisis->created_at));
        }else{
            $fechaAnalisis = DB::table('ViewLoteAnalisis')->where('Id_lote', 0)->first();
            $fechaConFormato = date("d/m/Y", strtotime($fechaAnalisis->Fecha));
            $hora = date("h:j:s", strtotime($fechaAnalisis->created_at));
            $mpdf->SetJS('print("No se han llenado todos los datos del reporte. Verifica que todos los datos estén ingresados.");');
        }

        $datos = DB::table('ViewLoteDetalle')->where('Id_lote', $id_lote)->get();
        if(!is_null($datos)){
            $datosLength = sizeof($datos);
        }else{
            $datos = DB::table('ViewLoteDetalle')->where('Id_lote', 0)->get();
            $datosLength = sizeof($datos);
            echo '<script> alert("Valores predeterminados para las ABS. Rellena este campo.") </script>';
        }

        $loteModel = DB::table('observacion_muestra')->where('Id_analisis', 1)->first();

        /* if(!is_null($datos) && !is_null($loteModel)){
            //Hace referencia a la vista captura, misma que es el body del documento PDF
            $html = view('exports.laboratorio.captura', compact('datos', 'datosLength', 'loteModel'));
        } */

        $html = view('exports.laboratorio.captura', compact('datos', 'datosLength', 'loteModel'));
        
        /* if(!is_null($formula) && !is_null($fechaAnalisis)){
            //Hace referencia a la vista capturaHeader y posteriormente le envía el valor de la var.formulaSelected
            $htmlHeader = view('exports.laboratorio.capturaHeader', compact('formulaSelected', 'fechaConFormato'));
            //Establece el encabezado del documento PDF
            $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);
        } */

        //Hace referencia a la vista capturaHeader y posteriormente le envía el valor de la var.formulaSelected
        $htmlHeader = view('exports.laboratorio.capturaHeader', compact('formulaSelected', 'fechaConFormato', 'hora'));
        //Establece el encabezado del documento PDF
        $mpdf->setHeader("{PAGENO}<br><br>" . $htmlHeader);

        //Hace referencia a la vista capturaPie
        $htmlFooter = view('exports.laboratorio.capturaPie', compact('usuario', 'firma')); 
        //Establece el pie de página del PDF                
        $mpdf->SetHTMLFooter($htmlFooter, 'O', 'E');

        /* if(is_null($formula) || is_null($fechaAnalisis) || is_null($datos) || is_null($loteModel)){
            $semaforo = false;
        } */

        //if($semaforo === true){
            //Escribe el contenido HTML de la var.html en el documento PDF
            $mpdf->WriteHTML($html);
        //}

        //*************************************************Segundo juego de documentos PDF***************************************************
        $mpdf->AddPage('', '', '1', '', '', '', '', 40, 35, 6.5, '', '', '', '', '', -1, -1, -1, -1);

        $semaforoHoja1 = true;

        //Recupera (PRUEBA) el texto dinámico Procedimientos de la tabla reportes
        $textoProcedimiento = Reportes::where('Id_lote', $id_lote)->first();
        if(!is_null($textoProcedimiento)){
            //Hoja1
            $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));
        }else{
            $textoProcedimiento = Reportes::where('Id_lote', 0)->first();
            $htmlCurva = view('exports.laboratorio.curvaBody', compact('textoProcedimiento'));

            $mpdf->SetJS('print("Valores predeterminados para el reporte. Rellena este campo.");');

            //echo '<script type= alert("Valores predeterminados para el reporte. Rellena este campo."); </script>';
        }

        //if(!is_null($formula) && !is_null($fechaAnalisis)){
            $htmlCurvaHeader = view('exports.laboratorio.curvaHeader', compact('formulaSelected', 'fechaConFormato', 'hora'));
            $mpdf->SetHTMLHeader('{PAGENO}<br><br>' . $htmlCurvaHeader, 'O', 'E');
        //}
        
        $htmlCurvaFooter = view('exports.laboratorio.curvaFooter', compact('usuario'));        
        $mpdf->SetHTMLFooter($htmlCurvaFooter, 'O', 'E');
        
        /* if(is_null($textoProcedimiento) || is_null($formula) || is_null($fechaAnalisis)){
            $semaforoHoja1 = false;
        } */

        //if($semaforoHoja1 === true){ 
            $mpdf->WriteHTML($htmlCurva);
        //}

        //Hoja2
        $semaforoHoja2 = true;
        $mpdf->AddPage('', '', '', '', '', '', '', 40, '', '', '', '', '', '', '', '', '', '', '');
        
        //if(!is_null($formula)){
            $limiteCuantificacion = DB::table('parametros')->where('Parametro', $formulaSelected)->first();
        //}
                
        $estandares = estandares::where('Id_lote', $id_lote)->get();
        if(is_null($estandares)){
            $estandares = estandares::where('Id_lote', 0)->get();
            echo '<script> alert("Valores predeterminados para los estándares. Rellena estos datos.") </script>';
        }        

        $bmr = CurvaConstantes::where('Id_lote', $id_lote)->first();
        if(is_null($bmr)){
            $bmr = CurvaConstantes::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para las curvas. Rellena estos datos.") </script>';
        }
        
        $tecnicaMetales = TecnicaLoteMetales::where('Id_lote', $id_lote)->first();
        if(!is_null($tecnicaMetales)){
            //Recupera la fecha de preparación y le da un formato d/m/Y        
            $fechaPreparacion = date("d/m/Y", strtotime($tecnicaMetales->Fecha_preparacion));

            //Instancia Carbon
            $fechaHora = Carbon::parse($tecnicaMetales->Fecha_hora_dig);        

            //Separa de la hora la fecha y aplica un formato DD/MM/AAAA
            $soloFecha = $fechaHora->toDateString();
            $soloFechaFormateada = date("d/m/Y", strtotime($soloFecha));

            //Separa la hora de la fecha dando un formato de HH:mm:ss
            $soloHoraFormateada = $fechaHora->toTimeString();
        }else{
            $tecnicaMetales = TecnicaLoteMetales::where('Id_lote', 0)->first();            
            $fechaPreparacion = date("d/m/Y", strtotime($tecnicaMetales->Fecha_preparacion));            
            $fechaHora = Carbon::parse($tecnicaMetales->Fecha_hora_dig);
            $soloFecha = $fechaHora->toDateString();
            $soloFechaFormateada = date("d/m/Y", strtotime($soloFecha));            
            $soloHoraFormateada = $fechaHora->toTimeString();

            echo '<script> alert("Valores predeterminados en la sección Flama/Generador de hidruros/Horno de grafito/Alimentos. Rellena estos datos.") </script>';
        }

        //Recupera los datos de las tablas filtrándolas por Id del lote
        $blancoMetales = BlancoCurvaMetales::where('Id_lote', $id_lote)->first();
        if(is_null($blancoMetales)){
            $blancoMetales = BlancoCurvaMetales::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados en la sección Blanco de curva. Rellena estos datos.") </script>';
        }

        $estandarMetales = EstandarVerificacionMet::where('Id_lote', $id_lote)->first();
        if(is_null($estandarMetales)){
            $estandarMetales = EstandarVerificacionMet::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para la sección Estándar de verificación del instrumento. Rellena estos datos.") </script>';
        }

        $verificacionMetales = VerificacionMetales::where('Id_lote', $id_lote)->first();
        if(is_null($verificacionMetales)){
            $verificacionMetales = VerificacionMetales::where('Id_lote', 0)->first();
            echo '<script> alert("Valores predeterminados para la sección Verificación del espectrofotómetro. Rellena estos datos.") </script>';
        }

        /* if(is_null($estandares) || is_null($bmr) || is_null($tecnicaMetales) || is_null($blancoMetales) || is_null($estandarMetales) || is_null($verificacionMetales)){
            $semaforoHoja2 = false;
        } */

        //if($semaforoHoja2 === true){

            //if($semaforo === true && $semaforoHoja1 === true && $semaforoHoja2 === true){
                //Crea el documento PDF final
                $sw = true;
            //}else{            
                //echo "Fallo al generar el PDF, faltan valores por llenar o no se encontró un lote válido.";
            //    echo '<script> alert("Faltan valores por llenar"); </script>';
            //    $sw = false;
            //}
            
            $htmlCurva2 = view('exports.laboratorio.curvaBody2', compact('textoProcedimiento', 'estandares', 'limiteCuantificacion', 'bmr', 
            'tecnicaMetales', 'blancoMetales', 'estandarMetales', 'verificacionMetales', 'fechaConFormato', 'soloFechaFormateada', 
            'soloHoraFormateada', 'fechaPreparacion','sw'));
            $mpdf->WriteHTML($htmlCurva2);


        
        //}
        
        $mpdf->Output();
    }
}
