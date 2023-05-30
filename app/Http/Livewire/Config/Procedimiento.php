<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialProcedimientoAnalisis;
use App\Models\ProcedimientoAnalisis;
use Illuminate\Support\Facades\DB;
use Livewire\Component; 

class Procedimiento extends Component
{
    // Variables publicas 
    public $idUser;
    public $show = false;
    public $alert = false;
    public $search = '';

    // Variables form
    public $idPro;
    public $procedimiento;
    public $descripcion;
    public $nota;

    public function render()
    {
        $model = ProcedimientoAnalisis::withTrashed()
        ->where('Procedimiento','LIKE',"%{$this->search}%")
        ->orWhere('Descripcion','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.config.procedimiento',compact('model'));
    }
    public function create()
    {
        $model = ProcedimientoAnalisis::create([
            'Procedimiento' => $this->procedimiento,
            'Descripcion' => $this->descripcion,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser,
        ]);
        $this->idPro = $model->Id_procedimiento;
        $this->nota = "Creación de registro";
       // $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        //$this->historial();
        $model = ProcedimientoAnalisis::find($this->idPro);
        $model->Procedimiento = $this->procedimiento;
        $model->Descripcion = $this->descripcion;
        $model->save();
        $this->alert = true;
    }
    public function setData($idPro,$procedimiento,$descripcion)
    {
        $this->alert = false;
        $this->idPro = $idPro;
        $this->procedimiento = $procedimiento;
        $this->descripcion = $descripcion;
    }
    Public function historial()
    {
        $model = DB::table('procedimiento_analisis')->where('Id_procedimiento',$this->idPro)->first();
        HistorialProcedimientoAnalisis::create([
            'Id_procedimiento' => $this->idPro,
            'Procedimiento' => $model->Procedimiento,
            'Descripcion' => $model->Descripcion,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $model->Id_user_m,
        ]);
    }
    public function btnCreate()
    {
        $this->clean();
        $this->show = true;
        $this->alert = false;
    }
    public function btnCancel()
    {
        $this->show = false;
    }
    public function clean()
    {
        $this->idPro = '';
        $this->procedimiento = '';
        $this->descripcion = '';
        $this->nota = '';
    }
}
