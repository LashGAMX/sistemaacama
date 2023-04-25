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

    public $sw = false;
    public $search = '';

    public $idEnvase; 
    public $nombre;
    public $vol;
    public $unidad;
    public $alert =  false;
 
    public function render()
    {
        $unidades = Unidad::all();
        $model = DB::table("ViewEnvases")
        ->where('Nombre','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.analisis-q.envases', compact('unidades','model'));
    }
    public function create()
    {
        $model = Envase::create([
            'Nombre' => $this->nombre,
            'Volumen' => $this->vol,
            'Id_unidad' => $this->unidad,
        ]);
        if($this->status != 1) 
        {
            Envase::find($model->Id_envase)->delete();   
        }
        $this->alert = true;
    }
    public function store()
    {

        Envase::withTrashed()->find($this->idEnvase)->restore();
        $model = Envase::find($this->idEnvase);   
        $model->Nombre = $this->nombre;
        $model->volumen = $this->vol;
        $model->Id_unidad = $this->unidad;
        $model->save();

        if($this->status != 1) 
        {
            Envase::find($this->idEnvase)->delete();   
        }
        $this->alert = true;
    }
    public function btnCreate()
    {
        $this->alert = false;
        $this->clean();
        $this->sw = false;
    }
    public function setData($idEnvase,$nombre,$vol,$unidad,$status)
    {
        $this->idEnvase = $idEnvase;
        $this->nombre = $nombre;
        $this->vol = $vol;
        $this->unidad = $unidad;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->sw = true;
        $this->alert = false;
    }
    
    public function clean()
    {
        $this->idEnvase = '';
        $this->nombre = '';
        $this->vol = '';
        $this->unidad = '';
        $this->status = 1;
    }
    public function resetAlert()
    {
        $this->alert = false;
    }


}
