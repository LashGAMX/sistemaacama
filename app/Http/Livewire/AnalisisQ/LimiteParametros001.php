<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Limite001;
use Illuminate\Support\Facades\DB; 
use Livewire\Component; 
 
class LimiteParametros001 extends Component
{
    public $idParametro;
    public $alert = false;

    public $idLimite;
    public $Prom_Mmax;
    public $Prom_Mmin;
    public $Prom_Dmax;
    public $Prom_Dmin;

    public function render()
    {   
        $parametro = DB::table('ViewParametros')->where('Id_parametro',$this->idParametro)->first();
        $model = DB::table('ViewLimite001')->where('Id_parametro',$this->idParametro)->get();
        if($model->count())
        {
        }else{
            $this->createLimites($this->idParametro);
            $model = DB::table('ViewLimite001')->where('Id_parametro',$this->idParametro)->get();
        }
        return view('livewire.analisis-q.limite-parametros001',compact('model','parametro'));
    } 
    public function store()
    {
        $model = Limite001::find($this->idLimite);
        $model->Prom_Mmax = $this->Prom_Mmax;
        $model->Prom_Mmin = $this->Prom_Mmin;
        $model->Prom_Dmax = $this->Prom_Dmax;
        $model->Prom_Dmin = $this->Prom_Dmin;
        $model->save();
        $this->alert = true;
    }
    public function setData($idLimite,$Prom_Mmax,$Prom_Mmin,$Prom_Dmax,$Prom_Dmin)
    {
        $this->alert = false;
        $this->idLimite = $idLimite;
        $this->Prom_Mmax = $Prom_Mmax;
        $this->Prom_Mmin = $Prom_Mmin;
        $this->Prom_Dmax = $Prom_Dmax;
        $this->Prom_Dmin = $Prom_Dmin;
    }
    public function createLimites($id)
    {
        Limite001::create([
            'Id_tipo' => 1,
            'Id_categoria' => 1,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 1,
            'Id_categoria' => 2,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 1,
            'Id_categoria' => 3,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 2,
            'Id_categoria' => 4,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 2,
            'Id_categoria' => 5,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 3,
            'Id_categoria' => 6,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 3,
            'Id_categoria' => 7,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 3,
            'Id_categoria' => 8,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 4,
            'Id_categoria' => 9,
            'Id_parametro' => $this->idParametro,
        ]);
        Limite001::create([
            'Id_tipo' => 5,
            'Id_categoria' => 10,
            'Id_parametro' => $this->idParametro,
        ]);
    }
}
