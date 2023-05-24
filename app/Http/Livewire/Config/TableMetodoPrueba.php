<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialMetodoPrueba;
use App\Models\MetodoPrueba;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableMetodoPrueba extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $show = false;
    public $alert = false;

    public $metodo;
    public $clave;
    public $idMetodo;
    Public $nota;

    protected $rules = [ 
        'metodo' => 'required',
        'clave' => 'required',
    ];
    protected $messages = [
        'metodo.required' => 'El nombre es un dato requerido',
        'clave.required' => 'El nombre es un dato requerido',
    ];

    public function render()
    {
        $model = MetodoPrueba::where('Metodo_prueba','LIKE',"%{$this->search}%")
        ->orWhere('Clave_metodo','LIKE',"%{$this->search}%")
        ->get();

        return view('livewire.config.table-metodo-prueba',compact('model'));
    }

    public function create()
    {
        $this->validate();
        $model = MetodoPrueba::create([
            'Metodo_prueba' => $this->metodo,
            'Clave_metodo' => $this->clave,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser,
        ]);
        $this->idMetodo = $model->Id_metodo;
        $this->clave = $model->Clave_metodo;
        $this->nota = "Creación de registro";
       // $this->historial();
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = MetodoPrueba::find($this->idMetodo);
        $model->Metodo_prueba = $this->metodo;
        $model->Clave_metodo = $this->clave;
       // $this->historial();
        $model->save();
        
        $this->alert = true;
    }
    public function setData($id,$metodo,$clave)
    {
        $this->resetValidation();
        $this->idMetodo = $id;
        $this->metodo = $metodo;
        $this->clave = $clave;
        $this->alert = false;
    }

    Public function historial()
    {
        $model = DB::table('metodo_prueba')->where('Id_metodo',$this->idMetodo)->first();
        HistorialMetodoPrueba::create([
            'Id_metodo' => $this->idMetodo,
            'Metodo_prueba' => $model->Metodo_prueba,
            'Clave_metodo' => $model->Clave_metodo,
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
        $this->idTipo = '';
        $this->tipo = '';
        $this->nota = '';
    }
}
