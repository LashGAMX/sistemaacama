<?php

namespace App\Http\Livewire\IngenieriaCampo;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\InstrumentosLaboratorio;

class Instrumentos extends Component
{
    public function render()
    {
        $instrumentos = DB::table('instrumentos_laboratorios')->get();
        return view('livewire.ingenieria-campo.instrumentos',compact('instrumentos'));
    }


}
