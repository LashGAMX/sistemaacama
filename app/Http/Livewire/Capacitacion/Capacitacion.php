<?php

namespace App\Http\Livewire\Capacitacion;

use App\Models\Grupo;
use Livewire\Component;

class Capacitacion extends Component
{
    //Cuando es publica no es necesario pasarla por medio de compact
    public $buscar;
    public $grupo;
    public $comentario;
    public $sw = false;

    //Método que escucha constantemente
    public function render()
    {
        $model = Grupo::all();
        return view('livewire.capacitacion.capacitacion', compact('model'));
    }

    public function create(){
        Grupo::create([
            'Grupo' => $this->grupo,
            'Comentarios' => $this->comentario
        ]);
    }

    //Si es una variable publica para usarse debe referenciarse por medio de this
    public function btnCrear(){
        if($this->sw == false ){
            $this->sw = true;
        }else{
            $this->sw = false;
        }
    }
}
