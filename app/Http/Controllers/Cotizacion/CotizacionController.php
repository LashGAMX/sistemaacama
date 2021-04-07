<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
use App\Models\IntermediariosView;
use App\Models\Clientes;
use App\Models\Norma;
use App\Models\SubNorma;
use App\Models\DetallesTipoCuerpo;
use App\Models\Intermediario;
use App\Models\NormaParametroView;
use App\Models\EvaluacionParametros;
use App\Models\Usuarios;
use App\Models\CotizacionHistorico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{

    /**
     * Retorna la Pagina Principal del Modulo de Cotización
     */
    public function index()
    {
        //Vista Cotización
        $model = Cotizaciones::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        return view('cotizacion.cotizacion', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas'));
    }
    /**
     * Crear
     */
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at',null)->get();
        $normas = Norma::all();
        $subNormas = SubNorma::all();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'normas' => $normas,
            'subNormas' => $subNormas,
        );
        return view('cotizacion.create',$data);
    }
    /**
     * Obtener Cliente
     */
    public function getCliente()
    {
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        return response()->json($model);
    }
    /**
     * Obtener Norma
     */
    public function getSubNorma()
    {
        $id = $_POST['norma'];
        $model = DB::table('sub_normas')->where('Id_norma',$id)->first();
        return response()->json(compact('model'));
    }
    /**
     * Metodo para Registrar Cotización
     */
    public function registrar(Request $request)
    {
        $user = Auth::user();
        $user = $user->name;
        $now = Carbon::now();
        $now->year;
        $now->month;
        $clienteManual =   $request->nombreCliente;
        $tipoServicio = $request->tipoServicio;
        $atencionA = $request->clienteManual;
        $fechaCotizacion = $request->fechaCotizacion;
        $telefono = $request->telefono;
        $correo =  $request->correo;
        $estadoCotizacion = $request->estadoCotizacion;
        $tipoDescarga = $request->tipoDescarga;
        $clasifacionNorma =  $request->clasifacionNorma;
        $normaFormularioUno =  $request->normaFormularioUno;
        $frecuencia = $request->frecuencia;
        $tipoMuestra = $request->tipoMuestra;
        $promedio =  $request->promedio;
        $puntosMuestreo =  $request->puntosMuestreo;
        $reporte =  $request->reporte;
        $codiccionesVenta = $request->codiccionesVenta;

        $viaticos = $request->viaticos;
        $paqueteria = $request->paqueteria;
        $gastosExtras = $request->gastosExtras;
        $numeroServicio = $request->numeroServicio;
        $kmExtra = $request->kmExtra;
        $precioKm = $request->precioKm;
        $precioKmExtra = $request->precioKmExtra;
        $observacionInterna = $request->observacionInterna;
        $observacionCotizacion = $request->observacionCotizacion;
        $tarjeta = $request->tarjeta;
        $tiempoEntrega = $request->tiempoEntrega;
        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
        @$valoresParametros = @$request->valoresParametros;

        $newCotizacion =  Cotizaciones::create([
            'Cliente' => $clienteManual,
            // 'Folio_servicio' => '96-92/' . $num,
            'Cotizacion_folio' => '96-'.$num.'/' . $now->year,
            'Empresa' => $atencionA,
            'Servicio' => $tipoServicio,
            'Fecha_cotizacion' => $fechaCotizacion,
            'Supervicion' => 'por Asignar',
            'Created_by' =>  'David Barrita',
            'Telefono' => $telefono,
            'Correo' => $correo,
            'Tipo_descarga' => $tipoDescarga,
            'Tipo_servicio' => $tipoServicio,
            'Estado_cotizacion' => $estadoCotizacion,
            'Puntos_muestreo' => $puntosMuestreo,
            'Promedio' =>  $promedio,
            'Tipo_muestra' => $tipoMuestra,
            'frecuencia' => $frecuencia,
            'Norma_formulario_uno' => $normaFormularioUno,
            'clasificacion_norma' => $clasifacionNorma,
            'Reporte' => $reporte,
            'condicciones_venta' =>  $codiccionesVenta,
            'Viaticos' =>  $viaticos,
            'Paqueteria' => $paqueteria,
            'Gastos_extras' => $gastosExtras,
            'Numero_servicio' => $numeroServicio,
            'Km_extra' => $kmExtra,
            'observacionInterna' => $observacionInterna,
            'observacionCotizacion' => $observacionCotizacion,
            'tarjeta' => $tarjeta,
            'tiempoEntrega' => NULL,
            'precioKmExtra' => $precioKmExtra
        ]);

        // $newCotizacionHistorico = CotizacionHistorico::create([
        //     'Cliente' => $clienteManual,
        //     'Id_busquedad' => $newCotizacion->Id_cotizacion,
        //     'Folio_servicio' => '21-95/' . $num,
        //     'Cotizacion_folio' => '21-95/' . $num,
        //     'Empresa' => $atencionA,
        //     'Servicio' => $tipoServicio,
        //     'Fecha_cotizacion' => $fechaCotizacion,
        //     'Supervicion' => 'por Asignar',
        //     'deleted_at' => NULL,
        //     'created_by' =>  'David Barrita',
        //     'Telefono' => $telefono,
        //     'Correo' => $correo,
        //     'Tipo_descarga' => $tipoDescarga,
        //     'Tipo_servicio' => $tipoServicio,
        //     'Estado_cotizacion' => $estadoCotizacion,
        //     'Puntos_muestreo' => $puntosMuestreo,
        //     'Promedio' =>  $promedio,
        //     'Tipo_muestra' => $tipoMuestra,
        //     'frecuencia' => $frecuencia,
        //     'Norma_formulario_uno' => $normaFormularioUno,
        //     'clasificacion_norma' => $clasifacionNorma,
        //     'Reporte' => $reporte,
        //     'condicciones_venta' =>  $codiccionesVenta,
        //     'Viaticos' =>  $viaticos,
        //     'Paqueteria' => $paqueteria,
        //     'Gastos_extras' => $gastosExtras,
        //     'Numero_servicio' => $numeroServicio,
        //     'Km_extra' => $kmExtra,
        //     'observacionInterna' => $observacionInterna,
        //     'observacionCotizacion' => $observacionCotizacion,
        //     'tarjeta' => $tarjeta,
        //     'tiempoEntrega' => NULL,
        //     'precioKmExtra' => $precioKmExtra,
        //     'fecha' => date("Y/m/d"),
        //     'hora' => date("h:i:sa"),
        //     'autor' =>  $user
        // ]);

        return back();
    }

    /**
     * Metodo para Obtner parametros
     */
    public function obtenerParametros(Request $request)
    {
        $html = "";
        $subNorma =  $request->id_subnorma;
        $parametros =  DB::table('ViewNormaParametro')->where('Id_norma', 2)->get();
        foreach ($parametros as $parametro) {
            $html .= "<option value='" . $parametro->Id_norma_param . "'>" . $parametro->Parametro . "</option>";
        }
        echo $html;
    }
    /**
     * Obtener La Subnorma
     */
    public function obtenerClasificacion(Request $request)
    {
        $html = "";
        $norma = $request->id_norma;
        $parametrosDos =  DB::table('sub_normas')->where('Id_norma',  $norma)->get();
        foreach ($parametrosDos as $parametroDos) {
            $html .= "<option value='" . $parametroDos->Id_subnorma . "'>" . $parametroDos->Clave . "</option>";
        }
        echo $html;
    }

    public function obtenerHistorico(Request $request)
    {
        $html = "";
        $idCotizacion = $request->idCotizacion;
        $historicos = DB::table('cotizacion_historico')->where('Id_busquedad',  $idCotizacion)->get();

        foreach ($historicos as $historico) {
            $html .= "<tr>";
            $html .= "<td>" . $historico->Id_cotizacion_historico . "</td>";
            $html .= "<td>" . $historico->Cliente . "</td>";
            $html .= "<td>" . $historico->Folio_servicio . "</td>";
            $html .= "<td>" . $historico->Cotizacion_folio . "</td>";
            $html .= "<td>" . $historico->Empresa . "</td>";
            $html .= "<td>" . $historico->Servicio . "</td>";
            $html .= "<td>" . $historico->Fecha_cotizacion . "</td>";
            $html .= "<td>" . $historico->fecha . "</td>";
            $html .= "<td>" . $historico->hora . "</td>";
            $html .= "<td>" . $historico->autor . "</td>";
            $html .=  "</tr>";
        }
        echo $html;
    }

    public function duplicarCotizacion(Request $request)
    {
        # code...
        $idCotizacion = $request->id;
        $cotizacionCopia = DB::table('cotizacion')
            ->where('Id_cotizacion', $idCotizacion)
            ->first();

            // $user = Auth::user();
            // $user = $user->name;
            // $now = Carbon::now();
            // $now->year;
            // $now->month;
            // $cotizacion = Cotizaciones::withTrashed()->get();
            // $num = count($cotizacion);
            // $num++;
            // $newCotizacionCopia =  [
            //     'Cliente' =>  $cotizacionCopia->clienteManual,
            //     'Folio_servicio' => '21-92/' . $num.'-2',
            //     'Cotizacion_folio' => '21-92/' . $num.'-2',
            //     'Empresa' => $cotizacionCopia->atencionA,
            //     'Servicio' => $cotizacionCopia->tipoServicio,
            //     'Fecha_cotizacion' => $cotizacionCopia->fechaCotizacion,
            //     'Supervicion' => 'por Asignar',
            //     'deleted_at' => NULL,
            //     'created_by' =>  'David Barrita',
            //     'Telefono' => $cotizacionCopia->telefono,
            //     'Correo' => $cotizacionCopia->correo,
            //     'Tipo_descarga' => $cotizacionCopia->tipoDescarga,
            //     'Tipo_servicio' => $cotizacionCopia->tipoServicio,
            //     'Estado_cotizacion' => $cotizacionCopia->estadoCotizacion,
            //     'Puntos_muestreo' => $cotizacionCopia->puntosMuestreo,
            //     'Promedio' =>  $cotizacionCopia->promedio,
            //     'Tipo_muestra' => $cotizacionCopia->tipoMuestra,
            //     'frecuencia' => $cotizacionCopia->frecuencia,
            //     'Norma_formulario_uno' => $cotizacionCopia->normaFormularioUno,
            //     'clasificacion_norma' => $cotizacionCopia->clasifacionNorma,
            //     'Reporte' => $cotizacionCopia->reporte,
            //     'condicciones_venta' =>  $cotizacionCopia->codiccionesVenta,
            //     'Viaticos' =>  $cotizacionCopia->viaticos,
            //     'Paqueteria' => $cotizacionCopia->paqueteria,
            //     'Gastos_extras' => $cotizacionCopia->gastosExtras,
            //     'Numero_servicio' => $cotizacionCopia->numeroServicio,
            //     'Km_extra' => $cotizacionCopia->kmExtra,
            //     'observacionInterna' => $cotizacionCopia->observacionInterna,
            //     'observacionCotizacion' => $cotizacionCopia->observacionCotizacion,
            //     'tarjeta' => $cotizacionCopia->tarjeta,
            //     'tiempoEntrega' => NULL,
            //     'precioKmExtra' => $cotizacionCopia->precioKmExtra
            // ];

            // $newCotizacionHistorico = CotizacionHistorico::create([
            //     'Cliente' => $cotizacionCopia->clienteManual,
            //     'Id_busquedad' => $newCotizacionCopia->Id_cotizacion,
            //     'Folio_servicio' => '21-92/' . $num,
            //     'Cotizacion_folio' => '21-92/' . $num,
            //     'Empresa' => $cotizacionCopia->atencionA,
            //     'Servicio' => $cotizacionCopia->tipoServicio,
            //     'Fecha_cotizacion' => $cotizacionCopia->fechaCotizacion,
            //     'Supervicion' => 'por Asignar',
            //     'deleted_at' => NULL,
            //     'created_by' =>  'David Barrita',
            //     'Telefono' => $cotizacionCopia->telefono,
            //     'Correo' => $cotizacionCopia->correo,
            //     'Tipo_descarga' => $cotizacionCopia->tipoDescarga,
            //     'Tipo_servicio' => $cotizacionCopia->tipoServicio,
            //     'Estado_cotizacion' => $cotizacionCopia->estadoCotizacion,
            //     'Puntos_muestreo' => $cotizacionCopia->puntosMuestreo,
            //     'Promedio' =>  $cotizacionCopia->promedio,
            //     'Tipo_muestra' => $cotizacionCopia->tipoMuestra,
            //     'frecuencia' => $cotizacionCopia->frecuencia,
            //     'Norma_formulario_uno' => $cotizacionCopia->normaFormularioUno,
            //     'clasificacion_norma' => $cotizacionCopia->clasifacionNorma,
            //     'Reporte' => $cotizacionCopia->reporte,
            //     'condicciones_venta' =>  $cotizacionCopia->codiccionesVenta,
            //     'Viaticos' =>  $cotizacionCopia->viaticos,
            //     'Paqueteria' => $cotizacionCopia->paqueteria,
            //     'Gastos_extras' => $cotizacionCopia->gastosExtras,
            //     'Numero_servicio' => $cotizacionCopia->numeroServicio,
            //     'Km_extra' => $cotizacionCopia->kmExtra,
            //     'observacionInterna' => $cotizacionCopia->observacionInterna,
            //     'observacionCotizacion' => $cotizacionCopia->observacionCotizacion,
            //     'tarjeta' => $cotizacionCopia->tarjeta,
            //     'tiempoEntrega' => NULL,
            //     'precioKmExtra' => $cotizacionCopia->precioKmExtra,
            //     'fecha' => date("Y/m/d"),
            //     'hora' => date("h:i:sa"),
            //     'autor' =>  $user
            // ]);

        //Retornar la Respuesta
         return json_encode($cotizacionCopia);
    }
}
