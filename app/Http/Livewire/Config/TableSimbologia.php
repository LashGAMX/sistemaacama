<?php
 
namespace App\Http\Livewire\Config;

use App\Models\Simbologia;
use Livewire\Component;
use Livewire\WithPagination;

class TableSimbologia extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $simbologia;
    public $description;
    public $idSim; 

    protected $rules = [
        'simbologia' => 'required',
        'description' => 'required',
    ];
    protected $messages = [
        'simbologia.required' => 'El nombre es un dato requerido',
        'description.required' => 'La descripcion es un dato requerido',
    ];

    public function render()
    {
        $model = Simbologia::where('Simbologia','LIKE',"%{$this->search}%")
        ->orWhere('Descripcion','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-simbologia',compact('model'));
    }

    public function create()
    {
        $this->validate();
        Simbologia::create([
            'Simbologia' => $this->simbologia,
            'Descripcion' => $this->description,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = Simbologia::find($this->idSim);
        $model->Simbologia = $this->simbologia;
        $model->Descripcion = $this->description;
        $model->save();
        $this->alert = true;
    }
    public function setData($id,$name,$description)
    {
        $this->idSim = $id;
        $this->simbologia = $name;
        $this->description = $description;
        $this->alert = false;
    }
    
    public function setBtn()
    {
        $this->clean();
        if($this->show == false)
        {
            $this->resetValidation();
            $this->show = true;
        }
        $this->alert = false;
    }
    public function deleteBtn()
    {
        if($this->show == true)
        {
            $this->show = false;
        }
    }
    public function clean()
    {
        $this->simbologia = '';
        $this->description = '';
        $this->idSim = '';
    }
}
