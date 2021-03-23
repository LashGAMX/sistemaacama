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
    public $datos;
     
    public $sw = false;
    public $msg = 'Dato guardado correctamente';
    public $alert = null;

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
    public function update()
    {
        $this->datos = $this->parametro;
        var_dump($this->parametro);
    }
}

 