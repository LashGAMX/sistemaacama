<?php
namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\AnalisisQ\LimiteParametros001;
use App\Http\Livewire\AnalisisQ\Normas;
use App\Models\CampoCompuesto;
use App\Models\ClienteGeneral;
use App\Models\ClienteSiralab;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\ContactoCliente;
use App\Models\Cotizacion;
use App\Models\CotizacionEstado;
use App\Models\CotizacionMuestreo;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\Frecuencia001;
use App\Models\Intermediario;
use App\Models\Norma;
use App\Models\NormaParametros;
use App\Models\PhMuestra;
use App\Models\PuntoMuestreoGen;
use App\Models\PuntoMuestreoSir;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SubNorma;
use App\Models\SucursalCliente;
use App\Models\TipoDescarga;
use App\Models\TipoServicios;
use App\Models\TipoMuestraCot;
use App\Models\PromedioCot;
use App\Models\SucursalContactos;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        if (Auth::user()->role->id == 13) {
            $model = Cotizacion::orderBy('Id_cotizacion','DESC')->where('Creado_por', Auth::user()->id)->get();
        } else {
            $model = Cotizacion::orderBy('Id_cotizacion','DESC')->get();
        }
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
        return view('cotizacion.solicitud', $data); 
    }
    public function buscarFecha($inicio, $fin)
    {
        $model = DB::table('ViewCotizacion')->whereBetween('created_at', [$inicio, $fin])->get();
        return view('cotizacion.solicitud', compact('model'));
    }

    public function create($idCot)
    { 
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
        $intermediario = DB::table('ViewIntermediarios')->get();
        $categorias001 = DB::table('categorias001')->get();
        $categorias0012 = DB::table('categoria001_2021')->get();
        $data = array(
            'model' => $model,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'servicio' => $servicios,
            'descargas' => $descargas,
            'categorias001' => $categorias001,
            'categorias0012' => $categorias0012,
            'frecuencia' => $frecuencia,
            'intermediario' => $intermediario,
        );

        return view('cotizacion.createSolicitud', $data);
    }
    
    public function createOrden()
    {
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        // $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
        $intermediario = DB::table('ViewIntermediarios')->get();
        $categorias001 = DB::table('categorias001')->get();
        $categorias0012 = DB::table('categoria001_2021')->get();
        $data = array(
            'categorias0012' => $categorias0012,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'servicio' => $servicios,
            'descargas' => $descargas,
            'categorias001' => $categorias001,
            'frecuencia' => $frecuencia,
            'intermediario' => $intermediario,
        );
 
        return view('cotizacion.createOrden', $data);
    }
    public function updateOrden($id)
    {
        $model = Solicitud::where('Id_cotizacion', $id)->where('Padre',1)->first();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all(); 
        $servicios = TipoServicios::all();
        $descargas = TipoDescarga::all();
        $frecuencia = DB::table('frecuencia001')->get();
        // $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $idCot)->first();
        $intermediario = DB::table('ViewIntermediarios')->get();
        $categorias001 = DB::table('categorias001')->get();
        $categorias0012 = DB::table('categoria001_2021')->get();
        $data = array(
            'model' => $model,
            'categorias0012' => $categorias0012,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'servicio' => $servicios,
            'descargas' => $descargas,
            'categorias001' => $categorias001,
            'frecuencia' => $frecuencia,
            'intermediario' => $intermediario,
        );

        return view('cotizacion.createOrden', $data);
    }
    public function getDatoIntermediario(Request $res)
    {
        $model = DB::table('ViewIntermediarios')->where('Id_intermediario', $res->id)->first();
        $generales = ClienteGeneral::where('Id_intermediario', $res->id)->get();
        $data = array(
            'model' => $model,
            'generales' => $generales,
        );
        return response()->json($data);
    }
    public function getDataCotizacion(Request $res)
    {
        $parametro = DB::table('ViewCotParam')->where('Id_cotizacion', $res->id)->get();
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $res->id)->first();
        $data = array(
            'model' => $model,
            'parametro' => $parametro
        );
        return response()->json($data);
    }
    public function getClienteRegistrado(Request $res)
    {
        $model = ClienteGeneral::where('Id_intermediario', $res->id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getSucursalCliente(Request $res)
    {
        $sucursal = SucursalCliente::where('Id_cliente', $res->id)->get();
        $data = array(
            'idCliente' => $res->cliente,
            'model' => $sucursal,
        );
        return response()->json($data);

    }
    public function getDireccionReporte(Request $res)
    {

        if ($res->siralab == "true") {
            $model = DB::table('ViewDireccionSir')->where('Id_sucursal', $res->id)->get();
        } else {
            $model = DireccionReporte::where('Id_sucursal', $res->id)->get();
        }
        $contacto = SucursalContactos::where('Id_sucursal', $res->id)->get();
        $data = array(
            'model' => $model,
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function setContacto(Request $res)
    {
        ContactoCliente::create([
            'Id_cliente' => $res->id,
            'Nombres' => $res->nombre,
            'A_paterno' => $res->paterno,
            'A_materno' => $res->materno,
            'Celular' => $res->celular,
            'Telefono' => $res->telefono,
            'Email' => $res->correo,
        ]);

        $model = ContactoCliente::Where('Id_cliente', $res->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getDataContacto(Request $res)
    {
        $model = SucursalContactos::where('Id_contacto', $res->id)->first();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function storeContacto(Request $request)
    {
        $model = ContactoCliente::find($request->idContacto);
        $model->Nombres = $request->nombre;
        $model->A_paterno = $request->paterno;
        $model->A_materno = $request->materno;
        $model->Celular = $request->celular;
        $model->Telefono = $request->telefono;
        $model->Email = $request->correo;
        $model->save();

        $model = ContactoCliente::Where('Id_cliente', $request->idCliente)->get();

        $data = array(
            'model' => $model,
        );

        return response()->json($data);
    }
    public function getPuntoMuestro(Request $res)
    {
        if ($res->siralab == "true") {
            $model = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal', $res->idSuc)->get();
        } else {
            $model = PuntoMuestreoGen::where('Id_sucursal', $res->idSuc)->get();
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setSolicitudSinCot(Request $res)
    {
        $temp = Cotizacion::find($res->id);
        $temp->Observacion_interna = $res->obsInt;
        $temp->Observacion_cotizacion = $res->obsCot;
        $temp->Metodo_pago = $res->metodoPago;
        $temp->Precio_analisis = $res->precioAnalisis;
        $temp->Precio_catalogo = $res->precioCat;
        $temp->Descuento = $res->descuento;
        $temp->Precio_analisisCon = $res->precioAnalisisCon;
        $temp->Precio_muestreo = $res->precioMuestra;
        $temp->Iva = $res->iva;
        $temp->Sub_total = $res->subTotal;
        $temp->Costo_total = $res->precioTotal;
        $temp->save();

        $year = date("y");
        $month = date("m");
        $dayYear = date("z") + 1;
        $today = Carbon::now()->format('Y-m-d');
        $solicitudDay = DB::table('solicitudes')->whereDate('created_at', $today)->where('Padre', 1)->count();


        $numCot = DB::table('solicitudes')->whereDate('created_at', $today)->where('Id_cliente', $res->clientes)->get();
        $firtsFol = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->where('Id_cliente', $res->clientes)->first();
        $cantCot = $numCot->count();

        //var_dump($numCot);
        if ($cantCot > 0) {
            echo "Entro a if <br>";
            $hijo = 1;
            // $folio = $firtsFol->Folio_servicio . '-' . ($cantCot + 1);
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        } else {
            echo "Entro a else <br>";
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        }
        //var_dump($folio);
        // Convertir cadena a array de datos

        if ($res->id > 0) {
            if ($res->siralab != NULL) {
                $siralab = 1;
            } else {
                $siralab = 0;
            }

            $model = Solicitud::create([
                'Id_cotizacion' => $res->id,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $res->inter,
                'Id_cliente' => $res->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $res->sucursal,
                'Id_direccion' => $res->direccionReporte,
                'Id_contacto' => $res->contacto,
                'Atencion' => $res->atencion,
                'Observacion' => $res->observacion,
                'Id_servicio' => $res->tipoServicio,
                'Id_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma,
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Id_muestreo' => $res->frecuencia,
                'Num_tomas' => $res->numTomas,
                'Id_muestra' => $res->tipoMuestra,
                'Id_promedio' => $res->promedio,
                'Id_user_c' => Auth::user()->id,
                'Padre' => 1,
                'Hijo' => 0,
            ]);

            // var_dump($model->Id_solicitud);
            $contPuntos = 0;
            DB::table('solicitud_puntos')->where('Id_solicitud', $model->Id_solicitud)->delete();
            for ($i = 0; $i < sizeof($res->puntos); $i++) {
                SolicitudPuntos::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_muestreo' =>  $res->puntos[$i]
                ]);
                $contPuntos++;
            }

            for ($i = 0; $i < sizeof($res->parametros); $i++) {
                $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                $extra = 0;
                if ($subnorma->count() > 0) {
                    $extra = 0;
                } else {
                    $extra = 1;
                }
                if ($res->chParam[$i] == "true") {
                    $chParam = 1;
                } else {
                    $chParam = 0;
                }
                SolicitudParametro::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_subnorma' => $res->parametros[$i],
                    'Extra' => $extra,
                    'Reporte' => $chParam,
                ]);
            }

            //Actualiza la cotización de estado
            $cotModel = Cotizacion::find($res->id);
            $cotModel->Folio_servicio = $model->Folio_servicio;
            $cotModel->Estado_cotizacion = 2;
            $cotModel->save();


            //todo Inicia seguimiento de analisis
            SeguimientoAnalisis::create([
                'Id_servicio' => $model->Id_solicitud,
                'Obs_solicitud' => '',
            ]);

            if ($contPuntos > 0) {
                for ($i = 0; $i < $contPuntos; $i++) {
                    if ($res->siralab != NULL) {
                        $siralab = 1;
                    } else {
                        $siralab = 0;
                    }
                    $model2 = Solicitud::create([
                        'Id_cotizacion' => $res->id,
                        'Folio_servicio' => $folio . '-' . ($i + 1),
                        'Id_intermediario' => $res->inter,
                        'Id_cliente' => $res->clientes,
                        'Siralab' => $siralab,
                        'Id_sucursal' => $res->sucursal,
                        'Id_direccion' => $res->direccionReporte,
                        'Id_contacto' => $res->contacto,
                        'Atencion' => $res->atencion,
                        'Observacion' => $res->observacion,
                        'Id_servicio' => $res->tipoServicio,
                        'Id_descarga' => $res->tipoDescarga,
                        'Id_norma' => $res->norma,
                        'Id_subnorma' => $res->subnorma,
                        'Fecha_muestreo' => $res->fechaMuestreo,
                        'Id_muestreo' => $res->frecuencia,
                        'Num_tomas' => $res->numTomas,
                        'Id_muestra' => $res->tipoMuestra,
                        'Id_promedio' => $res->promedio,
                        'Padre' => 0,
                        'Hijo' => $model->Id_solicitud
                    ]);
                    SolicitudPuntos::create([
                        'Id_solPadre' => $model->Id_solicitud,
                        'Id_solicitud' => $model2->Id_solicitud,
                        'Id_muestreo' => $res->puntos[$i]
                    ]);


                    for ($i = 0; $i < sizeof($res->parametros); $i++) {
                        $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                        $extra = 0;
                        if ($subnorma->count() > 0) {
                            $extra = 0;
                        } else {
                            $extra = 1;
                        }
                        if ($res->chParam[$i] == "true") {
                            $chParam = 1;
                        } else {
                            $chParam = 0;
                        }
                        SolicitudParametro::create([
                            'Id_solicitud' => $model2->Id_solicitud,
                            'Id_subnorma' => $res->parametros[$i],
                            'Extra' => $extra,
                            'Reporte' => $chParam,
                        ]);
                    }
                }
            }
        } else {
        }

        $data = array(
            'punto' => $res,
        );
        return response()->json($data);
    }
    public function setSolicitud(Request $res)
    {
        $temp = strtotime($res->fechaMuestreo);
        $year = date("y", $temp);
        $month = date("m");
        $dayYear = date("z", $temp) + 1;
        $today = Carbon::now()->format('Y-m-d');
        // $solicitudDay = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$today}%")->count();
        $solicitudDay = DB::table('solicitudes')->whereDate('Fecha_muestreo', $res->fechaMuestreo)->where('Padre', 1)->count();


        $numCot = DB::table('solicitudes')->whereDate('created_at', $res->fechaMuestreo)->where('Id_cliente', $res->clientes)->get();
        $firtsFol = DB::table('solicitudes')->where('created_at', 'LIKE', "%{$res->fechaMuestreo}%")->where('Id_cliente', $res->clientes)->first();
        $cantCot = $numCot->count();

        //var_dump($numCot);
        if ($cantCot > 0) {
            echo "Entro a if <br>";
            $hijo = 1;
            // $folio = $firtsFol->Folio_servicio . '-' . ($cantCot + 1);
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        } else {
            echo "Entro a else <br>";
            $folio = $dayYear . "-" . ($solicitudDay + 1) . "/" . $year;
        }
        //var_dump($folio);
        // Convertir cadena a array de datos

        if ($res->id > 0) {
            if ($res->siralab != 0) {
                $siralab = 1;
            } else {
                $siralab = 0;
            }

            $model = Solicitud::create([
                'Id_cotizacion' => $res->id,
                'Folio_servicio' => $folio,
                'Id_intermediario' => $res->inter,
                'Id_cliente' => $res->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $res->sucursal,
                'Id_direccion' => $res->direccionReporte,
                'Id_contacto' => $res->contacto,
                'Atencion' => $res->atencion,
                'Observacion' => $res->observacion,
                'Id_servicio' => $res->tipoServicio,
                'Id_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma,
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Id_muestreo' => $res->frecuencia,
                'Num_tomas' => $res->numTomas,
                'Id_muestra' => $res->tipoMuestra,
                'Id_promedio' => $res->promedio,
                'Id_user_c' => Auth::user()->id,
                'Padre' => 1,
                'Hijo' => 0,
            ]);

            $contPuntos = 0;
            DB::table('solicitud_puntos')->where('Id_solicitud', $model->Id_solicitud)->delete();
            for ($i = 0; $i < sizeof($res->puntos); $i++) {
                SolicitudPuntos::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_muestreo' =>  $res->puntos[$i]
                ]);
                $contPuntos++;
            }

            for ($i = 0; $i < sizeof($res->parametros); $i++) {
                $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                $extra = 0;
                if ($subnorma->count() > 0) {
                    $extra = 0;
                } else {
                    $extra = 1;
                }
                if ($res->chParam[$i] == "true") {
                    $chParam = 1;
                } else {
                    $chParam = 0;
                }
                SolicitudParametro::create([
                    'Id_solicitud' => $model->Id_solicitud,
                    'Id_subnorma' => $res->parametros[$i],
                    'Extra' => $extra,
                    'Reporte' => $chParam,
                ]);
            }

            //Actualiza la cotización de estado
            $cotModel = Cotizacion::find($res->id);
            $cotModel->Folio_servicio = $model->Folio_servicio;
            $cotModel->Estado_cotizacion = 2;
            $cotModel->save();


            //todo Inicia seguimiento de analisis
            SeguimientoAnalisis::create([
                'Id_servicio' => $model->Id_solicitud,
                'Obs_solicitud' => '',
            ]);

            if ($contPuntos > 0) {
                for ($i = 0; $i < $contPuntos; $i++) {
                    if ($res->siralab != 0) {
                        $siralab = 1;
                    } else {
                        $siralab = 0;
                    }
                    $model2 = Solicitud::create([
                        'Id_cotizacion' => $res->id,
                        'Folio_servicio' => $folio . '-' . ($i + 1),
                        'Id_intermediario' => $res->inter,
                        'Id_cliente' => $res->clientes,
                        'Siralab' => $siralab,
                        'Id_sucursal' => $res->sucursal,
                        'Id_direccion' => $res->direccionReporte,
                        'Id_contacto' => $res->contacto,
                        'Atencion' => $res->atencion,
                        'Observacion' => $res->observacion,
                        'Id_servicio' => $res->tipoServicio,
                        'Id_descarga' => $res->tipoDescarga,
                        'Id_norma' => $res->norma,
                        'Id_subnorma' => $res->subnorma,
                        'Fecha_muestreo' => $res->fechaMuestreo,
                        'Id_muestreo' => $res->frecuencia,
                        'Num_tomas' => $res->numTomas,
                        'Id_muestra' => $res->tipoMuestra,
                        'Id_promedio' => $res->promedio,
                        'Padre' => 0,
                        'Hijo' => $model->Id_solicitud
                    ]);
                    SolicitudPuntos::create([
                        'Id_solPadre' => $model->Id_solicitud,
                        'Id_solicitud' => $model2->Id_solicitud,
                        'Id_muestreo' => $res->puntos[$i]
                    ]);


                    for ($i = 0; $i < sizeof($res->parametros); $i++) {
                        $subnorma = NormaParametros::where('Id_norma', $res->subnorma)->where('Id_parametro', $res->parametros[$i])->get();

                        $extra = 0;
                        if ($subnorma->count() > 0) {
                            $extra = 0;
                        } else {
                            $extra = 1;
                        }
                        if ($res->chParam[$i] == "true") {
                            $chParam = 1;
                        } else {
                            $chParam = 0;
                        }
                        SolicitudParametro::create([
                            'Id_solicitud' => $model2->Id_solicitud,
                            'Id_subnorma' => $res->parametros[$i],
                            'Extra' => $extra,
                            'Reporte' => $chParam, 
                        ]);
                    } 
                }
            }
        } else {
        }

        $data = array(
            'punto' => $folio,
        );
        return response()->json($data);
        // return redirect()->to('admin/cotizacion/solicitud');
    }
    public function setGenFolio(Request $request)
    {

        $sw = false;
        $coliforme = false;
        $ga = false;
        $dbo = false;
        $dqo = false;
        $modelPadre = DB::table('ViewSolicitud')->where('Id_cotizacion', $request->idCot)->where('Padre', 1)->first();
        $model = DB::table('ViewSolicitud')->where('Hijo', $modelPadre->Id_solicitud)->get();


        foreach ($model as $item) {
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 13)->get();
            if ($solParam->count()) {
                $ga = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 12)->get();
            if ($solParam->count()) {
                $coliforme = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 5)->get();
            if ($solParam->count()) {
                $dbo = true;
            }
            $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $item->Id_solicitud)->where('Id_parametro', 6)->get();
            if ($solParam->count()) {
                $dqo = true;
            }
        }
        $phMuestra = PhMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();
        $campo = CampoCompuesto::where('Id_solicitud', $item->Id_solicitud)->first();
        $conduc = ConductividadMuestra::where('Id_solicitud', $item->Id_solicitud)->where('Activo', 1)->get();
        $promConduc = 0;
        $aux = 0; 
        foreach ($conduc as $item) {
            $promConduc = $promConduc + $item->Promedio;
            $aux++;
        }
        $promConduc = $promConduc / $aux;

        if ($phMuestra->count()) {
            foreach ($model as $value) {
                # code...
                $sw = false;
                $cont = 0;
                $swCodigo = CodigoParametros::where('Id_solicitud', $value->Id_solicitud)->get();
                $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $value->Id_solicitud)->get();

                if ($swCodigo->count()) {
                    $sw = true;
                } else {
                    foreach ($solParam as $item) {

                        switch ($item->Id_parametro) {
                            case 13:
                                // G&A
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-G-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => $item->Reporte,
                                        ]);
                                    }
                                }
                                break;
                            case 12:
                            case 78:
                                // Coliformes
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-C-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => $item->Reporte,
                                        ]);
                                    }
                                }
                                break;
                            case 103: //Dureza
                                for ($i = 0; $i < 3; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $value->Id_solicitud,
                                        'Id_parametro' => $item->Id_parametro,
                                        'Codigo' => $value->Folio_servicio . "-DU-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Cadena' => 0,
                                        'Reporte' => $item->Reporte,
                                    ]);
                                }
                                break;
                            case 5:
                                // DBO
                                for ($i = 0; $i < 3; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $value->Id_solicitud,
                                        'Id_parametro' => $item->Id_parametro,
                                        'Codigo' => $value->Folio_servicio . "-D-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Cadena' => 0,
                                        'Reporte' => $item->Reporte,
                                    ]);
                                }
                                break;
                            case 6:
                                // DQO
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);
                                // if ($model[0]->Id_norma == "27") {
                                //     if ($campo->Cloruros < 1000) {
                                //         CodigoParametros::create([
                                //             'Id_solicitud' => $value->Id_solicitud,
                                //             'Id_parametro' => $item->Id_parametro,
                                //             'Codigo' => $value->Folio_servicio,
                                //             'Num_muestra' => 1,
                                //             'Asignado' => 0,
                                //             'Analizo' => 1,
                                //             'Reporte' => $item->Reporte,
                                //         ]);
                                //     }
                                // } else {
                                //     CodigoParametros::create([
                                //         'Id_solicitud' => $value->Id_solicitud,
                                //         'Id_parametro' => $item->Id_parametro,
                                //         'Codigo' => $value->Folio_servicio,
                                //         'Num_muestra' => 1,
                                //         'Asignado' => 0,
                                //         'Analizo' => 1,
                                //         'Reporte' => $item->Reporte,
                                //     ]);
                                // }
                                break;
                            case 152:
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);
                                break;
                            case 35:
                                //E.Coli
                                if ($promConduc < 3500) {
                                    for ($i = 0; $i < $phMuestra->count(); $i++) {
                                        if ($phMuestra[$i]->Activo == 1) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $value->Id_solicitud,
                                                'Id_parametro' => $item->Id_parametro,
                                                'Codigo' => $value->Folio_servicio . "-EC-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => $item->Reporte,
                                            ]);
                                        }
                                    }
                                }
                                break;
                            case 253:
                                //Enterococos
                                if ($promConduc >= 3500) {
                                    for ($i = 0; $i < $phMuestra->count(); $i++) {
                                        if ($phMuestra[$i]->Activo == 1) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $value->Id_solicitud,
                                                'Id_parametro' => $item->Id_parametro,
                                                'Codigo' => $value->Folio_servicio . "-EF-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => $item->Reporte,
                                            ]);
                                        }
                                    }
                                }
                                break;
                            default:
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);
                                break;
                        }
                    }
                }
            }
        } else {
            $sw = false;
        }





        $data = array(
            'dqo' => $dqo,
            'sw' => $sw,
            'ga' => $ga,
            'coliformes' => $coliforme,
            'dbo' => $dbo,
        );

        return response()->json($data);
    }
    public function createSinCot()
    {
        if (Auth::user()->role->id == 13) {
            $intermediarios = DB::table('ViewIntermediarios')->where('Id_usuario', Auth::user()->id)->where('deleted_at', null)->get();
        } else {
            $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        }

        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get();
        $estados = DB::table('estados')->get();
        // $categorias001 = DB::table('ViewDetalleCuerpos')->get();
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
        );
        return view('cotizacion.createSinCot', $data);
    }
    public function exportPdfOrden($idOrden)
    {
        $qr = new DNS2D();
        $model = Solicitud::where('Id_cotizacion', $idOrden)->first();
        $modTemp = Solicitud::where('Id_cotizacion', $idOrden)->first();
        $cliente = SucursalCliente::where('Id_sucursal', $modTemp->Id_sucursal)->first();
        if ($model->Siralab == 1) {
            $direccion = DB::table('ViewDireccionSir')->where('Id_sucursal', $modTemp->Id_sucursal)->first();
        } else {
            $direccion = DireccionReporte::where('Id_sucursal', $modTemp->Id_sucursal)->first();
        }
        $puntos = SolicitudPuntos::where('Id_solicitud',$model->Id_solicitud)->get();

        $parametros = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $modTemp->Id_solicitud)->where('Extra', 0)->orderBy('Parametro', 'ASC')->get();
        $extra = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $modTemp->Id_solicitud)->where('Extra', 1)->orderBy('Parametro', 'ASC')->get();
        $cotizacion = Cotizacion::where('Id_cotizacion', $idOrden)->first();
        $frecuenciaMuestreo = Frecuencia001::where('Id_frecuencia', $cotizacion->Frecuencia_muestreo)->first();
        $norma = Norma::where('Id_norma',$model->Id_norma)->first();
        $contacto = SucursalContactos::where('Id_contacto',$model->Id_contacto)->first();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'letter',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 10 
        ]);
        $mpdf->SetWatermarkImage(
            asset('/public/storage/MembreteVertical.png'),
            1,
            array(215, 280),
            array(0, 0),
        );

        $data = array(
            'extra' => $extra,
            'model' => $model,
            'norma' => $norma,
            'contacto' => $contacto,
            'parametros' => $parametros,
            'qr' => $qr,
            'cotizacion' => $cotizacion,
            'direccion' => $direccion,
            'frecuenciaMuestreo' => $frecuenciaMuestreo,
            'cliente' => $cliente,
            'puntos' => $puntos,
        );

        $mpdf->showWatermarkImage = true;
        $html = view('exports.cotizacion.ordenServicio', $data);
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    public function setOrdenServicio(Request $res)
    {
        $model = Solicitud::where('Id_cotizacion',$res->id)->where('Padre',1)->get();
        $siralab = 0;
        if ($res->siralab == "true") {
            $siralab = 1;
        }
        if ($res->id != "") {
            //Genera folio
                $idCot = $res->id; 

                $tempSol = Solicitud::where('Id_cotizacion',$idCot)->where('Padre',1)->first();
                $tempSol->Id_intermediario = $res->inter;
                $tempSol->Id_cliente = $res->clientes;
                $tempSol->Siralab = $siralab;
                $tempSol->Id_sucursal = $res->sucursal;
                $tempSol->Id_direccion = $res->direccionReporte;
                $tempSol->Id_contacto = $res->contacto;
                $tempSol->Atencion = $res->atencion;
                $tempSol->Observacion = $res->observacion;
                $tempSol->Id_servicio = $res->tipoServicio;
                $tempSol->Id_descarga = $res->tipoDescarga;
                $tempSol->Id_norma = $res->norma; 
                $tempSol->Id_subnorma = $res->subnorma;
                $tempSol->Fecha_muestreo = $res->fechaMuestreo;
                $tempSol->Id_muestreo = $res->frecuencia;
                $tempSol->Num_tomas = $res->numTomas;
                $tempSol->Id_muestra = $res->tipoMuestra;
                $tempSol->Id_promedio = $res->promedio;
                $tempSol->Id_reporte = $res->tipoReporte; 
                $tempSol->Id_reporte2 = $res->tipoReporte2;
                $tempSol->Padre = 1;
                $tempSol->Hijo = 0;
                $tempSol->Id_user_c = Auth::user()->id;
                $tempSol->Id_user_m = Auth::user()->id;
                $tempSol->save();

                $cotizacion = Cotizacion::where('Id_cotizacion',$idCot)->first();
                $cotizacion->Id_intermedio = $res->inter;
                $cotizacion->Id_cliente = $res->clientes;
                $cotizacion->Id_sucursal = $res->clienteSucursal;
                $cotizacion->Id_direccion = $res->direccionReporte;
                $cotizacion->Id_general = $res->idGen;
                $cotizacion->Tipo_servicio = $res->tipoServicio;
                $cotizacion->Tipo_descarga = $res->tipoDescarga; 
                $cotizacion->Id_norma = $res->norma;
                $cotizacion->Id_subnorma = $res->subnorma;
                $cotizacion->Fecha_muestreo = $res->fechaMuestreo;
                $cotizacion->Frecuencia_muestreo = $res->frecuencia;
                $cotizacion->Tomas = $res->numTomas;
                $cotizacion->Tipo_muestra = $res->tipoMuestra;
                $cotizacion->Promedio = $res->promedio;
                $cotizacion->Tipo_reporte = $res->tipoReporte;
                $cotizacion->Tipo_reporte2 = $res->tipoReporte2;
                $cotizacion->Numero_puntos = $res->puntosSize;
                $cotizacion->Estado_cotizacion = 2;
                $cotizacion->Num_servicios = 1;
                $cotizacion->Actualizado_por = Auth::user()->id;
                $cotizacion->save();

                if ($res->paramSize > 0) { 
                    DB::table('solicitud_parametros')->where('Id_solicitud', $tempSol->Id_solicitud)->delete();
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
                        SolicitudParametro::create([
                            'Id_solicitud' => $tempSol->Id_solicitud,
                            'Id_subnorma' => $res->parametros[$i],
                            'Extra' => $extra,
                            'Reporte' => $chParam,
                        ]);
                    }
                }else{
                   
                }
        } else {            
            $cotizacion = Cotizacion::create([
                'Id_intermedio' => $res->inter,
                'Id_cliente' => $res->clientes,
                'Id_sucursal' => $res->clienteSucursal,
                'Id_direccion' => $res->direccionReporte,
                'Id_general' => $res->idGen,
                'Tipo_servicio' => $res->tipoServicio,
                'Tipo_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma, 
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Frecuencia_muestreo' => $res->frecuencia,
                'Tomas' => $res->numTomas,
                'Tipo_muestra' => $res->tipoMuestra,
                'Promedio' => $res->promedio,
                'Tipo_reporte' => $res->tipoReporte,
                'Tipo_reporte2' => $res->tipoReporte2, 
                'Numero_puntos' => $res->puntosSize,
                'Estado_cotizacion' => 2,
                'Num_servicios' => 1,
                'Tipo' => 1,
                'Creado_por' => Auth::user()->id,
                'Actualizado_por' => Auth::user()->id,
            ]);
            $tempSol = Solicitud::create([
                'Id_cotizacion' => $cotizacion->Id_cotizacion,
                'Id_intermediario' => $res->inter,
                'Id_cliente' => $res->clientes,
                'Siralab' => $siralab,
                'Id_sucursal' => $res->sucursal,
                'Id_direccion' => $res->direccionReporte,
                'Id_contacto' => $res->contacto,
                'Atencion' => $res->atencion,
                'Observacion' => $res->observacion,
                'Id_servicio' => $res->tipoServicio,
                'Id_descarga' => $res->tipoDescarga,
                'Id_norma' => $res->norma,
                'Id_subnorma' => $res->subnorma,
                'Fecha_muestreo' => $res->fechaMuestreo,
                'Id_muestreo' => $res->frecuencia,
                'Num_tomas' => $res->numTomas,
                'Id_muestra' => $res->tipoMuestra,
                'Id_promedio' => $res->promedio,
                'Id_reporte' => $res->tipoReporte,
                'Id_reporte2' => $res->tipoReporte2,
                'Padre' => 1,
                'Hijo' => 0,
                'Id_user_m' => Auth::user()->id,
                'Id_user_m' => Auth::user()->id,
 
            ]); 
            $idSol = $tempSol->Id_solicitud;
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
                    SolicitudParametro::create([
                        'Id_solicitud' => $idSol,
                        'Id_subnorma' => $res->parametros[$i],
                        'Extra' => $extra,
                        'Reporte' => $chParam,
                    ]);
                }
            }
                
        }

        


        $data = array(
            'model' => $tempSol,
        );
        return response()->json($data);
    }
    public function getDataUpdate(Request $res)
    {
        $model = Solicitud::where('Id_cotizacion', $res->id)->where('Padre',1)->first();
        $parametro = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $model->Id_solicitud)->get();
        $data = array(
            'model' => $model,
            'parametros' => $parametro,
        );
        return response()->json($data);
    }
    public function setPuntoMuestreo(Request $res)
    {
        $temp = Solicitud::where('Id_cotizacion',$res->id)->where('Padre',1)->get();
        if ($temp->count()) {
            $aux = "";
            if ($res->siralab == "true") {
                $punto = DB::table('ViewPuntoMuestreoSir')->where('Id_punto', $res->idPunto)->first();
                $aux = $punto->Punto;
            } else {
                $punto = PuntoMuestreoGen::where('Id_punto', $res->idPunto)->first();
                $aux = $punto->Punto_muestreo;
            }
            
            $model = SolicitudPuntos::create([
                'Id_solicitud' => $temp[0]->Id_solicitud,
                'Id_muestreo' => $punto->Id_punto,
                'Punto' => $aux,
            ]);
        }
        $model = SolicitudPuntos::where('Id_solicitud',$temp[0]->Id_solicitud)->get();
        $data = array(
            'model' => $model,
        );
        
        return response()->json($data); 
    }
    public function getPuntoMuestreoSol(Request $res) 
    {
        $sol = Solicitud::where('Id_cotizacion', $res->id)->where('Padre',1)->first();
        $model = SolicitudPuntos::where('Id_solicitud',$sol->Id_solicitud)->get();
        $data = array(
            'model' => $model,
            'sol' => $sol,
        );
        
        return response()->json($data);
    }
    public function editPuntoMuestreo(Request $res)
    {
        $model = SolicitudPuntos::where('Id_punto',$res->id)->first();
        $model->Punto = $res->idPunto;
        $model->save();
        $data = array(
            'model' => $model,
        );
        
        return response()->json($data);
    }
    public function deletePuntoSol(Request $res)
    {
        $model = DB::table('solicitud_puntos')->where('Id_punto', $res->id)->delete();
        $data = array(
            'model' => $model,
        );
        
        return response()->json($data);
    }
    public function updateParametroSol(Request $res)
    {
        $tempSol = Solicitud::where('Id_cotizacion',$res->id)->where('Padre',1)->first();
        DB::table('solicitud_parametros')->where('Id_solicitud', $tempSol->Id_solicitud)->delete();
        for ($i = 0; $i < sizeof($res->param); $i++) {
            $subnorma = NormaParametros::where('Id_norma', $tempSol->Id_subnorma)->where('Id_parametro', $res->param[$i]["id"])->get();
            $chParam = 0;
            $extra = 0;
            if ($subnorma->count() > 0) {
                $extra = 0;
            } else {
                $extra = 1;
            }
            // if($res->chParam[$i] == "true"){
            //     $chParam = 1;
            // }else{
            //     $chParam = 0;
            // }
            SolicitudParametro::create([
                'Id_solicitud' => $tempSol->Id_solicitud,
                'Id_subnorma' => $res->param[$i]["id"],
                'Extra' => $extra,
                // 'Reporte' => $chParam,
            ]);
        }
        $parametro = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $tempSol->Id_solicitud)->get();
        $data = array(
            'model' => $parametro,
        );
        
        return response()->json($data);
    }
    public function getParametrosSelected(Request $res)
    {
        $tempSol = Solicitud::where('Id_cotizacion', $res->id)->where('Padre',1)->first();
        $model = DB::table('solicitud_parametros')->where('Id_solicitud', $tempSol->Id_solicitud)->get();
        $parametros = DB::table('ViewParametros')->get();
        $data = array(
            'parametros' => $parametros,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setCreateOrden(Request $res)
    {
        $solModel = Solicitud::where('Id_cotizacion',$res->id)->where('Padre',1)->first();
        $puntosModel = SolicitudPuntos::where('Id_solicitud',$solModel->Id_solicitud)->get();

        $cont = 1;
        foreach ($puntosModel as $item) {
            $solTemp = $solModel->replicate();
            $solTemp->Folio_servicio = $solModel->Folio_servicio."-".$cont;
            $solTemp->Padre = 0;
            $solTemp->Hijo = $solModel->Id_solicitud;
            $solTemp->save();

            $solPuntoDuplicada = $item->replicate();
            $solPuntoDuplicada->Id_solicitud = $solTemp->Id_solicitud;
            $solPuntoDuplicada->Id_solPadre = $solModel->Id_solicitud;
            $solPuntoDuplicada->save();

            
            $tempParam = SolicitudParametro::where('Id_solicitud',$solModel->Id_solicitud)->get();
    
                foreach ($tempParam as $item) {
                    $solParamDuplicada = $item->replicate();
                    $solParamDuplicada->Id_solicitud = $solTemp->Id_solicitud;
                    $solParamDuplicada->save();
                }
            $cont++;
        }

        $data = array(
            'model' => $solModel,
        );
        return response()->json($data);
    }
    
    public function setGenFolioSol(Request $res)
    {
        $cotTemp = Solicitud::where('Id_cotizacion',$res->id)->where('Padre',1)->get();
        $msg = "No se puede generar folio";
        if ($cotTemp->count()) {     
            if ($cotTemp[0]->Folio_servicio == NULL) { 
                $temp = strtotime($res->fecha);
                $year = date("y", $temp); 
                $dayYear = date("z", $temp) + 1;
                $solDay = Solicitud::where('Fecha_muestreo',$res->fecha)->where('Padre',1)->where('Folio_servicio','!=','')->count();
        
                $folio = $dayYear . "-" . ($solDay + 1) . "/" . $year;
                
                $model = Cotizacion::find($res->id);
                $model->Folio_servicio = $folio;
                $model->save();

                $temp = Solicitud::find($cotTemp[0]->Id_solicitud);
                $temp->Folio_servicio = $folio;
                $temp->save();

                $msg = "Folio creado correctamente";
            }else{
                $msg = "Esta solicitud ya tiene folio registrado";
                $folio = $cotTemp[0]->Folio_servicio;
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
}
