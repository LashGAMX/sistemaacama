<?php

namespace App\Http\Livewire\Config;

use App\Models\HistorialMatriz;
use App\Models\MatrizParametro;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableMatrizParametro extends Component
{

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 100;
    public $show = false;
    public $alert = false;

    public $matriz;
    public $idMatriz;
    public $nota;

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
        $model = MatrizParametro::create([
            'Matriz' => $this->matriz,
            'Id_user_c' => $this->idUser, 
            'Id_user_m' => $this->idUser, 
        ]);
        $this->idMatriz = $model->Id_matriz_parametro;
        $this->nota = "Creación de registro";
        
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
    Public function historial()
    {
        $model = DB::table('matriz_parametros')->where('Id_matriz_parametro',$this->idMatriz)->first();
        HistorialMatriz::create([
            'Id_matriz_parametro' => $this->idMatriz,
            'Matriz' => $model->Matriz,
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
        $this->matriz = '';
        $this->idMatriz = '';
        $this->nota = '';
    }
}
