<?php

namespace App\Http\Livewire\Config\Campo;

use App\Models\TermFactorCorreccionTemp;
use App\Models\TermometroCampo;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TermometroCalibrado extends Component
{
    use WithPagination;
    // protected $paginationTheme = 'bootstrap';
    public $idUser;
    public $alert = false;
    public $sw = false;
    public $tab = true;

    public $idTermo;
    public $equipo;
    public $marca;
    public $modelo;
    public $serie;
    public $muestreador;
    public $status = 0;

    public $msg;

    public $fa1;
    public $fa2;
    public $fa3;
    public $fa4;
    public $fa5;
    public $fa6;
    public $fa7;
    public $fa8;
    
    public $apl1;
    public $apl2;
    public $apl3;
    public $apl4;
    public $apl5;
    public $apl6;
    public $apl7;
    public $apl8;
 
    public function render()
    {
        // $model = TermometroCampo::all();
        $muestreadores = Usuario::where('role_id',8)->orWhere('role_id',1)->get();
        $model = TermometroCampo::join('users','users.id','=','termometro_campo.Id_muestreador')
        ->get();
        $factores = TermFactorCorreccionTemp::where('Id_termometro',$this->idTermo)->get();
        return view('livewire.config.campo.termometro-calibrado',compact('model','muestreadores','factores'));
    }
    public function tabla()
    {
        if($this->tab == true)
        {
            $this->tab = false;
        }else if($this->tab == false){
            $this->tab = true;
        }
    }
    public function  create()
    {
        $termo = TermometroCampo::create([
            'Id_muestreador' => $this->muestreador,
            'Equipo' => $this->equipo,
            'Marca' => $this->marca,
            'Modelo' => $this->modelo,
            'Serie' => $this->serie,
        ]);

        $de = 0;
        $a = 5;
        for ($i=0; $i < 8; $i++) { 
            # code...
            TermFactorCorreccionTemp::create([
                'Id_termometro' => $termo->Id_termometro,
                'De_c' => $de,
                'A_c' => $a,
            ]);
            $de += 5;
            $a += 5;
        }

        $this->alert = true; 
    }
    public function store()
    {
        TermometroCampo::withTrashed()->where('Id_termometro',$this->idTermo)->restore();
        $model = TermometroCampo::find($this->idTermo);
        $model->Equipo = $this->equipo;
        $model->Marca = $this->marca;
        $model->Modelo = $this->modelo;
        $model->Serie = $this->serie;
        $model->Id_muestreador = $this->muestreador;
        $model->save();
        if($this->status != 1) 
        {
            TermometroCampo::find($this->idTermo)->delete();
        }
        $this->alert = true;
    }
    public function storeFactor()
    {
        $model = TermFactorCorreccionTemp::where('Id_termometro',$this->idTermo)->get();
        foreach($model as $item)
        {
            $termo = TermFactorCorreccionTemp::find($model->Id_factor);
            $termo->De_c = 
        }
    }
    public function setData2($idTermo)
    {
        $this->clean();
       $model = TermometroCampo::find($idTermo);
       $this->idTermo = $model->Id_termometro;
    }
    public function setData($idTermo)
    {
        $this->clean();
       $model = TermometroCampo::find($idTermo);
       $this->idTermo = $model->Id_termometro;
       $this->equipo = $model->Equipo;
       $this->marca = $model->Marca;
       $this->modelo = $model->Modelo;
       $this->serie = $model->Serie;
       $this->muestreador = $model->Id_muestreador;
       if ($model->deleted != null) {
            $this->status = 0;
        } else {
           $this->status = 1;
        }
        $this->sw = true;
    }
    public function btnCreate()
    {
        $this->clean();
    }
    public function clean()
    {
        $this->alert = false;
        $this->equipo = "";
        $this->marca = "";
        $this->modelo = "";
        $this->serie = "";
        $this->muestreador = 0;
        $this->status = 0;
    }
}
