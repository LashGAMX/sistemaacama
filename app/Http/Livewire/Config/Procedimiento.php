<?php

namespace App\Http\Livewire\Config;

use App\Models\ProcedimientoAnalisis;
use Livewire\Component; 

class Procedimiento extends Component
{
    // Variables publicas 
    public $show = false;
    public $alert = false;
    public $search = '';

    // Variables form
    public $idPro;
    public $procedimiento;
    public $descripcion;

    public function render()
    {
        $model = ProcedimientoAnalisis::withTrashed()
        ->where('Procedimiento','LIKE',"%{$this->search}%")
        ->orWhere('Descripcion','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.config.procedimiento',compact('model'));
    }
    public function create()
    {
        ProcedimientoAnalisis::create([
            'Procedimiento' => $this->procedimiento,
            'Descripcion' => $this->descripcion,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $model = ProcedimientoAnalisis::find($this->idPro);
        $model->Procedimiento = $this->procedimiento;
        $model->Descripcion = $this->descripcion;
        $model->save();
        $this->alert = true;
    }
    public function setData($idPro,$procedimiento,$descripcion)
    {
        $this->alert = false;
        $this->idPro = $idPro;
        $this->procedimiento = $procedimiento;
        $this->descripcion = $descripcion;
    }
    public function btnCreate()
    {
        $this->show = true;
        $this->alert = false;
    }
    public function btnCancel()
    {
        $this->show = false;
    }
}
