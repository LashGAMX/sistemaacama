<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Clientes;
use App\Models\HistorialIntermediarios;
use App\Models\Intermediario;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
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
    public $user;
    public $nota;

    protected $rules = [ 
        'nombre' => 'required',
        'rfc' => 'required|max:13|min:12|unique:clientes',
        'correo' => 'required',
        'dir' => 'required',
        'tel' => 'required',
    ];
    protected $messages = [
        'nombre.required' => 'El nombre es un dato requerido',
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
            'Id_user_c' => Auth::user()->id,
            'Id_user_m' => Auth::user()->id,
            'RFC' => $this->rfc,
        ]);

        Intermediario::create([
            'Id_cliente' => $model->Id_cliente,
            'Laboratorio' => $this->lab,
            'Correo' => $this->correo,
            'Direccion' => $this->dir,
            'Tel_oficina' => $this->tel,
            'Extension' => $this->ext,
            // 'Id_user_c' => $this->idUser,
            // 'Id_user_m' => $this->idUser,
            'Celular1' => $this->cel,
            'Id_usuario' => @$this->user,
            
        ]);
        // $this->nombre = $model->Nombres;
        // $this->paterno = $model->A_paterno;
        // $this->materno = $model->A_materno;
        // $this->rfc = $model->RFC;
        $this->nota = "Creación de registro";
        // $this->historial($model->Id_cliente);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate([
            'nombre' => 'required',
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
                $model->Id_user_m = Auth::user()->id;
                // $this->historial($this->idCliente);
                $model->save();
            Clientes::find($this->idCliente)->delete();
        }else{
            Clientes::withTrashed()->where('Id_cliente',$this->idCliente)->restore();
            $model = Clientes::find($this->idCliente);
            $model->Nombres = $this->nombre;
            $model->A_paterno = $this->paterno;
            $model->A_materno = $this->materno;
            $model->RFC = $this->rfc;
            $model->Id_user_m = $this->idUser;
            // $this->historial($this->idCliente);
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
            'Id_usuario' => @$this->user,
        ]);
        $this->alert = true;

    }
    public function setData($id,$nombre,$paterno,$materno,$rfc,$status,$lab,$correo,$dir,$tel,$ext,$cel = '',$user)
    {
        $this->sw = true;
        $this->resetValidation();
        $this->idCliente = $id;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->nota = '';
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
        $this->user = $user;
        $this->alert = false;
    }

    Public function historial($idCliente)
    {
        $model = DB::table('ViewIntermediarios')->where('Id_cliente',$idCliente)->first();
        HistorialIntermediarios::create([
            'Id_intermediario' => $model->Id_intermediario,
            'Id_cliente' => $model->Id_cliente,
            'Nombres' => $model->Nombres,
            'A_paterno'=> $model->A_paterno,
            'A_materno' => $model->A_materno,
            'RFC' => $model->RFC,
            'Laboratorio' => $model->Sucursal,
            'Correo' => $model->Correo,
            'Direccion' => $model->Direccion,
            'Tel_oficina' => $model->Tel_oficina,
            'Extension' => $model->Extension,
            'Celular1' => $model->Celular1,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $this->idUser,
        ]);
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
        $this->user = '';
        $this->nota = '';
    }
}
