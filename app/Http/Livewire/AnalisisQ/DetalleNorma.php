<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\SubNorma;
use Livewire\Component;

class DetalleNorma extends Component
{
    public $search;
    public $stdNorma;
    public $name;
    public $idSub;
    public $sub;
    public $clave;
    public $status;
    public $sw;
    public $alert = null;

    protected $rules = [
        'sub' => 'required',
        'clave' => 'required',
    ];

    protected $messeges = [
        'sub.required' => 'El nombre es un dato requerido',
        'clave.required' => 'La clave es un dato requerido',
    ];
    
    public function render()
    {
        $model = SubNorma::all();
        return view('livewire.analisis-q.detalle-norma',compact('model'));
    }
    public function create()
    {
        $this->validate();
    }
    public function btnCreate()
    {
        $this->resetValidation();
        $this->status = 1;
        if($this->sw != false)
        {
            $this->sw = true;
        }
    }
    public function setName()
    {
        if($this->stdNorma == 1)
        {
            $this->sub = $this->name;
        }else{
            $this->sub = '';
        }
    }
}
