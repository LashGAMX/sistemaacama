<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialTecnica;
use App\Models\Tecnica;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableTecnica extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $tecnica;
    public $idTecnica;
    public $nota;

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
        $model = Tecnica::create([
            'Tecnica' => $this->tecnica,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser, 
        ]);
        $this->idTecnica = $model->Id_tecnica;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = Tecnica::find($this->idTecnica);
        $model->Tecnica = $this->tecnica;
        $model->save();
        $this->historial();
        $this->alert = true;
    }
    public function setData($id,$tecnica)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idTecnica = $id;
        $this->tecnica = $tecnica;
    }

    Public function historial()
    {
        $model = DB::table('tecnicas')->where('Id_tecnica',$this->idTecnica)->first();
        HistorialTecnica::create([
            'Id_tecnica' => $this->idTecnica,
            'Tecnica' => $model->Tecnica,
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
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idTecnica = '';
        $this->tecnica = '';
        $this->nota='';
    }
}
