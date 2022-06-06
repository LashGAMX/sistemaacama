<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\AreaLab;
use App\Models\Envase;
use App\Models\EnvaseParametro as ModelsEnvaseParametro;
use App\Models\Parametro;
use App\Models\Preservacion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EnvaseParametro extends Component
{
    public $sw = false;
    public $search = '';

    public $idEnv; 
    public $area;
    public $parametro;
    public $envase;
    public $preservador;

    public $alert =  false;
    public function render()
    {
        $parametros = DB::table('ViewParametros')->get();
        $areaLab = AreaLab::all();
        $envases = DB::table('ViewEnvases')->get();
        $preservadores = Preservacion::all();
        $model = DB::table('ViewEnvaseParametro')->get();
    

        return view('livewire.analisis-q.envase-parametro',compact('parametros','areaLab','envases','preservadores','model'));
    }
    
    public function create()
    {
        $model = ModelsEnvaseParametro::create([
            'Id_analisis' => $this->area,
            'Id_parametro' => $this->parametro,
            'Id_envase' => $this->envase,
            'Id_preservador' => $this->preservador
        ]); 
        if($this->status != 1) 
        {
            ModelsEnvaseParametro::find($model->Id_env)->delete();   
        }
        $paraModel = Parametro::find($this->parametro);
        $paraModel->Envase = 1;
        $paraModel->save();
        $this->alert = true;
    }
    public function store()
    {

        ModelsEnvaseParametro::withTrashed()->find($this->idEnv)->restore();
        $model = ModelsEnvaseParametro::find($this->idEnv);   
        $model->Id_analisis = $this->area;
        $model->Id_parametro = $this->parametro;
        $model->Id_envase = $this->envase;
        $model->Id_preservador = $this->preservador;
        $model->save();

        if($this->status != 1) 
        {
            ModelsEnvaseParametro::find($this->idEnv)->delete();   
        }
        $this->alert = true;
    }
    public function btnCreate()
    {
        $this->alert = false;
        $this->clean();
        $this->sw = false;
    }
    public function setData($idEnv,$area,$parametro,$envase,$preservador,$status)
    {
        $this->idEnv = $idEnv; 
        $this->area = $area;
        $this->parametro = $parametro;
        $this->envase = $envase;
        $this->preservador = $preservador;
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
        $this->idEnv = ''; 
        $this->area = '';
        $this->parametro = '';
        $this->envase = '';
        $this->preservador = '';
        $this->status = 1;
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
