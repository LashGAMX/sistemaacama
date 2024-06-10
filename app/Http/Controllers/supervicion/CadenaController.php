<?php

namespace App\Http\Controllers\Supervicion;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\DireccionReporte;
use App\Models\GastoMuestra;
use App\Models\LoteAnalisis;
use App\Models\LoteDetalle;
use App\Models\LoteDetalleColiformes;
use App\Models\LoteDetalleDbo;
use App\Models\LoteDetalleDirectos;
use App\Models\LoteDetalleDqo;
use App\Models\LoteDetalleDureza;
use App\Models\LoteDetalleEcoli;
use App\Models\LoteDetalleEspectro;
use App\Models\LoteDetalleGA;
use App\Models\LoteDetalleHH;
use App\Models\LoteDetalleNitrogeno;
use App\Models\LoteDetallePotable;
use App\Models\LoteDetalleSolidos;
use App\Models\PhMuestra;
use App\Models\ProcesoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudesGeneradas;
use App\Models\SolicitudPuntos;
use App\Models\TemperaturaMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CadenaController extends Controller
{
    //cadena  
    public function cadenaCustodia()
    {
        
       $model = DB::table('ViewSolicitud4')->orderby('Id_solicitud', 'desc')->where('Padre', 1)->get();
       return view('supervicion.cadena.cadena', compact('model'));
    }

   
    public function detalleCadena($id)
    {
        $swSir = false;
        $model = DB::table('ViewSolicitud3')->where('Id_solicitud', $id)->first();
        $intermediario = DB::table('ViewIntermediarios')->where('Id_intermediario', $model->Id_intermediario)->first();
        $proceso = ProcesoAnalisis::where('Id_solicitud',$id)->first();
        $direccion = DireccionReporte::where('Id_direccion',$model->Id_direccion)->first();
        if ($model->Siralab == 1) {
            $swSir = true;
        } else 
        {
         
        }
        $puntos = SolicitudPuntos::where('Id_solPadre', $id)->get();
        return view('supervicion.cadena.detalleCadena', compact('model', 'puntos','swSir', 'intermediario','proceso','direccion'));
    }
    public function getParametroCadena(Request $res)
    {
        $model = DB::table('ViewCodigoRecepcion')->where('Id_solicitud', $res->idPunto)->where('Num_muestra', 1)->orderByRaw('CASE WHEN Orden IS NOT NULL THEN 0 ELSE 1 END, Orden ASC')->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
//     public function cadenaCustodia()
//   {
//     $model= DB :: table('ViewSolicitud4')->orderby('Id_solicitud','desc')->where('Padre',1)->get();

//     $Miarray =array(
//     'model'=>$model,
//     );
//     return view('supervicion.cadena.cadena',compact ('model'));
//   }

    public function liberarMuestra(Request $res)
    {
        $sw = true;
        $model = CodigoParametros::where('Id_codigo', $res->idCod)->first();
        switch ($model->Id_parametro) {
            case 5:
                $aux = CodigoParametros::where('Id_parametro',$model->Id_parametro)->where('Id_solicitud',$model->Id_solicitud)->get();
                for ($i=0; $i < $aux->count(); $i++) { 
                    $aux[$i]->Cadena = 0;
                    $aux[$i]->Reporte = 0; 
                    $aux[$i]->save();
                }
                break;
            
            default:
        
                break;
        }

        $solModel = Solicitud::where('Id_solicitud',$model->Id_solicitud)->where('Id_servicio','!=',3)->get();
        switch ($model->Id_parametro) {
            case 14:
            case 31:
            case 97:
            case 100:
            case 67:
            case 68:
            case 26:
            case 64:
            case 358:
            case 110:
            case 2:
        
                if ($solModel->count()) {
                    $solGen = SolicitudesGeneradas::where('Id_solicitud',$model->Id_solicitud)->first();
                    $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                    $model2->Resultado2 = $res->resLiberado; 
                    $model2->Cadena = 1;
                    $model2->Analizo = $solGen->Id_muestreador;
                    $model2->Reporte = 1;
                }else{
                    $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                    $model2->Resultado2 = $res->resLiberado; 
                    $model2->Cadena = 1;
                    $model2->Reporte = 1;
                }
                break;
            case 11:
            case 83:
                $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                $model2->Resultado2 = $res->resLiberado; 
                $model2->Cadena = 1;
                $model2->Reporte = 1;
                // $model2->Analizo = Auth::user()->id;
                $model2->Analizo = 14;
                break;
            default:
                $model2 = CodigoParametros::where('Id_codigo', $res->idCod)->first();
                $model2->Resultado2 = $res->resLiberado; 
                $model2->Cadena = 1;
                $model2->Reporte = 1;
                break;
        }


        // $model2->Analizo = Auth::user()->id;
        $model2->Liberado = 1;
        $model2->Asignado = 1;
        $model2->save();


        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }

    // Controles de liberacion, regresas muestras, etc... 
    public function regresarMuestra(Request $res) {
       $codigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
       switch ($codigoParametro->Id_area) {
        case 2:
                 $model = LoteDetalle::where('Id_codigo',$res->idCodigo)->first();
                 $model->Liberado = 0;
                 $model->save();
            break;
        case 8:
                $model = LoteDetalleDureza::where('Id_codigo',$res->idCodigo)->first();
                $model->Liberado = 0;
                $model->save();
           break;
        case 16:
        case 5:
                $model = LoteDetalleEspectro::where('Id_codigo',$res->idCodigo)->get();
                foreach ($model as $item){
                    $item->Liberado = 0;
                    $item->save();
                }
        case 6:
            switch ($codigoParametro->Id_parametro) {
                case 16: //Huevos
                    $model = LoteDetalleHH::where('Id_codigo',$res->idCodigo)->get();
                    foreach ($model as $item){
                        $item->Liberado = 0;
                        $item->save();
                }
                break;
                case 5: // dbo
                    $model = LoteDetalleDbo::where('Id_analisis',$codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                    foreach ($model as $item){
                        $codigo = LoteDetalleDbo::where('Id_codigo', $item->Id_codigo)->first();
                        $codigo->Liberado = 0;
                        $codigo->save();
                    }
                break;
                case 137:
                    $model = LoteDetalleColiformes::where('Id_codigo',$res->idCodigo)->get();
                    foreach ($model as $item){
                        $item->Liberado = 0;
                        $item->save();
                    }
                break;
                case 35: //ecoli mb
                    $model = LoteDetalleColiformes::where('Id_analisis',$codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                    foreach ($model as $item){
                        $codigo = LoteDetalleColiformes::where('Id_codigo', $item->Id_codigo)->first();
                        $codigo->Liberado = 0;
                        $codigo->save();
                    }
                break;
                case 12: //coliformes fecales
                    $model = LoteDetalleColiformes::where('Id_analisis',$codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                    foreach ($model as $item){
                        $codigo = LoteDetalleColiformes::where('Id_codigo', $item->Id_codigo)->first();
                        $codigo->Liberado = 0;
                        $codigo->save();
                    }
                break;
           
           
            }
            break;
        case 13: //grasas
                $model = LoteDetalleGA::where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', $codigoParametro->Id_parametro)->get();
                foreach ($model as $item){
                    $codigo = LoteDetalleGA::where('Id_codigo',$item->Id_codigo)->first();
                    $codigo->Liberado = 0;
                    $codigo->save();
                }
                break;
        case 14:
            switch($codigoParametro->Id_parametro){
                case 10:
                case 9: // N. AMONIACAL
                case 10: //N. ORGANICO
                    $model = LoteDetalleNitrogeno::where('Id_codigo',$res->idCodigo)->get();
                    foreach ($model as $item){
                        $item->Liberado = 0;
                        $item->save();
                    }
                    break;
                case 6:
                    $model = LoteDetalleDqo::where('Id_codigo',$res->idCodigo)->get();
                    foreach ($model as $item){
                        $item->Liberado = 0;
                        $item->save();
                    }
                    break;
            }
           
            break;
            case 15: 
                $model = LoteDetalleSolidos::where('Id_codigo',$res->idCodigo)->get();
                foreach ($model as $item){
                    $item->Liberado = 0;
                    $item->save();
                }
                
            break;
        default:
            $model = LoteDetalleDirectos::where('Id_codigo',$res->idCodigo)->get();
            foreach ($model as $item){
            $item->Liberado = 0;
            $item->save();
        }
            
            break;
       }
       $temp = CodigoParametros::find($res->idCodigo);
       $temp->Liberado = 0;
       $temp->save();
       
        $data = array(
            'idSol' => $res->idSol,
            'idCodigo' => $res->idCodigo,
            'model' => $model,
            
        );

        return response()->json($data);
    }
    public function reasignarMuestra(Request $res){
        $metodo = '';
        $codigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
       switch ($codigoParametro->Id_area) {
        case 2:
                 $model = DB::table('lote_detalle')->where('Id_codigo', $res->idCodigo)->delete();
                 $metodo = 'simple';
            break;
        case 14:
                $model = DB::table('lote_detalle_dqo')->where('Id_codigo', $res->idCodigo)->delete();
                $metodo = 'simple';
        break;
        case 6:
            switch ($codigoParametro->Id_parametro){
                case 5:
                    $model = DB::table('lote_detalle_dbo')->where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', 5)->get(); 
                    foreach($model as $item){
                        $codigo = DB::table('lote_detalle_dbo')->where('Id_codigo', $item->Id_codigo)->delete();
                    }
                    $metodo = 'multiple';
                    break;
                case 16;
                    $model = DB::table('lote_detalle_hh')->where('Id_codigo', $res->idCodigo)->delete();
                    $metodo = 'simple';
                    break;
                case 137;
                    $model = DB::table('lote_detalle_coliformes')->where('Id_codigo', $res->idCodigo)->delete();
                    $metodo = 'simple';
                break;
                case 12;
                        $model = DB::table('lote_detalle_coliformes')->where('Id_analisis', $codigoParametro->Id_solicitud)->where('Id_parametro', 12)->get(); 
                        foreach($model as $item){
                            $codigo = DB::table('lote_detalle_coliformes')->where('Id_codigo', $item->Id_codigo)->delete();
                        }
                        $metodo = 'multiple';
                break;
            }
                
            break; 
        case 16:
        case 5:
            $model = DB::table('lote_detalle_espectro')->where('Id_codigo', $res->idCodigo)->delete();
            $metodo = 'simple';
            break;
        case 8:
                $model = DB::table('lote_detalle_dureza')->where('Id_codigo', $res->idCodigo)->delete();
                $metodo = 'simple';
           break;
        case 19;
                $model = DB::table('lote_detalle_directos')->where('Id_codigo', $res->idCodigo)->delete();
                $metodo = 'simple';
            break;
        default:
            
            break;
       }
       if ($metodo == 'multiple'){
        foreach ($model as $item) {
            $codigo = CodigoParametros::where('Id_codigo', $item->idCodigo)->first();
            $codigo->Asignado = 0;
            $codigo->Resultado = null;
            $codigo->Resultado2 = null;
            $codigo->Id_lote = null;
            //$codigo->Analizo = 1;
            $codigo->save();
        }
       } else {
        $codigo = CodigoParametros::where('Id_codigo', $res->idCodigo)->first();
        $codigo->Asignado = 0;
        $codigo->Resultado = null;
        $codigo->Resultado2 = null;
        $codigo->Id_lote = null;
        //$codigo->Analizo = 1;
        $codigo->save();
 
       }
      
        $data = array(
            'idSol' => $res->idSol,
            'idCodigo' => $res->idCodigo,
            'codigoParametros' => $codigoParametro,
            'model' => $model,
            'area' => $codigoParametro->Id_area,
            "metodo" => $metodo, 
        ); 
        return response()->json($data);
    }
    public function desactivarMuestra(Request $res){
        $codigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $model = CodigoParametros::where('Id_codigo', $res->idCodigo)->first();
        $model->Cadena = 0;
        $model->Reporte = 0;
        $model->Mensual = 0;
        $model->save();

        $data = array(
            "model" => $model,
          
        );
        return response()->json($data);
    }

    public function getDetalleAnalisis(Request $res)
    {
        $aux = 0;
       
        $model = array();
        $solModel = Solicitud::where('Id_solicitud',$res->idSol)->first();
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_parametro) {
                // Metales
            case 17: // Arsenico
            case 231:
            case 208:
            case 207:
            case 20: // Cobre
            case 22: //Mercurio
            case 215:
            case 25: //Zinc
            case 227: 
            case 24: //Plomo
            case 216:
            case 21: //Cromoa
            case 264: 
            case 18: //Cadmio
            case 210:
            case 300: //Niquel
            case 233: // Seleneio
            case 213: //Fierro 
            case 197:
            case 188:
            case 189:
            case 190:
            case 191:
            case 192:
            case 194:
            case 195:
            case 196:
            case 204:
            case 219:
            case 230:
            case 23:
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)
                    ->get();
               
                break;
            case 15: // fosforo
            case 19: // Cianuros
            case 7: //Nitrats 
            case 8: //Nitritos
            case 152: //Cot
            case 99: //Cianuros 127
            case 105: //floururos 127
            case 106:
            case 107:
            case 96: 
            case 95: // Sulfatos
            case 87:
            case 222:
            case 79:
            case 80:
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                    break;
            case 11:
                $model = DB::table('ViewCodigoParametro')->where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', 83)->first();
                $aux = DB::table('ViewLoteDetalleEspectro')
                ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->get();
                break;
            case 6:
                $model = DB::table('ViewLoteDetalleDqo')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)
                    ->where('Id_control', 1)->get();
                break;
            case 9:
            case 10:
            case 108:
                $model = DB::table('ViewLoteDetalleNitrogeno')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 83:
                $model = DB::table('ViewLoteDetalleNitrogeno')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro',9)
                    ->where('Id_control', 1)
                    ->get();
                $aux = DB::table('ViewLoteDetalleNitrogeno')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro',10)
                    ->where('Id_control', 1)
                    ->get();
                break;
            // case 218: //Cloro
            case 64:
                $model = DB::table('ViewLoteDetalleCloro')
                ->where('Id_analisis', $codigoModel->Id_solicitud)
                ->where('Id_parametro',64)
                ->where('Id_control', 1)
                ->get();
                break;
            case 358:
                $model = DB::table('campo_compuesto') 
                    ->where('Id_solicitud', $codigoModel->Id_solicitud)
                    ->get();
                break;
            case "13": // Grasas y Aceites
                $model = DB::table('ViewLoteDetalleGA')
                    ->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)->get();

                    if ($solModel->Num_tomas > 1) {
                        $gasto = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo',1)->get();

                        $sumGasto = 0;
                        $aux = array();
                        foreach($gasto as $item)
                        {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach($gasto as $item) 
                        {
                            array_push($aux,($item->Promedio/$sumGasto));
                        }
                  
                    }else{
                        $model = DB::table('ViewLoteDetalleGA')
                        ->where('Id_analisis', $codigoModel->Id_solicitud)
                        ->where('Id_control', 1)->get();
                    }
               
                break;
                //Mb
            case 5:
            case 71:
                $model = DB::table('ViewLoteDetalleDbo')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                $name = "LoteDetalleDbo";
                break;
            case 70:
                $model = DB::table('viewlotedetalledboino')->where('Id_analisis', $codigoModel->Id_solicitud)
                ->where('Id_control', 1)
                ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                $name = "LoteDetalleDbo";
                break;
            case 12:
            case 134:
            case 133: 
            case 137:
            case 51:
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
              
                break;
            case 253:
                if ($solModel->Id_norma == 27) {
                    $gasto = GastoMuestra::where('Id_solicitud',$codigoModel->Id_solicitud)->get();
                    $sumGasto = 0;
                    $aux = array();
                    foreach($gasto as $item)
                    {
                        $sumGasto = $sumGasto + $item->Promedio;
                    }
                    foreach($gasto as $item) 
                    {
                        array_push($aux,($item->Promedio/$sumGasto));
                    }
                }
                $model = DB::table('ViewLoteDetalleEnterococos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1) 
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 35:
                $model = DB::table('ViewLoteDetalleColiformes')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 16:
                $model = DB::table('ViewLoteDetalleHH')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 78:
                $model = DB::table('ViewLoteDetalleEcoli')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 3: // Solidos
            case 4:
            case 112:
            case 90:
                $model = DB::table('ViewLoteDetalleSolidos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 26: //Gasto
                if ($solModel->Id_servicio != 3) {
                    $model = GastoMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                }else{
                    $model = LoteDetalleDirectos::where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->get();
                }

                break;
            case "67": //Conductividad
            case "68":
                if ($solModel->Id_norma == 27) {
                    if ($solModel->Num_toma > 1) {
                        $gasto = GastoMuestra::where('Id_solicitud',$codigoModel->Id_solicitud)->get();
                        $sumGasto = 0;
                        $aux = array();
                        foreach($gasto as $item)
                        {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach($gasto as $item)
                        {
                            array_push($aux,($item->Promedio/$sumGasto));
                        }
                    }else{
                        $model = ConductividadMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    }
                }
                if ($solModel->Id_servicio != 3) {
                    $model = ConductividadMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                }else{
                    $model = LoteDetalleDirectos::where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->get();
                }
            break;
            case "2": //Materia flotante
                if ($solModel->Id_servicio != 3) {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();   
                }else{
                    $model = LoteDetalleDirectos::where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->get();
                }
                break;
            case 14: //ph
            case 110:
                if ($solModel->Id_norma == 27) {
                    if ($solModel->Num_toma > 1) {
                        $gasto = GastoMuestra::where('Id_solicitud',$codigoModel->Id_solicitud)->get();
                    $sumGasto = 0;
                    $aux = array();
                    foreach($gasto as $item)
                    {
                        $sumGasto = $sumGasto + $item->Promedio;
                    } 
                    foreach($gasto as $item)
                    {
                        array_push($aux,($item->Promedio/$sumGasto));
                    }
                     $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    }else{
                        $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                    }
                }
                if ($solModel->Id_servicio != 3) {
                    $model = PhMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)->where('Activo', 1)->get();
                }else{
                    $model = LoteDetalleDirectos::where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->where('Id_control', 1)->get();
                }
                break;
            case "97": //Temperatura
                if ($solModel->Id_servicio != 3) {
                    if ($solModel->Id_norma == 27) {
                        $gasto = GastoMuestra::where('Id_solicitud',$codigoModel->Id_solicitud)->get();
                        $sumGasto = 0;
                        $aux = array();
                        foreach($gasto as $item)
                        {
                            $sumGasto = $sumGasto + $item->Promedio;
                        }
                        foreach($gasto as $item)
                        {
                            array_push($aux,($item->Promedio/$sumGasto));
                        }
                    }
                    $model = TemperaturaMuestra::where('Id_solicitud', $codigoModel->Id_solicitud)
                        ->where('Activo', 1)->get();   
                }else{
                    $model = LoteDetalleDirectos::where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->get();
                }
                break;

                //Potable
            case 95: // Sulfatos
            case 116:
                $model = DB::table('ViewLoteDetallePotable')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
                //Dureza
            case 77:
            case 251:
            case 252:
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis',$codigoModel->Id_solicitud)->where('Id_parametro',$paraModel->Id_parametro)->get();
                break;
            case 103:
                $model = DB::table('ViewLoteDetalleDureza')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 66: // Color verdadero
            case 65:
            case 98: // Turbiedad
            case 89: // Turbiedad
            case 218: //Cloro
            case 84: // Olor
            case 86: // Sabor
                $model = DB::table('ViewLoteDetalleDirectos')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 114:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            case 69:
                $model = DB::table('ViewLoteDetalleEspectro')->where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_control', 1)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->get();
                break;
            default:
                break;
        }
        $data = array(
            'solModel' => $solModel,
            'aux' => $aux,
            'paraModel' => $paraModel,
            'codigoModel' => $codigoModel,
            'model' => $model,
           
        );
        return response()->json($data);
    }
    public function regresarRes(Request $res)
    {
        $codigoModel = DB::table('ViewCodigoParametro')->where('Id_codigo', $res->idCodigo)->first();
        $paraModel = DB::table('ViewParametros')->where('Id_parametro', $codigoModel->Id_parametro)->first();
        switch ($paraModel->Id_area) {
            case 2:
                $model = LoteDetalle::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalle::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            case 16:
                $model = LoteDetalleEspectro::where('Id_analisis', $codigoModel->Id_solicitud)
                    ->where('Id_parametro', $codigoModel->Id_parametro)->where('Id_control', 1)->get();
                foreach ($model as $item) {
                    $mod = LoteDetalleEspectro::find($item->Id_detalle);
                    $mod->Liberado = 0;
                    $mod->save();
                    $mod2 = CodigoParametros::find($codigoModel->Id_codigo);
                    $mod2->Resultado = NULL;
                    $mod2->save();
                }
                break;
            default:
                # code... 
                $model = "Default";
                break;
        }
        $data = array(
            'paraModel' => $paraModel,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function liberarSolicitud(Request $res)
    {
        $sw = true;
        $model = Solicitud::find($res->idSol);
        if ($res->liberado == true) {
            $model->Liberado = 1;
            $sw = true;
        } else {
            $model->Liberado = 0;
            $sw = false;
        }
        $model->save();

        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }
    public function setHistorial(Request $res)
    {
        $solTemp = Solicitud::where('Id_solicitud',$res->idSol)->first();
        $sw = true;
        $model = CodigoParametros ::where('Codigo','LIKE','%'.$solTemp->Folio_servicio.'%')->get();
        foreach ($model as $item) {
            if ($res->historial == true) {
                $item->Historial = 1; 
                $sw = true;
            } else {
                $item->Historial = 0;
                $sw = false;
            }
            $item->save();   
        }

        $data = array(
            'sw' => $sw,
        );
    

        $model = ProcesoAnalisis::where('Folio','LIKE','%'.$solTemp->Folio_servicio.'%')->get();
        foreach ($model as $item) {
            if ($res->historial == true) {
                $item->Historial_resultado = 1; 
                $sw = true;
            } else {
                $item->Historial_resultado = 0;
                $sw = false;
            }
            $item->save();   
        }

        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }
  
 
    public function actualizarHistorial(Request $request)
    {
        $idSol = $request->input('idSol');
        $historialValor = $request->input('historialValor');

        // Encuentra todos los registros que corresponden a la solicitud
        $parametros = CodigoParametros::where('Id_solicitud', $idSol)->get();
        
        // Actualiza el campo Historial según el valor recibido
        foreach ($parametros as $parametro) {
            $parametro->Historial = $historialValor;
            $parametro->save();
        }

        return response()->json(['success' => true]);
    }

    public function setEmision(Request $res)
    {
        $msg = "";
        try {
            $model = ProcesoAnalisis::where('Id_solicitud',$res->idSol)->first();
            $model->Emision_informe = $res->fecha;
            $model->save();
            $solModel = Solicitud::where('Hijo',$res->idSol)->get();
            foreach ($solModel as $item) {
                $model = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->first();
                $model->Emision_informe = $res->fecha;
                $model->save();
            }
            $msg = "Fecha modificada correctamente";
        } catch (\Throwable $th) {
            $msg = $th;
        }

        $data = array(
            'msg' => $msg,
        );
        return response()->json($data);
    }
    public function getHistorial(Request $res)
    {
        $idHistorial = array();
        $idsLotes = array();
        $fechaLote = array();
        $Codigohist = array();
        $parametrohist = array();
        $resultadoHist = array();
        $historialHist = array();
     
        $sw = 0;

        $codigo = CodigoParametros::where('Id_codigo',$res->idCodigo)->first();
        $solicitud = Solicitud::where('Id_solicitud',$codigo->Id_solicitud)->first();
        $punto = SolicitudPuntos::where('Id_solicitud',$codigo->Id_solicitud)->first();

        $histSol = Solicitud::where('Padre',0)->where('Id_sucursal',$solicitud->Id_sucursal)->orderBy('Id_solicitud','DESC')->get();

        try { 
            foreach ($histSol as $item) 
            {
                $histPunto = SolicitudPuntos::where('Id_solicitud',$item->Id_solicitud)->where('Id_muestreo',$punto->Id_muestreo)->get();
                if ($histPunto->count()) {
                    $res = ProcesoAnalisis::where('Id_solicitud',$item->Id_solicitud)->where('Liberado',1)->get();
                    if ($res->count()) {
                        array_push($idHistorial,$item->Id_solicitud);
                        $sw++;
                    }
                }
                if ($sw == 3) {
                    break;
                }
            }
            for ($i=0; $i < sizeof($histPunto); $i++) { 
                $temp = DB::table('viewcodigoparametro')->where('Id_solicitud',$histPunto[$i]->Id_solicitud)->where('Id_parametro',$codigo->Id_parametro)->first();
                $tempLote = LoteAnalisis::where('Id_lote',@$temp->Id_lote)->first();
                 
                array_push($idsLotes,$tempLote->Id_lote);
                array_push($fechaLote,@$tempLote->Fecha);
                array_push($parametrohist,@$temp->Parametro. "(".@$temp->Tipo_formula.")");
                array_push($Codigohist,@$temp->Codigo);
                array_push($resultadoHist,@$temp->Resultado2);
                array_push($historialHist,@$temp->Historial);
           

            }
        } catch (\Throwable $th) {
            $idsLotes = array();
            $fechaLote = array();
            $Codigohist = array();
            $parametrohist = array();
            $resultadoHist = array();
            $historialHist=array();
         
        }

        $data = array(
            'idsLotes' => $idsLotes,
            'fechaLote' => $fechaLote,
            'Codigohist' => $Codigohist,
            'resultadoHist' => $resultadoHist,
            'parametrohist' => $parametrohist,
            'historialHist' => $historialHist,
        );
        return response()->json($data);
    }
}
