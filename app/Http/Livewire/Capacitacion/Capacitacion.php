<?php

namespace App\Http\Livewire\Capacitacion;
<<<<<<< HEAD
 
=======

>>>>>>> 44462f9efd4d623bdebdd1e97a10d6d1c4737431
use App\Models\Grupo;
use Livewire\Component;

class Capacitacion extends Component
{
<<<<<<< HEAD
=======
    //Cuando es publica no es necesario pasarla por medio de compact
>>>>>>> 44462f9efd4d623bdebdd1e97a10d6d1c4737431
    public $buscar;
    public $grupo;
    public $comentario;
    public $sw = false;

<<<<<<< HEAD
    public function render()
    {
        $model = Grupo::all();
        return view('livewire.capacitacion.capacitacion',compact('model')); 
    }
    public function create()
    {
=======
    //Método que escucha constantemente
    public function render()
    {
        $model = Grupo::all();
        return view('livewire.capacitacion.capacitacion', compact('model'));
    }

    public function create(){
>>>>>>> 44462f9efd4d623bdebdd1e97a10d6d1c4737431
        Grupo::create([
            'Grupo' => $this->grupo,
            'Comentarios' => $this->comentario
        ]);
    }

<<<<<<< HEAD
    public function btnCrear()
    {
        if($this->sw == false)
        {
=======
    //Si es una variable publica para usarse debe referenciarse por medio de this
    public function btnCrear(){
        if($this->sw == false ){
>>>>>>> 44462f9efd4d623bdebdd1e97a10d6d1c4737431
            $this->sw = true;
        }else{
            $this->sw = false;
        }
    }
}
