<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Limite127;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LimiteParametros127 extends Component
{
    
    public $idParametro;
    public $alert = false;

    public $idLimite;
    public $promM;
    public $promD;

    public function render()
    {
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$this->idParametro)->first();
        $model = DB::table('limitepnorma_127')->where('Id_parametro',$this->idParametro)->get();
        if($model->count())
        {
            $model = DB::table('limitepnorma_127')->where('Id_parametro',$this->idParametro)->first();
        }else{
            $this->createLimites($this->idParametro);
            $model = DB::table('limitepnorma_127')->where('Id_parametro',$this->idParametro)->first();
        }

        return view('livewire.analisis-q.limite-parametros127',compact('model','parametro')); 
    }
    public function store()
    {
        $model = Limite127::find($this->idLimite);
        $model->Per_min = $this->promM;
        $model->Per_max = $this->promD;
        $model->save();
        $this->alert = true;
    }
    public function setData($idLimite,$promM,$promD)
    {
        $this->alert = false;
        $this->idLimite = $idLimite;
        $this->promM = $promM;
        $this->promD = $promD;
    }
    public function createLimites($id)
    {
        Limite127::create([
            'Id_parametro' => $this->idParametro,
        ]);
    }
}
