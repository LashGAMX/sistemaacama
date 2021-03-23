<?php

namespace App\Http\Livewire\Cotizacion;

use App\Models\Cotizaciones;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\Clientes;
use App\Models\Intermediario;
use Livewire\Component;

class Cotizacion extends Component
{

    public $sw = false;
    public $clienteAgregadoPorSeleccion = false;
    public $clientes;
    public $testing;
     #Atributos del primer Formulario
    public $codiccionesVenta;
    public $puntosMuestreo;
    public $promedio;
    /**
     * Muestra la Pantalla Inicial
     */
    public function render()
    {
        $parametro  = Parametro::withTrashed()->get();
        $intermediario = Intermediario::withTrashed()->get();
        $cliente = Clientes::withTrashed()->get();
        $norma = Norma::withTrashed()->get();
        $model = Cotizaciones::withTrashed()->get();
        return view('livewire.cotizacion.cotizacion',compact('model','norma',
        'cliente','intermediario','parametro'));
    }

    public function clienteAgregadoPorSeleccion(){
        $this->clienteAgregadoPorSeleccion = true;
    }


}
