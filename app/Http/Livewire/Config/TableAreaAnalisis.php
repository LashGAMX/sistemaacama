<?php

namespace App\Http\Livewire\Config;

use App\Models\AreaAnalisis;
use Livewire\Component;
use Livewire\WithPagination;

class TableAreaAnalisis extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $show = false;
    public $area;
    public $idArea;

    protected $rules = [ 
        'area' => 'required',
    ];
    protected $messages = [
        'area.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = AreaAnalisis::where('Area_analisis','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-area-analisis',compact('model'));
    }

    public function create()
    {
        $this->validate();
        AreaAnalisis::create([
            'Area_analisis' => $this->area,
        ]);
        
    }
    public function store()
    {
        $this->validate();
        $model = AreaAnalisis::find($this->idArea);
        $model->Area_analisis = $this->area;
        $model->save();
    }
    public function setData($id,$area)
    {
        $this->resetValidation();
        $this->idArea = $id;
        $this->area = $area;
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
