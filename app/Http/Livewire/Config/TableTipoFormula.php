<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialTipoFormula;
use App\Models\TipoFormula;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableTipoFormula extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $tipo;
    public $idTipo;
    public $nota;

    protected $rules = [
        'tipo' => 'required',
    ];
    protected $messages = [
        'tipo.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = TipoFormula::where('Tipo_formula','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-tipo-formula',compact('model'));
    }

    public function create()
    {
        $this->validate();
        $model = TipoFormula::create([
          'Tipo_formula' => $this->tipo,
          'Id_user_c' => $this->idUser,
          'Id_user_m' => $this->idUser, 
        ]);
        $this->idTipo = $model->Id_tipo_formula;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = TipoFormula::find($this->idTipo);
        $model->Tipo_formula = $this->tipo;
        $this->historial();
        $model->save();
        
        $this->alert = true;
    }
    public function setData($id,$tipo)
    {
        $this->clean();
        $this->alert = false;
        $this->resetValidation();
        $this->idTipo = $id;
        $this->tipo = $tipo;
    }
    Public function historial()
    {
        $model = DB::table('tipo_formulas')->where('Id_tipo_formula',$this->idTipo)->first();
        HistorialTipoFormula::create([
            'Id_sucursal' => $this->idTipo,
            'Tipo_formula' => $model->Tipo_formula,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $model->Id_user_m,
        ]);
        
        
    }
    
    public function setBtn()
    {
        $this->clean();
        $this->alert = false;
        if($this->show == false)
        {
            $this->resetValidation();
            $this->show = true;
        }
    }
    public function deleteBtn()
    {
        if($this->show == true)
        {
            $this->show = false;
        }
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idTipo = '';
        $this->tipo = '';
        $this->nota = '';
    }
}
