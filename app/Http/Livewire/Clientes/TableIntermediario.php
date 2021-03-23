<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Clientes;
use App\Models\Intermediario;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableIntermediario extends Component
{

    use WithPagination; 
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 50;
    public $sw = false;
    public $alert = false;

    public $idCliente;
    public $lab = 1;
    public $nombre;
    public $paterno;
    public $materno; 
    public $rfc;
    public $correo;
    public $dir;
    public $tel;
    public $status = 1;
    public $ext;
    public $cel;

    protected $rules = [ 
        'nombre' => 'required',
        'paterno' => 'required',
        'rfc' => 'required|max:13|min:12|unique:clientes',
        'correo' => 'required',
        'dir' => 'required',
        'tel' => 'required',
    ];
    protected $messages = [
        'nombre.required' => 'El nombre es un dato requerido',
        'paterno.required' => 'El apellido paterno es requerido',
        'rfc.required' => 'El RFC es un dato requerido',
        'rfc.min' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
        'rfc.max' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
        'correo.required' => 'El correo es un dato requerido',
        'rfc.unique' => 'El rfc ya se encuentra registrado',
        'dir.required' => 'La dirección es un dato requerido',
        'tel.required' => 'El telefono es un dato requerido',
    ];

    public function render()
    {

        $sucursal = Sucursal::all();
        $model = DB::table('ViewIntermediarios')
        ->where('Nombres','LIKE',"%{$this->search}%")
        ->orWhere('A_paterno','LIKE',"%{$this->search}%")
        ->orWHere('A_materno','LIKE',"%{$this->search}%")
        ->orWhere('RFC','LIKE',"%{$this->search}%")
        ->orWhere('Correo','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.clientes.table-intermediario',compact('model','sucursal'));
    }

    public function create()
    {
        $this->validate();

        $model = Clientes::create([
            'Nombres' => $this->nombre,
            'A_paterno' => $this->paterno,
            'A_materno' => $this->materno,
            'RFC' => $this->rfc,
        ]);

        Intermediario::create([
            'Id_cliente' => $model->Id_cliente,
            'Laboratorio' => $this->lab,
            'Correo' => $this->correo,
            'Direccion' => $this->dir,
            'Tel_oficina' => $this->tel,
            'Extension' => $this->ext,
            'Celular1' => $this->cel,
        ]);      
        $this->alert = true;
    }
    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'paterno' => 'required',
            'rfc' => 'required|max:13|min:12',
            'correo' => 'required',
            'dir' => 'required',
            'tel' => 'required',
        ]); 

        // $model = Clientes::find($this->idCliente);
        if($this->status != 1) 
        {
            Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
                $model = Clientes::find($this->idCliente);
                $model->Nombres = $this->nombre;
                $model->A_paterno = $this->paterno;
                $model->A_materno = $this->materno;
                $model->RFC = $this->rfc;
                $model->save();
            Clientes::find($this->idCliente)->delete();
        }else{
            Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
            $model = Clientes::find($this->idCliente);
            $model->Nombres = $this->nombre;
            $model->A_paterno = $this->paterno;
            $model->A_materno = $this->materno;
            $model->RFC = $this->rfc;
            $model->save();
        }

        DB::table('intermediarios')
        ->where('Id_cliente',$this->idCliente)
        ->update([
            'Laboratorio' => $this->lab,
            'Correo' => $this->correo,
            'Direccion' => $this->dir,
            'Tel_oficina' => $this->tel,
            'Extension' => $this->ext,
            'Celular1' => $this->cel,
        ]);
        $this->alert = true;

    }
    public function setData($id,$nombre,$paterno,$materno,$rfc,$status,$lab,$correo,$dir,$tel,$ext,$cel = '')
    {
        $this->sw = true;
        $this->resetValidation();
        $this->idCliente = $id;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->rfc = $rfc;
        if($status != null)
        {
            $this->status = 0;
        }else{
            $this->status = 1;
        }
        $this->lab = $lab;
        $this->correo = $correo;
        $this->dir = $dir;
        $this->tel = $tel;
        $this->ext = $ext;
        $this->cel = $cel;
        $this->alert = false;
    }
    
    public function btnCreate()
    {
        $this->alert = false;
        if($this->sw == true)
        {
            $this->resetValidation();
            $this->clean();
        }
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->idCliente = '';
        $this->nombre = '';
        $this->paterno = '';
        $this->materno = '';
        $this->rfc = '';
        $this->status = '';
        $this->lab = '';
        $this->correo = '';
        $this->dir = '';
        $this->tel = '';
        $this->ext = '';
        $this->cel = '';
        $this->sw = false;
    }
}
