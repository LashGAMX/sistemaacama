<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;
// use File;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CobranzaController extends Controller
{
    
    public function servicios(){

        $model = DB::table('viewprocesoanalisis')->where('Padre',1)->orderBy('Id_solicitud','DESC')->get();
        $data = array(
            'model' => $model,
        );

        return view('cobranza.servicios',$data);
    }
    public function setPago(Request $res){
        $model = ProcesoAnalisis::where('Id_solicitud',$res->id)->first();
        $temp = ProcesoAnalisis::where('Folio','LIKE','%'.$model->Folio.'%')->get();
        $msg = "";
        $sw = 0;
        foreach ($temp as $item) {
            $aux = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
            if ($aux->Pagado != 0) {
                $aux->Pagado = 0;    
                $msg = "Orden de servicio sin pagar";
                $sw = 0;
            }else{
                $aux->Pagado = 1;
                $msg = "Orden de servicio pagado";
                $sw = 1;
            }
            
            $aux->save();
        }
        $data = array(
            'sw' => $sw,
            'msg' => $msg,
        );
        return  response()->json($data);
    }
    
    public function setCredito(Request $res){
        $model = ProcesoAnalisis::where('Id_solicitud',$res->id)->first();
        $temp = ProcesoAnalisis::where('Folio','LIKE','%'.$model->Folio.'%')->get();
        $msg = "";
        $sw = 0;
        foreach ($temp as $item) {
            $aux = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
            if ($aux->Pagado != 0) {
                $aux->Pagado = 0;    
                $msg = "Orden de servicio sin credito";
                $sw = 0;
            }else{
                $aux->Pagado = 2;
                $msg = "Orden de servicio con credito";
                $sw = 1;
            }
            
            $aux->save();
        }
        $data = array(
            'sw' => $sw,
            'msg' => $msg,
        );
        return  response()->json($data);
    }

    public function setRetenido(Request $res){
        $model = ProcesoAnalisis::where('Id_solicitud',$res->id)->first();
        $temp = ProcesoAnalisis::where('Folio','LIKE','%'.$model->Folio.'%')->get();
        $msg = "";
        $sw = 0;
        foreach ($temp as $item) {
            $aux = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
            if ($aux->Pagado != 0) {
                $aux->Pagado = 0;    
                $msg = "Orden de servicio sin retener";
                $sw = 0;
            }else{
                $aux->Pagado = 3;
                $msg = "Orden de servicio retenida";
                $sw = 1;
            }
            
            $aux->save();
        }
        $data = array(
            'sw' => $sw,
            'msg' => $msg,
        );
        return  response()->json($data);
    }
    public function getDescargar($id)
    {
    
        $solicitud = Solicitud::where('Id_solicitud', $id)->first();
        $folioPadre = str_replace('/', '-', $solicitud->Folio_servicio);
        $folderPath = storage_path('app/public/clientes/' . $solicitud->Fecha_muestreo . '/' . $folioPadre);
        $directory = 'clientes/'. $solicitud->Fecha_muestreo . '/' . $folioPadre;
        
        // Obtiene todos los archivos en la carpeta
        $files = Storage::files($directory);
        
        // Crear la carpeta ZIP si no existe
        if (!File::exists(public_path('storage/zip'))) {
            File::makeDirectory(public_path('storage/zip'), 0755, true);
        }
        
        // Crear un archivo ZIP
        $zip = new ZipArchive();
        $zipFileName = $folioPadre.'.zip';
        $zipFilePath = public_path('storage/zip/' . $zipFileName);
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $fileName) {
                // Obtén la ruta completa del archivo en storage
                $filePath = Storage::path($fileName);
        
                // Asegúrate de que el archivo exista
                if (file_exists($filePath)) {
                    // Agregar el archivo al ZIP usando el path correcto
                    $zip->addFile($filePath, basename($fileName));
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }
        // header("Content-Length: " . filesize($zipFilePath));
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename='.$zipFileName.'');
    
        // readfile($zipFilePath);

        $nuevaURL = url("public/storage/zip/".$zipFileName);
        header('Location: ' . $nuevaURL);
        exit;
    }
}
  