<?php

namespace App\Http\Livewire\Config\Campo;

use App\Models\TermometroCampo;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TermometroCalibrado extends Component
{
    public $idUser;
    public $alert = false;
    public $sw = false;

    public $equipo;
    public $marca;
    public $modelo;
    public $serie;
    public $muestreador;
    public $status = 0;
    public function render()
    {
        // $model = TermometroCampo::all();
        $muestreadores = Usuario::where('role_id',8)->orWhere('role_id',1)->get();
        $model = DB::table('termometro_campo')
        ->join('users','users.id','=','termometro_campo.Id_muestreador')
        ->get();
        return view('livewire.config.campo.termometro-calibrado',compact('model','muestreadores'));
    }
    public function  create()
    {
        TermometroCampo::create([
            'Id_muestreador' => $this->muestreador,
            'Equipo' => $this->equipo,
            'Marca' => $this->marca,
            'Modelo' => $this->modelo,
            'Serie' => $this->serie,
        ]);
        $this->alert = true;
    }
    public function setData($item)
    {
        
    }
    public function btnCreate()
    {
        $this->alert = false;
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
