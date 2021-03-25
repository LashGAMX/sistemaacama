<?php

namespace App\Http\Livewire\Clientes;

use App\Models\ClienteSiralab;
use App\Models\TituloConsecionSir;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableDireccionSiralab extends Component
{
    use WithPagination; 
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 30;
    public $show = false;
    public $alert = false;
    public $sw = false;

    public $idDireccion;
    public $titulo;
    public $calle;
    public $ext;
    public $int;
    public $estado;
    public $municipio;
    public $colonia;
    public $cp;
    public $localidad;


    protected $rules = [ 
        'titulo' => 'required',
        'calle' => 'required',
        'ext' => 'required',
        'int' => 'required',
        'estado' => 'required',
        'municipio' => 'required',
        'colonia' => 'required',
        'cp' => 'required',
        'localidad' => 'required',
    ];
    // protected $messages = [
    //     'dir.required' => 'La direccion es un dato requerido',
    // ];

    public function render()
    {
        $titulos = TituloConsecionSir::all();
        $estados = DB::table('estados')->get();
        $model = ClienteSiralab::where('Id_sucursal',$this->idSuc)->get();
        $municipios = DB::table('localidades')->where('Id_municipio',$this->estado)->get();
        return view('livewire.clientes.table-direccion-siralab',compact('model','titulos','estados','municipios'));

    }

    public function create()
    {
        $this->validate();
        ClienteSiralab::create([
            'Id_sucursal' => $this->idSuc,
            'Titulo_concesion' => $this->titulo,
            'Calle' => $this->calle,
            'Num_exterior' => $this->ext,
            'Num_interior' => $this->int,
            'Colonia' => $this->colonia,
            'CP' => $this->cp,
            'Localidad' => $this->localidad,
            'Estado' => $this->estado,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = ClienteSiralab::find($this->idDir);
        $model->Direccion = $this->dir;
        $model->save();
        $this->alert = true;
    }
    public function setMunicipio()
    {
        $this->municipios = DB::table('localidades')->where('Id_municipio',$this->estado)->get();
    }
    public function setData($id,$dir)
    {
        $this->alert = false;
        $this->resetValidation();
        $this->idDir = $id;
        $this->dir = $dir;
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
        $this->sw = false;
    }
    public function clean()
    {
        $this->idDireccion = '';
        $this->titulo = '';
        $this->calle = '';
        $this->ext = '';
        $this->int = '';
        $this->estado = '';
        $this->municipio = '';
        $this->colonia = '';
        $this->cp = '';
        $this->localidad = '';
    }

    public function resetAlert()
    {
        $this->alert = false;
    }
}
