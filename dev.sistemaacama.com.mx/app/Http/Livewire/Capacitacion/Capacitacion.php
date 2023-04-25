<?php

namespace App\Http\Livewire\Capacitacion;

use App\Models\Grupo;
use Livewire\Component;

class Capacitacion extends Component
{
    public $buscar;
    public $grupo;
    public $comentario;
    public $sw = false;

    public function render()
    {
        $model = Grupo::all();
        return view('livewire.capacitacion.capacitacion',compact('model')); 
    }
    public function create()
    {
        Grupo::create([
            'Grupo' => $this->grupo,
            'Comentarios' => $this->comentario
        ]);
    }

    public function btnCrear()
    {
        if($this->sw == false)
        {
            $this->sw = true;
        }else{
            $this->sw = false;
        }
    }
}
