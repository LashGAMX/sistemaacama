<?php

namespace App\Http\Livewire\AnalisisQ;
use Illuminate\Support\Facades\DB;
use App\Models\Envase;

use Livewire\Component;

class Envases extends Component
{ 

    public $alert = false;
    public $nombre;
    public $volumen;
    public $unidad;

    public function render()
    {
        $unidad = DB::table('unidades')->get();
        $model = Envase::all();
        return view('livewire.analisis-q.envases', compact('unidad','model'));
    }
    public function create()
    {   
        $model = Envase::create([
            'Nombre' => $this->nombre,
            'Volumen' => $this->volumen,
            'Unidad' => $this->unidad,
        ]);
        $this->alert = true;

    }
    public function message(){
        $this->alerta = true;
    }
}
