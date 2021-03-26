<?php
//Dirección de carpeta
namespace App\Http\Livewire\Clientes;

//Declaración de librerias
use App\Models\ClienteSiralab;
use App\Models\TituloConsecionSir;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

//Clase de componente
class TableDireccionSiralab extends Component
{
    // Uso de libreria
    use WithPagination; 
    // Variables auxiliares
    public $idSuc;
    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']]; 
    public $perPage = 30;
    public $alert = false;
    public $sw = false;
    public $idEstado;

    //Variables modelos
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
    public $ciudad;

    // Reglas de validación
    protected $rules = [ 
        'titulo' => 'required',
        'calle' => 'required',
        'ext' => 'required',
        'int' => 'required',
        'estado' => 'required',
        'municipio' => 'required',
        'colonia' => 'required',
        'cp' => 'required',
        'ciudad' => 'required',
        'localidad' => 'required',
    ];
    // protected $messages = [
    //     'dir.required' => 'La direccion es un dato requerido',
    // ];

    // Función principal LOOP
    public function render()
    {
        $titulos = TituloConsecionSir::where('Id_sucursal',$this->idSuc)->get();
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
            'Ciudad' => $this->ciudad,
            'Localidad' => $this->localidad,
            'Municipio' => $this->municipio,
            'Estado' => $this->estado,
        ]);
        $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $model = ClienteSiralab::find($this->idDireccion);
        $model->Id_sucursal = $this->idSuc;
        $model->Titulo_concesion = $this->titulo;
        $model->Calle = $this->calle;
        $model->Num_exterior = $this->ext;
        $model->Num_interior = $this->int;
        $model->Colonia = $this->colonia;
        $model->CP = $this->cp;
        $model->Ciudad = $this->ciudad;
        $model->Localidad = $this->localidad;
        $model->Municipio = $this->municipio;
        $model->Estado = $this->estado;
        $model->save();
        $this->alert = true;
    }
    public function setMunicipio()
    {
        $this->municipios = DB::table('localidades')->where('Id_municipio',$this->estado)->get();
    }
    public function setData($idDireccion,$titulo,$calle,$ext,$int,$estado,$municipio,$colonia,$cp,$localidad,$ciudad)
    {
        $this->alert = false;
        $this->sw = true;
        $this->resetValidation();

        $this->municipios = DB::table('localidades')
        ->where('Id_municipio',$estado) 
        ->get();

        $this->idDireccion = $idDireccion;
        $this->titulo = $titulo;
        $this->calle = $calle;
        $this->ext = $ext;
        $this->int = $int;
        $this->estado = $estado;
        $this->municipio = $municipio;
        $this->colonia = $colonia;
        $this->cp = $cp;
        $this->localidad = $localidad;
        $this->ciudad = $ciudad;
    }
    public function setBtn()
    {
        $this->alert = false;
        $this->clean();
        $this->sw = false;
        $this->resetValidation();
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
