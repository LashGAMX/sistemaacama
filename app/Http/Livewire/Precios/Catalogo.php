<?php

namespace App\Http\Livewire\Precios;

use App\Models\HistorialPrecioCatalogo;

use App\Models\Parametro;
use App\Models\PrecioCatalogo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Catalogo extends Component
{
    use WithPagination;
    public $idSucursal;
    public $idUser;

    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;
    public $perPage = 30;
    public $alert = false;
    public $error = false;

    public $porcentaje;

    public $idPrecio;
    public $precio;
    public $status = 1;
    public $parametro;
    public $nota;

    protected $rules = [
        'precio' => 'required',
        'parametro' => 'required',
    ];
    protected $messages = [
        'precio.required' => 'El precio es un dato obligatorio',
        'parametro.required' => 'El parametro es un dato obligatorio',
    ];

    public function render()
    {
        $parametros = Parametro::where('Id_laboratorio',$this->idSucursal)->get();

        $model = DB::table('ViewPrecioCat')
            ->where('Id_laboratorio', $this->idSucursal)
            ->where('Parametro', 'LIKE', "%{$this->search}%")
            ->paginate($this->perPage);
        return view('livewire.precios.catalogo', compact('model', 'parametros'));
    }
    public function create()
    {
        $this->validate();
        $model = PrecioCatalogo::withTrashed()->where('Id_parametro', $this->parametro)
            ->where('Id_laboratorio', $this->idSucursal);
        if ($model->count()) {
            $this->error = true;
        } else {
            $model = PrecioCatalogo::create([
                'Id_parametro' => $this->parametro,
                'Id_laboratorio' => $this->idSucursal,
                'Precio' => $this->precio,
                'Id_user_c' => $this->idUser,
                'Id_user_m' => $this->idUser
            ]);
            if ($this->status != 1) {
                PrecioCatalogo::find($model->Id_precio)->delete();
            }
            $this->error = false;
        }
        $this->idPrecio = $model->Id_precio;
        $this->nota = "Creación del registro";
        $this->historial();
        $this->alert = true;
    }
  
    public function store()
    {
        $this->validate();

        PrecioCatalogo::withTrashed()->find($this->idPrecio)->restore();
        $model = PrecioCatalogo::find($this->idPrecio);
        $model->Precio = $this->precio;
        $this->historial();
        $model->save();

        if ($this->status != 1) {
            PrecioCatalogo::find($this->idPrecio)->delete();
        }
        $this->error = false;
        $this->alert = true;
    }
    public function setData($idPrecio, $parametro, $precio, $status)
    {
        $this->idPrecio = $idPrecio;
        $this->precio = $precio;
        $this->parametro = $parametro;
        $this->sw = true;
        if ($status != null) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }
    }

    Public function historial()
    {
        $model = DB::table('ViewPrecioCat')->where('Id_precio',$this->idPrecio)->first();
        
        HistorialPrecioCatalogo::create([
            'Id_precio' => $model->Id_precio,
            'Id_parametro' => $model->Id_parametro,
            'Parametro' => $model->Parametro,
            'Id_laboratorio' => $model->Id_laboratorio,
            'Sucursal' => $model->Sucursal,
            'Precio' => $model->Precio,
            'Nota' => $this->nota,
            'F_creacion' => $model->created_at,
            'Id_user_c' => $model->Id_user_c,
            'F_modificacion' => $model->updated_at,
            'Id_user_m' => $this->idUser
        ]);
    }

    // public function createPrecio()
    // {
    //     $date = Carbon::now();
    //     $date = $date->format('y-m-d');

    //     $model = PrecioCatalogo::withTrashed()
    //     ->where('Id_laboratorio',$this->idSucursal)->get();
    //     $desc = 0;
    //     foreach($model as $item)
    //     {
    //         HistorialPrecioCat::create([
    //             'Id_parametro' => $item->Id_parametro,
    //             'Precio' => $item->Precio,
    //             'Porcentaje' => $this->porcentaje,
    //             'Anio' => $date,
    //             'Id_laboratorio' => $item->Id_laboratorio,
    //             'F_creacion' => $item->created_at,
    //             'F_actualizacion' => $item->updated_at
    //         ]);
    //     }
    //     foreach($model as $item)
    //     {
    //         $desc = 0;
    //         $desc = ($item->Precio * $this->porcentaje) / 100;
            
    //         PrecioCatalogo::withTrashed()->find($item->Id_precio)->restore();
    //         $precioModel = PrecioCatalogo::find($item->Id_precio);
    //         $precioModel->Precio = $item->Precio + $desc;
    //         $precioModel->save();
    //         if($item->deleted_at != null)
    //         {
    //             PrecioCatalogo::find($item->Id_precio)->delete();
    //         }
    //     }
    //     $this->alert = true;
    //     $this->error = false;
    // }
    public function btnPrice()
    {
        $this->clean();
        $this->resetValidation();
        $this->resetAlert();
        $this->alert = false;
        $this->error = false;
    }
    public function btnCreate()
    {
        $this->clean();
        $this->resetValidation();
        $this->resetAlert();
        $this->alert = false;
        $this->error = false;
    }
    public function clean()
    {
        $this->precio = '';
        $this->status = 1;
        $this->parametro = '';
        $this->porcentaje = '';
        $this->nota = '';
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
}
