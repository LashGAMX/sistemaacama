<?php

namespace App\Http\Livewire\Clientes;

use App\Models\ClienteGeneral;
use App\Models\Intermediario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TableCliente extends Component
{
    use WithPagination; 
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 5;
    public $sw = false;

    public $idCliente;
    public $cliente = 0;
    public $rfcCliente;
    public $idinter;
    public $inter;
    public $status;

    // protected $rules = [ 
    //     'nombre' => 'required',
    //     'paterno' => 'required',
    //     'rfc' => 'required|max:13|min:12|unique:clientes',
    //     'correo' => 'required',
    //     'dir' => 'required',
    //     'tel' => 'required',
    // ];
    // protected $messages = [
    //     'nombre.required' => 'El nombre es un dato requerido',
    //     'paterno.required' => 'El apellido paterno es requerido',
    //     'rfc.required' => 'El RFC es un dato requerido',
    //     'rfc.min' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
    //     'rfc.max' => 'El RFC Tiene que ser entre 12 y 13 caracteres',
    //     'correo.required' => 'El correo es un dato requerido',
    //     'rfc.unique' => 'El rfc ya se encuentra registrado',
    //     'dir.required' => 'La dirección es un dato requerido',
    //     'tel.required' => 'El telefono es un dato requerido',
    // ];

    public function render()
    {
        // $model = MetodoPrueba::where('Metodo_prueba','LIKE',"%{$this->search}%")
        // ->orWhere('Clave_metodo','LIKE',"%{$this->search}%")
        // ->paginate($this->perPage);
        $intermediario = DB::table('ViewIntermediarios')->get();
        $model = DB::table('ViewGenerales')->get();

        return view('livewire.clientes.table-cliente',compact('model','intermediario'));
    }

    public function create()
    {
        $this->validate();

        // $model = Clientes::create([
        //     'Nombres' => $this->nombre,
        //     'A_paterno' => $this->paterno,
        //     'A_materno' => $this->materno,
        //     'RFC' => $this->rfc,
        //     'deleted_at' => $this->status,
        // ]);

        // Intermediario::create([
        //     'Id_cliente' => $model->Id_cliente,
        //     'Laboratorio' => $this->lab,
        //     'Correo' => $this->correo,
        //     'Direccion' => $this->dir,
        //     'Tel_oficina' => $this->tel,
        //     'Extension' => $this->ext,
        //     'Celular1' => $this->cel,
        // ]);
        
    }
    public function store()
    {
        // $this->validate([
        //     'nombre' => 'required',
        //     'paterno' => 'required',
        //     'rfc' => 'required|max:13|min:12',
        //     'correo' => 'required',
        //     'dir' => 'required',
        //     'tel' => 'required',
        // ]);
        // $model = Clientes::find($this->idCliente);
        // $model->Nombres = $this->nombre;
        // $model->A_paterno = $this->paterno;
        // $model->A_materno = $this->materno;
        // $model->RFC = $this->rfc;
        // $model->deleted_at = $this->status;
        // $model->save();

        // $model = Intermediario::where('Id_cliente',$this->idCliente);
        // $model->Laboratorio = $this->lab;
        // $model->Correo = $this->correo;
        // $model->Direccion = $this->dir;
        // $model->Tel_oficina = $this->tel;
        // $model->Extension = $this->ext;
        // $model->Celular1 = $this->cel;
        // $model->save();
    }
    // public function setData($id,$nombre,$paterno,$materno,$rfc,$status,$lab,$correo,$dir,$tel,$ext,$cel = '')
    // {
    //     $this->sw = true;
    //     $this->resetValidation();
    //     $this->idCliente = $id;
    //     $this->nombre = $nombre;
    //     $this->paterno = $paterno;
    //     $this->materno = $materno;
    //     $this->rfc = $rfc;
    //     $this->status = $status;
    //     $this->lab = $lab;
    //     $this->correo = $correo;
    //     $this->dir = $dir;
    //     $this->tel = $tel;
    //     $this->ext = $ext;
    //     $this->cel = $cel;
    // }
    
    public function btnCreate()
    {
        if($this->sw == true)
        {
            $this->resetValidation();
            $this->idCliente = '';
            $this->cliente = '';
            $this->rfcCliente = '';
            $this->idInter = '';
            $this->inter = '';
            $this->status = '';
            $this->sw = false;
        }
    }

}
