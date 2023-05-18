<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\AnalisisQ\NormaControler;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ClienteGeneral;
use App\Models\ContactoCliente;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;

use App\Models\Cotizacion;
use App\Models\CotizacionEstado;
use App\Models\CotizacionMuestreo;
use App\Models\Norma;
use App\Models\SubNorma;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Frecuencia001;
use App\Models\InformesRelacion;
use App\Models\NormaParametros;
use App\Models\ParametroNorma;
use App\Models\PrecioCatalogo;
use App\Models\PrecioPaquete;
use App\Models\SeguimientoAnalsis;
use App\Models\TipoMuestraCot;
use App\Models\PromedioCot;
use App\Models\Sucursal;
use App\Models\SucursalContactos;
use App\Models\TipoDescarga;
use App\Models\TipoMuestra;
use App\Models\TipoServicios;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use PDF;
use Mpdf\Mpdf;
use PhpParser\Node\Stmt\Return_;

class CotizacionController extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        // $model = DB::table('ViewCotizacionList')->orderBy('Id_cotizacion', 'DESC')->get();
        $model = Cotizacion::orderBy('Id_cotizacion','DESC')->where('Tipo','!=',1)->get();
        $norma = Norma::all();
        $descarga = TipoDescarga::all();
        $estado = CotizacionEstado::all();
        $usuario = User::all();
        $data = array(
            'usuario' => $usuario,
            'model' => $model,
            'norma' => $norma,
            'descarga' => $descarga,
            'estado' => $estado,
        );
        return view('cotizacion.cotizacion', $data); 
    } 
    public function getClientesIntermediarios(Request $res)
    {
        $model = ClienteGeneral::where('Id_intermediario', $res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataCliente(Request $res)
    {
        $model = SucursalCliente::where('Id_sucursal', $res->id)->first();
        $direccion = DireccionReporte::where('Id_sucursal', $model->Id_sucursal)->get();
        $contacto = SucursalContactos::where('Id_sucursal', $model->Id_sucursal)->get();
        $data = array(
            'model' => $model,
            'direccion' => $direccion, 
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function setDatoGeneral(Request $res)
    {
        $model = SucursalContactos::find($res->id);
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSucursal(Request $res)
    {
        $model = SucursalCliente::where('Id_cliente', $res->id)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getNormas(Request $res)
    {
        $model = Norma::where('Id_descarga', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSubNormas(Request $res)
    {
        $model = DB::table("ViewPrecioPaq")->where('Id_norma', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getParametrosNorma(Request $res)
    {
        $model = DB::table('ViewNormaParametro')->where('Id_norma', $res->id)->get();
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getParametrosSelected(Request $res)
    {
        $model = DB::table('cotizacion_parametros')->where('Id_cotizacion', $res->id)->get();
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getFrecuenciaMuestreo(Request $res)
    {
        $model = Frecuencia001::find($res->id);
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDatosCotizacion(Request $res)
    {
        $precioAnalisis = 0;
        $precioCat = 0;
        $extra = 0;

        $precioModel = DB::table('ViewPrecioPaquete')->where('Id_paquete', $res->subnorma)->first();
        switch ($res->frecuencia) {
            case 1:
                $precioAnalisis =  $precioModel->Precio2;
                break;
            case 2:
                $precioAnalisis =  $precioModel->Precio3;
                break;
            case 3:
                $precioAnalisis =  $precioModel->Precio4;
                break;
            case 4:
                $precioAnalisis =  $precioModel->Precio5;
                break;
            case 5:
                $precioAnalisis =  $precioModel->Precio6;
                break;
            case 6:
                $precioAnalisis =  $precioModel->Precio1;
                break;
            default:
                $precioAnalisis =  0;
                break;
        }

        $parametroExtra = CotizacionParametros::where('Id_cotizacion', $res->id)->where('Extra', 1)->get();
        if ($parametroExtra->count()) {
            foreach ($parametroExtra as $item) {
                $extra++;

                $precioModel = DB::table('ViewPrecioCatInter')->where('Id_intermediario', $res->intermediario)->where('Id_catalogo', $item->Id_subnorma)->first();
                if ($precioModel != null) {
                    $precioCat += $precioModel->Precio;
                } else {
                    $precioModel = DB::table('ViewPrecioCat')->where('Id_parametro', $item->Id_subnorma)->first();
                    $precioCat += $precioModel->Precio;
                }
            }
        }


        $data = array(
            'extra' => $extra,
            'temp' => $parametroExtra,
            'precio' => $precioAnalisis,
            'precioCat' => $precioCat,
        );
        return response()->json($data);
    }
    public function getLocalidad()
    {
        $id = $_POST['idLocalidad'];
        $model = DB::table('localidades')->where('Id_estado', $id)->get();
        $cotizacionMuestreo = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $id)->first();

        $data = array(
            'model' => $model,
            'cotizacionMuestreo' => $cotizacionMuestreo,
        );
        return response()->json($data);
    }
    public function cantidadGasolina(Request $request)
    {
        $model = DB::table('costo_muestreo')->first();
        $kmTotal = ($request->km + $request->kmExtra);
        $descaste = $kmTotal * $model->Desgaste_km;
        $gasolinaTeorico = ($kmTotal / $model->Rendimiento);
        $data = array(
            'total' => $gasolinaTeorico,
        );
        return response()->json($data);
    }
    public function setCotizacion(Request $res)
    {

        $model = Cotizacion::where('Id_cotizacion',$res->id)->get();
        $precio = 0;
        if ($model->count()) {
            //Genera folio
                $idCot = $res->id;
                $cotizacion = Cotizacion::find($res->id);

                $precioModel = DB::table('ViewPrecioPaquete')->where('Id_paquete', $res->subnorma)->first();
                switch ($res->frecuencia) {
                    case 1:
                        $precioAnalisis =  $precioModel->Precio2;
                        break;
                    case 2:
                        $precioAnalisis =  $precioModel->Precio3;
                        break;
                    case 3:
                        $precioAnalisis =  $precioModel->Precio4;
                        break;
                    case 4:
                        $precioAnalisis =  $precioModel->Precio5;
                        break;
                    case 5:
                        $precioAnalisis =  $precioModel->Precio6;
                        break;
                    case 6:
                        $precioAnalisis =  $precioModel->Precio1;
                        break;
                    default:
                        $precioAnalisis =  0;
                        break;
                }
              
                if ($res->subnorma != $cotizacion->Id_subnorma || $res->puntosSize != $cotizacion->Numero_puntos) {
                    $precio = ($precioAnalisis * $res->puntosSize);
                }else{
                    $precio = $cotizacion->Precio_analisis;
                }
                
                $cotizacion->Id_intermedio = $res->intermediario;
                $cotizacion->Id_cliente = $res->cliente;
                $cotizacion->Id_sucursal = $res->clienteSucursal;
                $cotizacion->Id_direccion = $res->idDir;
                $cotizacion->Id_general = $res->idGen;
                $cotizacion->Nombre = $res->nomCli;
                $cotizacion->Direccion = $res->dirCli;
                $cotizacion->Atencion = $res->atencion;
                $cotizacion->Telefono = $res->telCli;
                $cotizacion->Correo = $res->correoCli;
                $cotizacion->Tipo_servicio = $res->tipoServicio;
                $cotizacion->Tipo_descarga = $res->tipoDescarga;
                $cotizacion->Id_norma = $res->norma;
                $cotizacion->Id_subnorma = $res->subnorma;
                $cotizacion->Fecha_muestreo = $res->fecha;
                $cotizacion->Frecuencia_muestreo = $res->frecuencia;
                $cotizacion->Tomas = $res->tomas;
                $cotizacion->Tipo_muestra = $res->tipoMuestra;
                $cotizacion->Promedio = $res->promedio;
                $cotizacion->Tipo_reporte = $res->tipoReporte;
                $cotizacion->Numero_puntos = $res->puntosSize;
                $cotizacion->Precio_analisis = $precioAnalisis;
                $cotizacion->Estado_cotizacion = 1;
                $cotizacion->Actualizado_por = Auth::user()->id;
                $cotizacion->save();

                if ($res->paramSize > 0) {
                    DB::table('cotizacion_parametros')->where('Id_cotizacion', $res->id)->delete();
                    for ($i = 0; $i < sizeof($res->parametros); $i++) {
                        $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();
                        $chParam = 0;
                        $extra = 0;
                        if ($subnorma->count() > 0) {
                            $extra = 0;
                        } else {
                            $extra = 1;
                        }
                        if($res->chParam[$i] == "true"){
                            $chParam = 1;
                        }else{
                            $chParam = 0;
                        }
                        CotizacionParametros::create([
                            'Id_cotizacion' => $res->id,
                            'Id_subnorma' => $res->parametros[$i],
                            'Extra' => $extra,
                            'Reporte' => $chParam,
                        ]);
                    }
                }else{
                   
                }
        } else {            
            $precioModel = DB::table('ViewPrecioPaquete')->where('Id_paquete', $res->subnorma)->first();
            switch ($res->frecuencia) {
                case 1:
                    $precioAnalisis =  $precioModel->Precio2;
                    break;
                case 2:
                    $precioAnalisis =  $precioModel->Precio3;
                    break;
                case 3:
                    $precioAnalisis =  $precioModel->Precio4;
                    break;
                case 4:
                    $precioAnalisis =  $precioModel->Precio5;
                    break;
                case 5:
                    $precioAnalisis =  $precioModel->Precio6;
                    break;
                case 6:
                    $precioAnalisis =  $precioModel->Precio1;
                    break;
                default:
                    $precioAnalisis =  0;
                    break;
            }
            $cotizacion = Cotizacion::create([
                'Id_intermedio' => $res->intermediario,
                'Id_cliente' => $res->cliente,
                'Id_sucursal' => $res->clienteSucursal,
                'Id_direccion' => $res->idDir,
                'Id_general' => $res->idGen,
                'Nombre' => $res->nomCli,
                'Direccion' => $res->dirCli,
                'Atencion' => $res->atencion,
                'Telefono' => $res->telCli,
                'Correo' => $res->correoCli,
                'Tipo_servicio' => $res->tipoServicio,
                'Tipo_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma, 
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fecha,
                'Frecuencia_muestreo' => $res->frecuencia,
                'Tomas' => $res->tomas,
                'Tipo_muestra' => $res->tipoMuestra,
                'Promedio' => $res->promedio,
                'Tipo_reporte' => $res->tipoReporte,
                'Numero_puntos' => $res->puntosSize,
                'Estado_cotizacion' => 1,
                'Num_servicios' => 1,
                'Precio_analisis' => $precioAnalisis,
                'Tipo' => 0,
                'Creado_por' => Auth::user()->id,
                'Actualizado_por' => Auth::user()->id,
            ]);
            $idCot = $cotizacion->Id_cotizacion;

            if ($res->paramSize > 0) {
                for ($i = 0; $i < sizeof($res->parametros); $i++) {
                    $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();
                    $chParam = 0;
                    $extra = 0;
                    if ($subnorma->count() > 0) {
                        $extra = 0;
                    } else {
                        $extra = 1;
                    }
                    if($res->chParam[$i] == "true"){
                        $chParam = 1;
                    }else{
                        $chParam = 0;
                    }
                    CotizacionParametros::create([
                        'Id_cotizacion' => $idCot,
                        'Id_subnorma' => $res->parametros[$i],
                        'Extra' => $extra,
                        'Reporte' => $chParam,
                        'precioAnalisis' => $precioAnalisis,
                    ]);
                }
        
            }else{
                
            }

            $precio = ($precioAnalisis * $res->puntosSize);
                
        }

        
       if ($res->puntosSize > 0) {
        DB::table('cotizacion_puntos')->where('Id_cotizacion', $res->id)->delete();
        for ($i = 0; $i < sizeof($res->puntos); $i++) {
            CotizacionPunto::create([
                'Id_cotizacion' => $idCot,
                'Descripcion' => $res->puntos[$i],
            ]);
        }
        $temp = Cotizacion::find($idCot);
        $temp->Precio_analisis = $precio;
        $temp->save();
       }


        $data = array(
            'precio' => $precio,
            'model' => $cotizacion,
        );
        return response()->json($data);
    }

    public function setGenFolio(Request $res)
    {
        $cotTemp = Cotizacion::where('Id_cotizacion',$res->id)->get();
        $msg = "No se puede generar folio";
        if ($cotTemp->count()) {     
            if ($cotTemp[0]->Folio == NULL) { 
                $temp = strtotime($res->fecha);
                $year = date("y", $temp); 
                $dayYear = date("z", $temp) + 1;
                $cotizacionDay = Cotizacion::where('Fecha_cotizacion',$res->fecha)->where('Folio','!=','')->count();
        
                $folio = $dayYear . "-" . ($cotizacionDay + 1) . "/" . $year;
                
                $model = Cotizacion::find($res->id);
                $model->Folio = $folio;
                $model->Fecha_cotizacion = $res->fecha;
                $model->save();
                $msg = "Folio creado correctamente";
            }else{
                $msg = "Esta cotizacion ya tiene folio registrado";
                $folio = $cotTemp[0]->Folio;
                $model = "";
            }
        }

        $data = array(
            'msg' => $msg,
            'folio' => $folio,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPrecioMuestreo(Request $request)
    {
        $model = DB::table('costo_muestreo')->first();
        $kmTotal = ($request->km + $request->kmExtra);
        $desgaste = $kmTotal * $model->Desgaste_km;
        $gasolinaTeorico = ($kmTotal / $model->Rendimiento);
        $costoViaticos = (($request->hospedaje * $request->diasHospedaje) + ($request->caseta) + ($model->Comida * $request->diasMuestreo * $request->numMuestreador) + ($model->Gasolina * $request->cantidadGasolina));
        $costoSuma = ($costoViaticos + ($model->Pago_muestreador * $request->diasMuestreo * $request->numMuestreador) + $desgaste + $model->Insumo);
        $costoTotal = ($costoSuma * (1 + $model->Ganancia));
        $costoTotal += ($request->paqueteria + $request->gastosExtras);

        $temp = CotizacionMuestreo::where('Id_cotizacion',$request->id)->get();
        if ($temp->count()) {
            $cot = CotizacionMuestreo::find($temp[0]->Id_muestreo);
            $cot->Dias_hospedaje = $request->diasHospedaje;
            $cot->Hospedaje = $request->hospedaje;
            $cot->Dias_muestreo = $request->diasMuestreo;
            $cot->Num_muestreo = $request->numeroMuestreo;
            $cot->Caseta = $request->caseta;
            $cot->Km = $request->km;
            $cot->Km_extra = $request->kmExtra;
            $cot->Gasolina_teorico = $gasolinaTeorico;
            $cot->Cantidad_gasolina = $request->cantidadGasolina;
            $cot->Paqueteria = $request->paqueteria;
            $cot->Adicional = $request->gastosExtras;
            $cot->Num_servicio = $request->numeroServicio;
            $cot->Num_muestreador = $request->numMuestreador;
            $cot->Estado = $request->estado;
            $cot->Localidad = $request->localidad;
            $cot->Total = $costoTotal;
            $cot->save();
        } else {
            CotizacionMuestreo::create([
                'Id_cotizacion' => $request->id,
                'Dias_hospedaje' => $request->diasHospedaje,
                'Hospedaje' => $request->hospedaje,
                'Dias_muestreo' => $request->diasMuestreo,
                'Num_muestreo' => $request->numeroMuestreo,
                'Caseta' => $request->caseta,
                'Km' => $request->km,
                'Km_extra' => $request->kmExtra,
                'Gasolina_teorico' => $gasolinaTeorico,
                'Cantidad_gasolina' => $request->cantidadGasolina,
                'Paqueteria' => $request->paqueteria,
                'Adicional' => $request->gastosExtras,
                'Num_servicio' => $request->numeroServicio,
                'Num_muestreador' => $request->numMuestreador,
                'Estado' => $request->estado,
                'Localidad' => $request->localidad,
                'Total' => $costoTotal
            ]);
        }
        

        $data = array(
            'descaste' => $desgaste,
            'viaticos' => $costoViaticos,
            'costoSuma' => $costoSuma,
            'total' => $costoTotal,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get();
        $categorias001 = DB::table('categorias001')->get();
        $categorias0012 = DB::table('categoria001_2021')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();



        $data = array(
            'categorias0012' => $categorias0012,
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'metodoPago' => $metodoPago,
            'show' => true,
        );
        return view('cotizacion.create', $data);
    }
    public function update($id)
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $categorias001 = DB::table('categorias001')->get();
        $categorias0012 = DB::table('categoria001_2021')->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $cotizacionPuntos = CotizacionPunto::where('Id_cotizacion', $id)->get();
        $cotizacionMuestreo = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $id)->first();
        $model = Cotizacion::where('Id_cotizacion', $id)->first();
        $data = array(
            'model' => $model,
            'cotizacionPuntos' => $cotizacionPuntos,
            'categorias0012' => $categorias0012,
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'muestreo' => @$cotizacionMuestreo,
            'metodoPago' => $metodoPago,
            'show' => true,
        );
        return view('cotizacion.create', $data);
    }
    public function show($id)
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get();
        $categorias001 = DB::table('categoria001_2021')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $id)->first();
        $cotizacionPuntos = CotizacionPunto::where('Id_cotizacion', $id)->get();
        $cotizacionMuestreo = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $id)->first();
        $data = array(
            'model' => $model,
            'cotizacionPuntos' => $cotizacionPuntos,
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'muestreo' => $cotizacionMuestreo,
            'metodoPago' => $metodoPago,
            'show' => false,
        );
        return view('cotizacion.create', $data);
    }
    public function updateParametroCot(Request $res)
    {
        DB::table('cotizacion_parametros')->where('Id_cotizacion', $res->id)->delete();
        for ($i = 0; $i < sizeof($res->param); $i++) {
            $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->param[$i]["id"])->get();
 
            $extra = 0;
            if ($subnorma->count()) {
                $extra = 0;
            } else {
                $extra = 1;
            }

            CotizacionParametros::create([
                'Id_cotizacion' => $res->id,
                'Id_subnorma' => $res->param[$i]["id"],
                'Extra' => $extra,
                // 'Reporte' => $subnorma[0]->Reporte,
            ]);
        }
        $parametro = DB::table('ViewCotParam')->where('Id_cotizacion', $res->id)->get();
        $precioCat = 0;
        
        $parametroExtra = CotizacionParametros::where('Id_cotizacion', $res->id)->where('Extra', 1)->get();
        if ($parametroExtra->count()) {
            foreach ($parametroExtra as $item) {
                $extra++;

                $precioModel = DB::table('ViewPrecioCatInter')->where('Id_intermediario', $res->intermediario)->where('Id_catalogo', $item->Id_subnorma)->first();
                if ($precioModel != null) {
                    $precioCat += $precioModel->Precio;
                } else {
                    $precioModel = DB::table('ViewPrecioCat')->where('Id_parametro', $item->Id_subnorma)->first();
                    $precioCat += $precioModel->Precio;
                }
            }
        }
        $temp = Cotizacion::find($res->id);
        $precio = ($precioCat * $temp->Numero_puntos);
        $temp->Precio_catalogo = $precio;
        $temp->save();
        $data = array(
            'precioCat' => $precio,
            'parametro' => $parametro,
        );
        return response()->json($data);
    }
    public function getDataUpdate(Request $res)
    {
        $model = Cotizacion::where('Id_cotizacion', $res->id)->first();
        $parametro = DB::table('ViewCotParam')->where('Id_cotizacion', $res->id)->get();
        $data = array(
            'model' => $model,
            'parametros' => $parametro,
        );
        return response()->json($data);
    }
    public function comprobarEdicion(Request $res)
    {
        $sw = false;
        $model = Cotizacion::find($res->id);
        if ($model->Folio_servicio != NULL) {
            $sw = true;
        } else {
            $sw = false;
        }

        $data = array('sw' => $sw);
        return response()->json($data);
    }
    public function setPrecioCotizacion(Request $res)
    {
        $model = Cotizacion::find($res->id);
        $model->Observacion_interna = $res->obsInt;
        $model->Observacion_cotizacion = $res->obsCot;
        $model->Metodo_pago = $res->metodoPago;
        $model->Precio_analisis = $res->precioAnalisis;
        $model->Precio_catalogo = $res->precioCat;
        $model->Descuento = $res->descuento;
        $model->Precio_analisisCon = $res->precioAnalisisCon;
        $model->Precio_muestreo = $res->precioMuestra;
        $model->Extras = $res->gastosExtras;
        $model->Num_servicios = $res->numServicio;
        $model->Paqueteria = $res->paqueteria;
        $model->Iva = $res->iva;
        $model->Sub_total = $res->subTotal;
        $model->Costo_total = $res->precioTotal;
        $model->save();
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
        
    }
    public function duplicar($idCot)
    {


        $cotOriginal = Cotizacion::where('Id_cotizacion', $idCot)->first();
        $cotReplicada = $cotOriginal->replicate();

        $cotReplicada->Folio_servicio = NULL;
        $cotReplicada->Folio = NULL;
        $cotReplicada->Fecha_cotizacion = NULL;
        $cotReplicada->Creado_por = Auth::user()->id;
        $cotReplicada->Actualizado_por = Auth::user()->id;
        $cotReplicada->created_at = Carbon::now();
        $cotReplicada->updated_at = Carbon::now();
        $cotReplicada->save();

        $model = CotizacionPunto::where('Id_cotizacion',$idCot)->get();
        if ($model->count()) {
            $cotPuntoOriginal = CotizacionPunto::where('Id_cotizacion', $idCot)->get();

            foreach ($cotPuntoOriginal as $item) {
                $cotPuntoDuplicada = $item->replicate();
                $cotPuntoDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
                $cotPuntoDuplicada->save();
            }
        }
        
  

        $model = CotizacionMuestreo::where('Id_cotizacion',$idCot)->get();
        if ($model->count()) { 
            $cotMuesOriginal = CotizacionMuestreo::where('Id_cotizacion', $idCot)->get();

            foreach ($cotMuesOriginal as $item) {
                $cotMuesDuplicada = $item->replicate();
                $cotMuesDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
                $cotMuesDuplicada->save();
            }
        }

        $model = CotizacionParametros::where('Id_cotizacion',$idCot)->get();
        if ($model->count()) {
            $cotParamOriginal = CotizacionParametros::where('Id_cotizacion', $idCot)->get();

            foreach ($cotParamOriginal as $item) {
                $cotParamDuplicada = $item->replicate();
                $cotParamDuplicada->Id_cotizacion = $cotReplicada->Id_cotizacion;
                $cotParamDuplicada->save();
            }
    
        }

        echo "<script>alert('Cotización duplicada exitosamente!');</script>";
        return redirect()->to('admin/cotizacion');
    }

    public function exportPdfOrden($idCot)
    {

        //Recupera los parámetros de la cotización
        $parametros = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 0)->orderBy('Parametro', 'ASC')->get();

        //Recupera los parámetros extra de la cotización
        $parametrosExtra = DB::table('ViewCotParam')->where('Id_cotizacion', $idCot)->where('Extra', 1)->orderBy('Parametro', 'ASC')->get();

        $model = Cotizacion::where('Id_cotizacion', $idCot)->first();
        $norma = Norma::where('Id_norma', $model->Id_norma)->first();
        $puntos = CotizacionPunto::where('Id_cotizacion', $idCot)->get();
        $relacion = InformesRelacion::where('Id_cotizacion', $model->Id_cotizacion)->get();
        $reportesInformes = DB::table('ViewReportesCotizacion')->orderBy('Num_rev', 'desc')->first();       
    

        $analisisDesc = $model->Precio_analisis - (($model->Precio_analisis * $model->Descuento) / 100);

        $servicio = TipoServicios::all();
        $tipo = TipoMuestraCot::all();
        $numServicios = $model->Num_servicios * $puntos->count();

 
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 20,
            'margin_bottom' => 25
        ]);
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $firma = User::find(24); // Firma maribel
        $mpdf->showWatermarkImage = true;
        $html = view('exports.cotizacion.cotizacion', compact('numServicios','model','tipo' ,'servicio' 
        ,'parametros', 'parametrosExtra', 'norma', 'puntos',  'analisisDesc',  'firma','reportesInformes'));
        $mpdf->CSSselectMedia = 'mpdf';

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
