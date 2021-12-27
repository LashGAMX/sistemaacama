<?php

namespace App\Http\Livewire\AnalisisQ;
use Illuminate\Support\Facades\DB;
use App\Models\Envase;
use App\Models\HistorialAnalisisqEnvase;
use App\Models\Unidad;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Envases extends Component
{ 

    public $alert = false;
    public $nombre;
    public $volumen;
    public $unidad;
    public $nota;    

    public function render()
    {
        $unidades = Unidad::all();
        $model = Envase::all();
        return view('livewire.analisis-q.envases', compact('unidades','model'));
    }
    
    public function create()
    {   
        $model = Envase::create([
            'Nombre' => $this->nombre,
            'Volumen' => $this->volumen,
            'Unidad' => $this->unidad,
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id
        ]);
        
        $this->nota = "Creación de registro";
        $this->historial($model->Id_envase);
        $this->alert = true;
    }

    public function historial($idEnvase)
    {        
        $model = DB::table('envase')->where('Id_envase', $idEnvase)->first();
        HistorialAnalisisqEnvase::create([
            'Id_envase' => $model->Id_envase,
            'Nombre' => $model->Nombre,
            'Volumen' => $model->Volumen,
            'Unidad' => $model->Unidad,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => Auth::user()->id
        ]);
    }

    public function message(){
        $this->alerta = true;
    }
}
