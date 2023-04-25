<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Parametro;
use Illuminate\Support\Facades\DB;
use Livewire\Component;  

class NormaParametros extends Component
{
    public $search;
    public $idSub;

    public $idParametro;
    public $parametro;
    public $dataArray = array();
    public $datos;
     
    public $sw = false;
    public $alert = false;

    public function render()
    {
        $parametros = DB::table('ViewParametros')->where('deleted_at',null)->get();
        $model = DB::table('ViewNormaParametro')->where('Id_norma',$this->idSub)->get();
        return view('livewire.analisis-q.norma-parametros',compact('model','parametros'));
    }
    public function btnCreate()
    {
        $this->sw = true;
    }   
    public function llenarDato()
    {
        $this->dataArray[0] = $this->parametro;
    }
    public function update()
    {
        $this->datos = $this->parametro;
        var_dump($this->parametro);
    }
}

 