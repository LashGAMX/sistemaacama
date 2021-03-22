<?php

namespace App\Http\Livewire\Cotizacion;

use App\Models\Cotizaciones;
use App\Models\Norma;
use App\Models\Clientes;
use App\Models\Intermediario;
use Livewire\Component;

class Cotizacion extends Component
{

    public $sw = false;
    public $controlFormularios = 0;

    /**
     * Muestra la Pantalla Inicial
     */
    public function render()
    {
        $intermediario = Intermediario::withTrashed()->get();
        $cliente = Clientes::withTrashed()->get();
        $norma = Norma::withTrashed()->get();
        $model = Cotizaciones::withTrashed()->get();
        return view('livewire.cotizacion.cotizacion',compact('model','norma',
        'cliente','intermediario'));
    }

}
