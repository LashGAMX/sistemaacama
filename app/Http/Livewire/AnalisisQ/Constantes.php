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
        $constantes = Constante::all(); 
        return view('livewire.analisis-q.constantes', compact('constantes'));
    }  
    public function create()
    {   
        $model = Constante::create([
            'Constante' => $this->constante,
            'Valor' => $this->valor,
            'Descripcion' => $this->descripcion,
        ]);
        $this->alert = true;

    }
    public function message(){
        $this->alerta = true;
    }
}
