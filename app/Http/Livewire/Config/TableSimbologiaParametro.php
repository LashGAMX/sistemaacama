<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialSimbologia;
use App\Models\SimbologiaParametros;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableSimbologiaParametro extends Component 
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
    public $nota;

    protected $rules = [
        'simbologia' => 'required', 
        'description' => 'required',
        //'nota' => 'required',
    ];
    protected $messages = [
        'simbologia.required' => 'El nombre es un dato requerido',
        'description.required' => 'La descripcion es un dato requerido',
    ];

    public function render()
    {
        $model = SimbologiaParametros::where('Simbologia','LIKE',"%{$this->search}%")
        ->orWhere('Descripcion','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-simbologia-parametro',compact('model'));
    }

    public function create()
    {
        $this->validate();
        $model = SimbologiaParametros::create([
            'Simbologia' => $this->simbologia,
            'Descripcion' => $this->description,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser,
        ]);
        $this->idSim = $model->Id_simbologia;
        $this->description = $model->Simbologia;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = SimbologiaParametros::find($this->idSim);
        $model->Simbologia = $this->simbologia;
        $model->Descripcion = $this->description; 
        $this->historial();
        $model->save();
        
        $this->alert = true;
    }
    public function setData($id,$name,$description)
    { 
        $this->idSim = $id;
        $this->simbologia = $name;
        $this->description = $description;
        $this->alert = false;
        $this->nota = "";
    }

    Public function historial()
    {
        $model = DB::table('simbologia_parametros')->where('Id_simbologia',$this->idSim)->first();
        HistorialSimbologia::create([
            'Id_simbologia' => $this->idSim,
            'Simbologia' => $model->Simbologia,
            'Descripcion' => $model->Descripcion,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $model->Id_user_m,
        ]);
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
    public function clean()
    {
        $this->simbologia = '';
        $this->description = '';
        $this->idSim = '';
        $this->nota = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
