 <?php

namespace App\Http\Livewire\Precios;
// namespace App\Http\Livewire\Precios;

use App\Models\Parametro; 
use App\Models\PrecioCatalogo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Catalogo extends Component
{
    public $idSucursal;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;

    public $precio;
    public $status;
    public $parametro;

    protected $rules = [
        'precio' => 'required',
    ];
    protected $messages = [
        'precio.required' => 'El precio es un dato obligatorio',
    ];

    public function render()
    {
        $parametros = Parametro::all();
        $model = DB::table('ViewPrecioCat')
        ->where('Id_laboratorio',$this->idSucursal)
        ->where('Parametro','LIKE',"%{$this->search}%")
        ->get();
        return view('livewire.precios.catalogo',compact('model','parametros'));
    }
    public function create()
    {
        $this->validate();
        {
            PrecioCatalogo::create([
                'Id_parametro' => $this->parametro,
                'Id_laboratorio' => $this->idSucursal,
                'Precio' => $this->precio,
            ]);
        }
    }
    public function btnCreate()
    {   $this->clean();
        $this->resetValidation();
    }
    public function clean()
    {
        $this->precio = '';
        $this->status = 1;
        $this->parametro = '';
    }
}
