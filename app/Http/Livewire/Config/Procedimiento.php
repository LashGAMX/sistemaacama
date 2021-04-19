<?php

namespace App\Http\Livewire\Config;

use App\Models\ProcedimientoAnalisis;
use Livewire\Component; 

class Procedimiento extends Component
{
    // Variables publicas 
    public $show = false;

    // Variables form
    public $procedimiento;
    public $descripcion;

    public function render()
    {
        $model = ProcedimientoAnalisis::withTrashed()->get();
        return view('livewire.config.procedimiento',compact('model'));
    }
    public function create()
    {
        ProcedimientoAnalisis::create([
            'Procedimiento' => $this->procedimiento,
            'Descripcion' => $this->descripcion,
        ]);
    }
    public function btnCreate()
    {
        $this->show = true;
    }
    public function btnCancel()
    {
        $this->show = false;
    }
}
