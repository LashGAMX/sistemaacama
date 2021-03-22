<?php

namespace App\Http\Livewire\Cotizacion;

use App\Models\Cotizaciones;
use Livewire\Component;

class Cotizacion extends Component
{

    public $sw = false;

    /**
     * Muestra la Pantalla Inicial
     */
    public function render()
    {
        $model = Cotizaciones::withTrashed()->get();
        return view('livewire.cotizacion.cotizacion',compact('model'));
    }
}
