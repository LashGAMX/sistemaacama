<?php

namespace App\Http\Livewire\Config;

use App\Models\Rama;
use Livewire\Component;

class TableRama extends Component
{
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 5;
    public $show = false;
    public $rama;
    public $idRama;

    protected $rules = [
        'rama' => 'required',
    ];
    protected $messages = [
        'rama.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = Rama::where('Rama','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-rama',compact('model'));
    }

    public function create()
    {
        $this->validate();
        Rama::create([
            'Rama' => $this->rama,
        ]);
        
    }
    public function store()
    {
        $this->validate();
        $model = Rama::find($this->idRama);
        $model->Rama = $this->rama;
        $model->save();
    }
    public function setData($id,$rama)
    {
        $this->resetValidation();
        $this->idRama = $id;
        $this->rama = $rama;
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
