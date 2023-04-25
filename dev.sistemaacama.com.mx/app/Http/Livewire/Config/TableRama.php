<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialRama;
use App\Models\Rama;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableRama extends Component
{
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $alert = false;

    public $rama;
    public $idRama;
    public $nota;

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
        $model = Rama::create([
            'Rama' => $this->rama,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser, 
        ]);
        $this->idRama = $model->Id_rama;
        $this->nota = "Creación de registro";
        $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = Rama::find($this->idRama);
        $model->Rama = $this->rama;
        $this->historial();
        $model->save();
        
        $this->alert = true;
    }
    public function setData($id,$rama)
    {
        $this->alert = false;    
        $this->resetValidation();
        $this->idRama = $id;
        $this->rama = $rama;
    }
    Public function historial()
    {
        $model = DB::table('ramas')->where('Id_rama',$this->idRama)->first();
        HistorialRama::create([
            'Id_rama' => $this->idRama,
            'Rama' => $model->Rama,
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
        $this->idRama = '';
        $this->rama = '';
        $this->nota = '';
    }
}
