<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Limite002;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LimiteParametros002 extends Component
{
    public $idParametro;
    public $alert = false;

    public $idLimite;
    public $promM;
    public $promD;
    public $insta;

    public function render()
    {
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$this->idParametro)->first();
        $model = DB::table('limitepnorma_002')->where('Id_parametro',$this->idParametro)->get();
        if($model->count())
        {
            $model = DB::table('limitepnorma_002')->where('Id_parametro',$this->idParametro)->first();
        }else{
            $this->createLimites($this->idParametro);
            $model = DB::table('limitepnorma_002')->where('Id_parametro',$this->idParametro)->first();
        }

        return view('livewire.analisis-q.limite-parametros002',compact('model','parametro'));
    }
    public function store()
    {
        $model = Limite002::find($this->idLimite);
        $model->PromM = $this->promM;
        $model->PromD = $this->promD;
        $model->Instantaneo = $this->insta;
        $model->save();
        $this->alert = true;
    }
    public function setData($idLimite,$promM,$promD,$insta)
    {
        $this->alert = false;
        $this->idLimite = $idLimite;
        $this->promM = $promM;
        $this->promD = $promD;
        $this->insta = $insta;
    }
    public function createLimites($id)
    {
        Limite002::create([
            'Id_parametro' => $this->idParametro,
        ]);
    }

}
