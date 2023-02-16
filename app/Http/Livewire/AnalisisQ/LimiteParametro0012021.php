<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Limite001_2021;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LimiteParametro0012021 extends Component
{
    public $idParametro;
    public $alert = false;

    public $idLimite;
    public $pd;
    public $pm;
    public $vi;
    

    public function render()
    {
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$this->idParametro)->first();
        $model = DB::table('ViewLimite0012021')->where('Id_parametro',$this->idParametro)->get();
        if($model->count())
        {
        }else{
            $this->createLimites($this->idParametro);
            $model = DB::table('ViewLimite0012021')->where('Id_parametro',$this->idParametro)->get();
        } 
        return view('livewire.analisis-q.limite-parametro0012021',compact('model','parametro'));
    }
    public function setData($id) 
    {
        $temp = Limite001_2021::find($id);
        $this->idLimite = $temp->Id_limite;
        $this->pd = $temp->Pd;
        $this->pm = $temp->Pm;
        $this->vi = $temp->Vi;
        $this->alert = false;
    }
    public function store() 
    {
        $temp = Limite001_2021::find($this->idLimite);
        $temp->Pd = $this->pd;
        $temp->Pm = $this->pm;
        $temp->Vi = $this->vi;
        $temp->save();
        $this->alert = true;
    }
    public function createLimites($id)
    {
        Limite001_2021::create([
            'Id_categoria' => 1,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001_2021::create([
            'Id_categoria' => 2,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001_2021::create([
            'Id_categoria' => 3,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001_2021::create([
            'Id_categoria' => 4,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001_2021::create([
            'Id_categoria' => 5,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001_2021::create([
            'Id_categoria' => 6,
            'Id_parametro' => $this->idParametro,
        ]);
    }
}
