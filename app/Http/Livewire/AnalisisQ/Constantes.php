<?php

namespace App\Http\Livewire\AnalisisQ;

use Livewire\Component;
use App\Models\Constante;

class Constantes extends Component
{

    public $alert = false;
    public $constante;
    public $valor;
    public $descripcion;

    public function render()
    { 
        $constante = Constante::all(); 
        return view('livewire.analisis-q.constantes', compact('constante'));
    }  
}
