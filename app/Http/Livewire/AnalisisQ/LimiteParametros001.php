<?php

namespace App\Http\Livewire\AnalisisQ;

use Illuminate\Support\Facades\DB;
use Livewire\Component; 
 
class LimiteParametros001 extends Component
{
    public $idParametro;
    public $alert = false;
    public function render()
    {
        $model = DB::table('ViewLimite001')->get();
        return view('livewire.analisis-q.limite-parametros001',compact('model'));
    }
}
