<?php

namespace App\Http\Livewire\Config;

use App\Models\TipoFormula;
use Livewire\Component;
use Livewire\WithPagination;

class TableTipoFormula extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 30;
    public $show = false;
    public $tipo;
    public $idTipo;

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
        TipoFormula::create([
            'Tipo_formula' => $this->tipo,
        ]);
        
    }
    public function store()
    {
        $this->validate();
        $model = TipoFormula::find($this->idTipo);
        $model->Tipo_formula = $this->tipo;
        $model->save();
    }
    public function setData($id,$tipo)
    {
        $this->resetValidation();
        $this->idTipo = $id;
        $this->tipo = $tipo;
    }
    
    public function setBtn()
    {
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
}
