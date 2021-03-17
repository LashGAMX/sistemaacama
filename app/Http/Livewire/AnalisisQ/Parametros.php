<?php

namespace App\Http\Livewire\AnalisisQ;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Parametros extends Component
{
    public $idUser;
    public function render()
    {
        $model = DB::table('ViewParametros')->get();
        return view('livewire.analisis-q.parametros',compact('model'));
    }
}
 