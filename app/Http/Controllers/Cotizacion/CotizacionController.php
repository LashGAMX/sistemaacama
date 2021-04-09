<?php

namespace App\Http\Controllers\Cotizacion;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
use App\Models\Cotizacion;
use App\Models\IntermediariosView;
use App\Models\Clientes;
use App\Models\Norma;
use App\Models\TipoMuestra;
use App\Models\SubNorma;
use App\Models\DetallesTipoCuerpo;
use App\Models\Intermediario;
use App\Models\NormaParametroView;
use App\Models\EvaluacionParametros;
use App\Models\Usuarios;
use App\Models\TipoServicios;
use App\Models\TipoDescarga;
use App\Models\CotizacionHistorico;
use App\Models\Promedio;
use App\Models\TipoReporte;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


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
        $tipoServicio = TipoServicios::All();
        return view('cotizacion.cotizacionFinal', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas','tipoServicio'));
    }
    /**
     * Crear
     */
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $normas = Norma::all();
        $subNormas = SubNorma::all();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'normas' => $normas,
            'subNormas' => $subNormas,
        );
        return view('cotizacion.create', $data);
    }
    /**
     * Obtener Cliente
     */
    public function getCliente()
    {
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente', $id)->first();
        return response()->json($model);
    }
    /**
     * Obtener Norma
     */
    public function getSubNorma()
    {
        $id = $_POST['norma'];
        $model = DB::table('sub_normas')->where('Id_norma', $id)->first();
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
            'Cotizacion_folio' => '96-' . $num . '/' . $now->year,
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
    /**
     * Metodo para Obtener el Historico
     */
    public function obtenerHistorico(Request $request)
    {
        //Incializando el string
        $html = "";
        #Obtenemos el Id
        $idCotizacion = $request->idCotizacion;
        #Consulta sobre Cotización Historico, para obtener todos los objetos que tiene
        $historicos = CotizacionHistorico::where('Id_busquedad', $idCotizacion)->orderBy('created_at')->get();
        foreach ($historicos as $historico) {
            $html .= "<tr>";
            $html .= "<td>" . $historico->Id_cotizacion_historico . "</td>";
            $html .= "<td>" . $historico->Id_clientes . "</td>";
            $html .= "<td>" . $historico->Folio_servicios . "</td>";
            $html .= "<td>" . $historico->Cotizacion_folio . "</td>";
            $html .= "<td>" . $historico->Empresa . "</td>";
            $html .= "<td>" . $historico->Servicio . "</td>";
            $html .= "<td>" . $historico->Fecha_cotizacion . "</td>";
            $html .= "<td>" . $historico->Fecha_modificacion . "</td>";
            $html .= "<td>" . $historico->Hora_modificacion . "</td>";
            $html .= "<td>" . $historico->Autor . "</td>";
            $html .=  "</tr>";
        }
        echo  $html;
    }

    /**
     * Metodo para Duplicar Cotización
     */
    public function duplicarCotizacion(Request $request)
    {
        #Obtenemos el Id
        $id =  $request->id;
        #Buscamos el Id
        $newCotizacion = Cotizacion::where('Id_cotizacion', $id)->first();

        #Variables del Objeto
        // Se guardan en el Objeto
        $newCotizacionObj = [
            'Id_intermedio' => $newCotizacion->Id_intermedio,
            'Id_cliente' => $newCotizacion->Id_cliente,
            'Nombre' => $newCotizacion->Nombre,
            'Direccion' => $newCotizacion->Direccion,
            'Atencion' => $newCotizacion->Atencion,
            'Telefono' => $newCotizacion->Telefono,
            'Correo' => $newCotizacion->Correo,
            'Tipo_servicio' => $newCotizacion->Tipo_servicio,
            'Tipo_descarga' => $newCotizacion->Tipo_descarga,
            'Id_norma' => $newCotizacion->Id_norma,
            'Id_subnorma' => $newCotizacion->Id_subnorma,
            'Frecuencia_muestreo' => $newCotizacion->Frecuencia_muestreo,
            'Tipo_muestra' => $newCotizacion->Tipo_muestra,
            'Promedio' => $newCotizacion->Promedio,
            'Numero_puntos' => $newCotizacion->Numero_puntos,
            'Tipo_reporte' => $newCotizacion->Tipo_reporte,
            'Condicion_venta' => $newCotizacion->Condicion_venta,
            'Metodo_pago' => $newCotizacion->Metodo_pago,
            'Tiempo_entrega'  => $newCotizacion->Tiempo_entrega,
            'Costo_total'    => $newCotizacion->Costo_total,
            'Supervicion'  => $newCotizacion->Supervicion,
            'Folio_servicio' => $newCotizacion->Folio_servicio,
            'Cotizacion_folio'  => $newCotizacion->Cotizacion_folio,
            'Fecha_cotizacion'  => $newCotizacion->Fecha_cotizacion
        ];
        //Logica de Folio

        $anio = date("y");
        $mes = date("m");
        $diaAnio = date("z") + 1;
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $cotizacionesPorDia = Cotizacion::whereDate('created_at', '=', $fechaHoy)->count();
        $identificadorCodigo = $diaAnio . "/" . $mes . "/" . $anio . "-" . $cotizacionesPorDia;

        #Generamos una cotización apartir de otra
        $cotizacionCopia = new Cotizacion;

        $cotizacionCopia->Id_intermedio = $newCotizacionObj['Id_intermedio'];
        $cotizacionCopia->Id_cliente = $newCotizacionObj['Id_cliente'];
        $cotizacionCopia->Nombre = $newCotizacionObj['Nombre'];
        $cotizacionCopia->Direccion = $newCotizacionObj['Direccion'];
        $cotizacionCopia->Telefono = $newCotizacionObj['Telefono'];
        $cotizacionCopia->Correo = $newCotizacionObj['Correo'];
        $cotizacionCopia->Tipo_servicio = $newCotizacionObj['Tipo_servicio'];
        $cotizacionCopia->Tipo_descarga = $newCotizacionObj['Tipo_descarga'];
        $cotizacionCopia->Id_norma = $newCotizacionObj['Id_norma'];
        $cotizacionCopia->Id_subnorma = $newCotizacionObj['Id_subnorma'];
        $cotizacionCopia->Frecuencia_muestreo = $newCotizacionObj['Frecuencia_muestreo'];
        $cotizacionCopia->Tipo_muestra = $newCotizacionObj['Tipo_muestra'];
        $cotizacionCopia->Promedio = $newCotizacionObj['Promedio'];
        $cotizacionCopia->Numero_puntos = $newCotizacionObj['Numero_puntos'];
        $cotizacionCopia->Tipo_reporte = $newCotizacionObj['Tipo_reporte'];
        $cotizacionCopia->Condicion_venta = $newCotizacionObj['Condicion_venta'];
        $cotizacionCopia->Metodo_pago = $newCotizacionObj['Metodo_pago'];
        $cotizacionCopia->Tiempo_entrega  = $newCotizacionObj['Tiempo_entrega'];
        $cotizacionCopia->Costo_total    = $newCotizacionObj['Costo_total'];
        $cotizacionCopia->Supervicion = $newCotizacionObj['Supervicion'];
        $cotizacionCopia->Folio_servicio = $identificadorCodigo;
        $cotizacionCopia->Cotizacion_folio = $identificadorCodigo;
        $cotizacionCopia->Fecha_cotizacion =  $fechaHoy;

        // Guardar el Objeto
        $cotizacionCopia->save();
        $array = array(
            'Nuevo' => $cotizacionCopia->Id_cotizacion
        );
        //Guardar Historico de la Nueva Cotización
        $cotizacionHistorico =  new CotizacionHistorico;
        $cotizacionHistorico->Id_busquedad = $cotizacionCopia->Id_cotizacion;
        $cotizacionHistorico->Id_intermedio = $newCotizacionObj['Id_intermedio'];
        $cotizacionHistorico->Id_cliente = $newCotizacionObj['Id_cliente'];
        $cotizacionHistorico->Nombre = $newCotizacionObj['Nombre'];
        $cotizacionHistorico->Direccion = $newCotizacionObj['Direccion'];
        $cotizacionHistorico->Telefono = $newCotizacionObj['Telefono'];
        $cotizacionHistorico->Correo = $newCotizacionObj['Correo'];
        $cotizacionHistorico->Tipo_servicio = $newCotizacionObj['Tipo_servicio'];
        $cotizacionHistorico->Tipo_descarga = $newCotizacionObj['Tipo_descarga'];
        $cotizacionHistorico->Id_norma = $newCotizacionObj['Id_norma'];
        $cotizacionHistorico->Id_subnorma = $newCotizacionObj['Id_subnorma'];
        $cotizacionHistorico->Frecuencia_muestreo = $newCotizacionObj['Frecuencia_muestreo'];
        $cotizacionHistorico->Tipo_muestra = $newCotizacionObj['Tipo_muestra'];
        $cotizacionHistorico->Promedio = $newCotizacionObj['Promedio'];
        $cotizacionHistorico->Numero_puntos = $newCotizacionObj['Numero_puntos'];
        $cotizacionHistorico->Tipo_reporte = $newCotizacionObj['Tipo_reporte'];
        $cotizacionHistorico->Condicion_venta = $newCotizacionObj['Condicion_venta'];
        $cotizacionHistorico->Metodo_pago = $newCotizacionObj['Metodo_pago'];
        $cotizacionHistorico->Tiempo_entrega  = $newCotizacionObj['Tiempo_entrega'];
        $cotizacionHistorico->Costo_total    = $newCotizacionObj['Costo_total'];
        $cotizacionHistorico->Supervicion = $newCotizacionObj['Supervicion'];
        $cotizacionHistorico->Folio_servicio = $identificadorCodigo;
        $cotizacionHistorico->Cotizacion_folio = $identificadorCodigo;
        $cotizacionHistorico->Fecha_cotizacion =  $fechaHoy;


        #Guardar Cotización Historico
        $cotizacionHistorico->save();

        return response()->json($array);
    }
    public function saveCotizacionCopia()
    {
        $isTrue =  Cotizacion::create([
            'Id_intermedio' => 1,
            'Id_cliente' => 1,
            'Nombre' => 1,
            'Direccion' => 1,
            'Atencion' => 1,
            'Telefono' => 1,
            'Correo' => 1,
            'Tipo_servicio' => 1,
            'Tipo_descarga' => 1,
            'Id_norma' => 1,
            'Id_subnorma' => 1,
            'Frecuencia_muestreo' => 1,
            'Tipo_muestra' => 1,
            'Promedio' => 1,
            'Numero_puntos' => 1,
            'Tipo_reporte' => 1,
            'Condicion_venta' => 1,
            'Metodo_pago' => 1,
            'Tiempo_entrega' => 1,
            'Costo_total' => 1,
        ]);
    }

    /** Funcion para traer todos los valores de editar */
    public function edit($id)
    {
        //Cambio A
        #Información para Rellenar un Select
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        #Catalogos
        $tipoServicio = TipoServicios::All();
        $descargas = TipoDescarga::All();
        #Se obtiene el id de la columna a editar
        $getCotizacion = Cotizacion::where('Id_cotizacion', $id)->first();
        $metodoPago = DB::table('metodo_pago')->get();
        $generales = DB::table('clientes_general')->get();
        $tipoMuestra = TipoMuestra::All();
        $promedios = Promedio::All();
        $reportes = TipoReporte::All();
        $frecuencia = DB::table('frecuencia001')->get();
        return view('cotizacion.cotizacionEdit', compact(
            'tipoServicio',
            'descargas',
            'getCotizacion',
            'generales',
            'intermediarios',
            'cliente',
            'norma',
            'subNormas',
            'metodoPago',
            'tipoMuestra',
            'promedios',
            'reportes',
            'frecuencia'
        ));
    }


    public function update(Request $request)
    {
        $id = $request->id;
        $clientes = $request->clientes;
        $nombreCliente = $request->nombreCliente;
        $direccion = $request->direccion;
        $atencion = $request->atencion;
        $telefono = $request->telefono;
        $correo = $request->correo;
        $tipoServicio = $request->tipoServicio;
        $tipoDescarga = $request->tipoDescarga;
        $norma = $request->norma;
        $subnorma = $request->subnormas;
        $fecha = $request->fecha;
        $frecuencia = $request->frecuencia;
        $tomas = $request->tomas;
        $tipoMuestra = $request->tipoMuestra;
        $promedio = $request->promedio;
        $tipoReporte = $request->tipoReporte;
        $textMuestreo = $request->textMuestreo;
        $fechaMuestreo = $request->fechaMuestreo;
        $tomasMuestreo = $request->tomasMuestreo;
        $viaticos = $request->viaticos;
        $paqueteria = $request->paqueteria;
        $gastosExtras = $request->gastosExtras;
        $numeroServicio = $request->numeroServicio;
        $kmExtra = $request->kmExtra;
        $precioKm = $request->precioKm;
        $precioKmExtra = $request->precioKmExtra;

        //$flight = Flight::find(1);
        //$flight->name = 'Paris to London';
        //$flight->save();


        $cotizacionEdit = Cotizacion::find($id);
        $cotizacionEdit->Id_intermedio = 1;
        $cotizacionEdit->Id_cliente = 1;
        $cotizacionEdit->Nombre = $nombreCliente;
        $cotizacionEdit->Direccion = $direccion;
        $cotizacionEdit->Telefono = $telefono;
        $cotizacionEdit->Correo = $correo;
        $cotizacionEdit->Atencion = $atencion;

        // $cotizacionEdit->Tipo_servicio = $tipoServicio;
        // $cotizacionEdit->Tipo_descarga = $tipoDescarga;
        // $cotizacionEdit->Id_norma = $norma;
        // $cotizacionEdit->Id_subnorma = $subnorma;
        // $cotizacionEdit->Frecuencia_muestreo = $frecuencia;
        // $cotizacionEdit->Promedio = $promedio;
        // $cotizacionEdit->Numero_puntos = $tomas;
        // $cotizacionEdit->Tipo_reporte = $tipoReporte;
        // $cotizacionEdit->Condicion_venta = NULL;
        // $cotizacionEdit->Metodo_pago = 0;
        // $cotizacionEdit->Tiempo_entrega  = NULL;
        // $cotizacionEdit->Costo_total    = NULL;
        // $cotizacionEdit->Supervicion = NULL;
        // $cotizacionEdit->Folio_servicio =  NULL;
        // $cotizacionEdit->Cotizacion_folio = NULL;
        // $cotizacionEdit->Fecha_cotizacion = $fecha;

        $cotizacionEdit->save();

        $array = array(
            'a' => 100,
            'id' => $cotizacionEdit,
            'e' => $direccion,
            'u' => $telefono,
            'o' => $correo,
            '$cotizacionEdit->Tipo_servicio' => $tipoServicio,
            '$cotizacionEdit->Tipo_descarga' => $tipoDescarga
        );

        return response()->json($array);
    }
}
