<?php

namespace App\Http\Livewire\AnalisisQ;

use Illuminate\Support\Facades\DB;
use Livewire\Component;  

class NormaParametros extends Component
{
    public $search;
    public $idSub;

    public $idParametro;
    

    public $sw;
    public $msg = 'Dato guardado correctamente';
    public $alert = null;

    public function render()
    {
        $model = DB::table('ViewNormaParametro')->where('Id_norma',$this->idSub)->get();
        return view('livewire.analisis-q.norma-parametros',compact('model'));
    }
}
