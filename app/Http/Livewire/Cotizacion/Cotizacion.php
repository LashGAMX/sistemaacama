<?php

namespace App\Http\Livewire\Cotizacion;
use App\Models\IntermediariosView;
use App\Models\Cotizaciones;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\Clientes;
use App\Models\Intermediario;
use App\Models\DetallesTipoCuerpo;
use Livewire\Component;

class Cotizacion extends Component
{

    public $sw = false;
    public $clienteAgregadoPorSeleccion = false;
    public $clientes;
    public $testing;
     #Atributos del primer Formulario
    public $intermediario;
    public $clienteObtenidoSelect;
    public $clienteManual;
    public $atencionA;
    public $tipoServicio;
    public $fechaCotizacion;

    public $codiccionesVenta;
    public $puntosMuestreo;
    public $promedio;
    public $tipoMuestra;
    public $frecuencia;
    public $normaFormularioUno;
    public $clasifacionNorma;
    public $tipoDescarga;
    public $estadoCotizacion;
    public $correo;
    public $telefono;
    public $direccion;

    #Atributos del segundo Formulario
    public $normaFormularioDos;
    /**
     * Muestra la Pantalla Inicial
     */
    public function render()
    {
        $detallesTipoCuerpos = DetallesTipoCuerpo::withTrashed()->get();
        $parametro  = Parametro::withTrashed()->get();
        $intermediarios = IntermediariosView::withTrashed()->get();
        $cliente = Clientes::withTrashed()->get();
        $norma = Norma::withTrashed()->get();
        $model = Cotizaciones::withTrashed()->get();
        return view('livewire.cotizacion.cotizacion',compact('model','norma',
        'cliente','intermediarios','parametro','detallesTipoCuerpos'));
    }

    public function clienteAgregadoPorSeleccion(){
        $this->clienteAgregadoPorSeleccion = true;
    }
    public function create()
    {
        # code...
        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
        Cotizaciones::create([
            'Cliente' => $this->clienteManual,
            'Folio_servicio' => '23-03/'.$num,
            'Cotizacion_folio' => '23-03/'.$num,
            'Empresa' => $this->atencionA,
            'Servicio' => $this->tipoServicio,
            'Fecha_cotizacion' => $this->fechaCotizacion,
            'Supervicion' => 'por Asignar',
            'created_by' =>  'por Asignar',

        ]);
    }

}
