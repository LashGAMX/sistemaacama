<?php

namespace App\Http\Livewire\AnalisisQ;

use App\Models\Norma;
use Livewire\Component;
use Livewire\WithPagination;

class Normas extends Component
{
    use WithPagination;
    public $perPage = 5;

    public $sw = false;
    public $idNorma;
    public $norma;
    public $status;
    public $clave;
    public $inicio;
    public $fin;

    public $alert =  NULL;
    public $msg = '';
    
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
        $model = Norma::withTrashed()
        ->paginate($this->perPage);
        return view('livewire.analisis-q.normas',compact('model'));
    }
    public function create()
    {
        $this->validate();
        Norma::create([
            'Norma' => $this->norma,
            'Clave_norma' => $this->clave,
            'Inicio_validez' => $this->inicio,
            'Fin_validez' => $this->fin
        ]);
    }
    public function store()
    {
        $this->validate();
        $model = Norma::find($this->idNorma);   
        $model->Norma = $this->norma;
        $model->Clave_norma = $this->clave;
        $model->Inicio_validez = $this->inicio;
        $model->Fin_validez = $this->fin;
        $model->save();
        $this->alert = true;
        $this->msg = 'Norma modificada correctamente';
    }
    public function btnCreate()
    {
        $this->resetValidation();
        $this->status = 1;
        if($this->sw != false)
        {
            $this->sw = true;
        }
    }
    public function setData($idNorma,$norma,$clave,$inicio,$fin,$status)
    {
        $this->idNorma = $idNorma;
        $this->norma = $norma;
        $this->clave = $clave;
        $this->inicio = $inicio;
        $this->fin = $fin;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->sw = true;
    }
    public function showDetils($id)
    {
        return redirect()->to('/admin/analisisQ/detalle_normas/'.$id);
    }

}
