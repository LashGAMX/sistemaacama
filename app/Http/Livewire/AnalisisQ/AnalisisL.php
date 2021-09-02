<?php

namespace App\Http\Livewire\AnalisisQ;

use Livewire\Component;
use App\Models\Analisis;
use App\Models\Parametro;
use App\Models\Envase;
use App\Models\Preservacion;

class AnalisisL extends Component
{
    public $alert = false;
    public $analisis;
    public $parametro;
    public $envase;
    public $preservacion;

    public function render()
    {
        $model = Analisis::all();
        $parametros = Parametro::all();
        $envases = Envase::all();
        $preservaciones = Preservacion::all();
        return view('livewire.analisis-q.analisis-l', compact('model','parametros','envases','preservaciones'));
    }
    public function create()
    {   
        $model = Analisis::create([
            'Analisis' => $this->analisis,
            'Id_parametro' => $this->parametro,
            'Id_envase' => $this->envase,
            'Id_preservacion' => $this->preservacion,
        ]);
        $this->alert = true;
    }

}
