<?php

namespace App\Http\Livewire\AnalisisQ;
use Illuminate\Support\Facades\DB;
use App\Models\Envase;

use Livewire\Component;

class Envases extends Component
{ 

    public function render()
    {
        $unidad = DB::table('unidades')->get();
        $model = Envase::all();
        return view('livewire.analisis-q.envases', compact('unidad','model'));
    }
    public function create()
    {   
        $model = Envases::create([
            
        ]);

    }
}
