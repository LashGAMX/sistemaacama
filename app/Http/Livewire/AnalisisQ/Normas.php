<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Norma;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Normas extends Component
{
    use WithPagination;
    public $perPage = 500;
    public $sw = false;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
 
    public $idNorma;
    public $norma;
    public $descarga;
    public $status;
    public $clave;
    public $inicio;
    public $fin;
    public $esp;
    public $tipo;

    public $alert =  false;
    
    protected $rules = [
        'norma' => 'required',
        'clave' => 'required'
    ];
    
    protected $messages = [
        'norma.required' => 'La norma es un dato requerido',
        'clave.required' => 'La clave de la norma es un dato requerido'
    ];

    public function render()
    {
        $descargas = DB::table('tipo_descargas')->get();
        $model = Norma::withTrashed()
        ->where('Norma','LIKE',"%{$this->search}%")
        ->orWhere('Clave_norma','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.analisis-q.normas',compact('model','descargas'));
    }
    public function create()
    {
        $this->validate();
        $model = Norma::create([
            'Norma' => $this->norma,
            'Clave_norma' => $this->clave,
            'Id_descarga' => $this->descarga,
            'Inicio_validez' => $this->inicio,
            'Fin_validez' => $this->fin
        ]);
        if($this->status != 1) 
        {
            Norma::find($model->Id_norma)->delete();   
        }
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        Norma::withTrashed()->find($this->idNorma)->restore();
        $model = Norma::find($this->idNorma);   
        $model->Norma = $this->norma;
        $model->Clave_norma = $this->clave;
        $model->Id_descarga = $this->descarga;
        $model->Inicio_validez = $this->inicio;
        $model->Fin_validez = $this->fin;
        $model->Espesificacion_ali = $this->esp;
        $model->Id_tipo = $this->tipo;
        $model->save();
        if($this->status != 1) 
        {
            Norma::find($this->idNorma)->delete();   
        }
        $this->alert = true;
    }
    public function btnCreate()
    {
        $this->resetValidation();
        $this->alert = false;
        $this->clean();
        $this->sw = false;
    }
    public function setData($idNorma,$norma,$clave,$descarga,$inicio,$fin,$status,$esp,$tipo)
    {
        $this->idNorma = $idNorma;
        $this->norma = $norma;
        $this->clave = $clave;
        $this->descarga = $descarga;
        $this->inicio = $inicio;
        $this->fin = $fin;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->sw = true;
        $this->alert = false;
        $this->esp=$esp;
        $this->tipo=$tipo;
    }
    public function showDetils($id)
    {
        return redirect()->to('/admin/analisisQ/detalle_normas/'.$id);
    }
    public function clean()
    {
        $this->idNorma = '';
        $this->norma = '';
        $this->status = 1;
        $this->clave = '';
        $this->inicio = '';
        $this->fin = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }

}
