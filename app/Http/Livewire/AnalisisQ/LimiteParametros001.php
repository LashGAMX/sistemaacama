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
        $model = DB::table('ViewLimite001')->get();
        return view('livewire.analisis-q.limite-parametros001',compact('model'));
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
}
