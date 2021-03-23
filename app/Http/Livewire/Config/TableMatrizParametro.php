<?php

namespace App\Http\Livewire\Config;

use App\Models\MatrizParametro;
use Livewire\Component;

class TableMatrizParametro extends Component
{

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $matriz;
    public $idMatriz;

    protected $rules = [
        'matriz' => 'required',
    ];
    protected $messages = [
        'matriz.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = MatrizParametro::where('Matriz','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-matriz-parametro',compact('model'));
    }

    public function create()
    {
        $this->validate();
        MatrizParametro::create([
            'Matriz' => $this->matriz,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = MatrizParametro::find($this->idMatriz);
        $model->Matriz = $this->matriz;
        $model->save();
        $this->alert = true;
    }
    public function setData($id,$matriz)
    {
        $this->clean();
        $this->resetValidation();
        $this->idMatriz = $id;
        $this->matriz = $matriz;
        $this->alert = false;
    }
    
    public function setBtn()
    {
        $this->alert = false;
        $this->clean();
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
        $this->matriz = '';
        $this->idMatriz = '';
    }
}
