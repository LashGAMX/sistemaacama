<?php

namespace App\Http\Livewire\Config;

use App\Models\Tecnica;
use Livewire\Component;
use Livewire\WithPagination;

class TableTecnica extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 5;
    public $show = false;
    public $tecnica;
    public $idTecnica;

    protected $rules = [
        'tecnica' => 'required',
    ];
    protected $messages = [
        'tecnica.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = Tecnica::where('Tecnica','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-tecnica',compact('model'));
    }

    public function create()
    {
        $this->validate();
        Tecnica::create([
            'Tecnica' => $this->tecnica,
        ]);
        
    }
    public function store()
    {
        $this->validate();
        $model = Tecnica::find($this->idTecnica);
        $model->Tecnica = $this->tecnica;
        $model->save();
    }
    public function setData($id,$tecnica)
    {
        $this->resetValidation();
        $this->idTecnica = $id;
        $this->tecnica = $tecnica;
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
