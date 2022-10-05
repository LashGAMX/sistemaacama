<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Limite003;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LimiteParametros003 extends Component 
{
    public $idParametro;
    public $alert = false;

    public $idLimite;
    public $promM;
    public $promD;

    public function render()
    {
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$this->idParametro)->first();
        $model = DB::table('limitepnorma_003')->where('Id_parametro',$this->idParametro)->get();
        if($model->count())
        {
            $model = DB::table('limitepnorma_003')->where('Id_parametro',$this->idParametro)->first();
        }else{
            $this->createLimites($this->idParametro);
            $model = DB::table('limitepnorma_003')->where('Id_parametro',$this->idParametro)->first();
        }

        return view('livewire.analisis-q.limite-parametros003',compact('model','parametro')); 
    }
    public function store()
    {
        $model = Limite003::find($this->idLimite);
        $model->Serv_indirecto = $this->promM;
        $model->Serv_directo = $this->promD;
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
        Limite003::create([
            'Id_parametro' => $this->idParametro,
        ]);
    }
}
