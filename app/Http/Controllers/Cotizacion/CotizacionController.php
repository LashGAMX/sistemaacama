<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
use App\Models\IntermediariosView;
use App\Models\Clientes;
use App\Models\Norma;
use App\Models\DetallesTipoCuerpo;
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
        return view('cotizacion.cotizacion',compact('model','intermediarios','cliente','norma'));
    }

    public function create(Request $request)
    {
        // $clienteManual =   $request->atencionA;
        // $tipoServicio = $request->tipoServicio;
        // $atencionA = $request->clienteManual;
        // $fechaCotizacion = $request->fechaCotizacion;
        // //Vista Crear
        // $cotizacion = Cotizaciones::withTrashed()->get();
        // $num = count($cotizacion);
        // $num++;
        //    Cotizaciones::create([
        //     'Cliente' => $clienteManual,
        //     'Folio_servicio' => '21-88/'.$num,
        //     'Cotizacion_folio' => '21-88/'.$num,
        //     'Empresa' => $this->atencionA,
        //     'Servicio' => $this->tipoServicio,
        //     'Fecha_cotizacion' => $this->fechaCotizacion,
        //     'Supervicion' => 'por Asignar',
        //     'deleted_at' => NULL,
        //     'created_by' =>  $this->idUser
        // ]);
        // $data = Cotizaciones::latest('Id_cotizacion')->first();
        // $this->test = $data->Id_cotizacion;
        // $this->resetValidation();

    }

    public function registrar(Request $request)
    {
        $clienteManual =   $request->atencionA;
        $tipoServicio = $request->tipoServicio;
        $atencionA = $request->clienteManual;
        $fechaCotizacion = $request->fechaCotizacion;

        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
           Cotizaciones::create([
            'Cliente' => $clienteManual,
            'Folio_servicio' => '21-88/'.$num,
            'Cotizacion_folio' => '21-88/'.$num,
            'Empresa' => $atencionA,
            'Servicio' => $tipoServicio,
            'Fecha_cotizacion' => $fechaCotizacion,
            'Supervicion' => 'por Asignar',
            'deleted_at' => NULL,
            'created_by' =>  'David Barrita'
        ]);

        return back();
    }
}
