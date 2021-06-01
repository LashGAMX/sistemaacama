<?php

namespace App\Http\Livewire\Config;

use App\Models\Estado;
use App\Models\Localidad as ModelsLocalidad;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
class Localidad extends Component
{
    use WithPagination;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $sw = false;
    public $alert = false;

    public $idLoc;
    public $status = false;
    public $localidad;
    public $estado;
    public function render()
    {
        $estados = Estado::all();
        $model = DB::table('ViewLocalidades')
        ->where('estado','LIKE',"%{$this->search}%")
        ->orWhere('Nombre','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.config.localidad', compact('model', 'estados'));
    }
    public function resetAlert()
    {
        $this->alert = false;
    }

    public function btnCreate()
    {
        $this->alert = false;
        $this->clean();
    }

    public function create()
    {

        ModelsLocalidad::create([
            'Id_estado' => $this->estado,
            'Nombre' => $this->localidad,
        ]);

        $this->alert = true;
    }
    public function setData($idLoc, $estado, $localidad, $status)
    {
        $this->sw = true;
        $this->alert = false;
        $this->estado = $estado;
        $this->localidad = $localidad;
        $this->idLoc = $idLoc;
        if ($status != null) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }
    }
    public function store()
    {

        $model = ModelsLocalidad::find($this->idLoc);
        $model->Id_estado = $this->estado;
        $model->Nombre = $this->localidad;
        $model->save();
        $this->alert = true;
    }
    public function clean()
    {
        // $this->resetValidation();
        $this->idLoc = '';
        $this->localidad = '';
        $this->estado = '';
        $this->status = 1;
        $this->sw = false;
    }
}
