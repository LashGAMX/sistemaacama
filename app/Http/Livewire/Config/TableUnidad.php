<?php
 
namespace App\Http\Livewire\Config;

use App\Models\Unidad;
use Livewire\Component;
use Livewire\WithPagination;

class TableUnidad extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 5;
    public $show = false;
    public $name;
    public $description;
    public $idUni;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];
    protected $messages = [
        'name.required' => 'El nombre es un dato requerido',
        'description.required' => 'La descripcion es un dato requerido',
    ];

    public function render()
    {
        $model = Unidad::where('Unidad','LIKE',"%{$this->search}%")
        ->orWhere('Descripcion','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-unidad',compact('model'));
    }

    public function create()
    {
        $this->validate();
        Unidad::create([
            'Unidad' => $this->name,
            'Descripcion' => $this->description,
        ]);
        
    }
    public function store()
    {
        $this->validate();
        $model = Unidad::find($this->idUni);
        $model->Unidad = $this->name;
        $model->Descripcion = $this->description;
        $model->save();
    }
    public function setData($id,$name,$description)
    {
        $this->idUni = $id;
        $this->name = $name;
        $this->description = $description;
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
