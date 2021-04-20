<?php

namespace App\Http\Livewire\IngenieriaCampo;

use Livewire\Component;
use App\Models\InstrumentosLaboratorio;

class Instrumentos extends Component
{
    public function render()
    {
        $instrumentos = InstrumentosLaboratorio::all();
        return view('livewire.ingenieria-campo.instrumentos',compact('instumentos'));
    }
}
