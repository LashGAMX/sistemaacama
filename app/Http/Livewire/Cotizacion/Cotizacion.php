<?php

namespace App\Http\Livewire\Cotizacion;

use Livewire\Component;

class Cotizacion extends Component
{

    public $sw = false;

    public function render()
    {
        return view('cotizacion.cotizacion');
    }
}
