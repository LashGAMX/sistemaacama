<?php

namespace App\Http\Livewire\Cotizacion;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\IntermediariosView;
use App\Models\Cotizaciones;
use App\Models\Norma;
use App\Models\Parametro;
use App\Models\Clientes;
use App\Models\Intermediario;
use App\Models\DetallesTipoCuerpo;
use Livewire\Component;
use Livewire\WithPagination;
class Cotizacion extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $color;

    public $step;

    public $customer;

    private $stepActions = [
        'submit1',
        'submit2',
        'submit3',
    ];

    public function mount()
    {
        $this->step = 0;
    }


    public function decreaseStep()
    {
        $this->step--;
    }

    public function submit()
    {

        $action = $this->stepActions[$this->step];

        $this->$action();
    }

    public function submit1()
    {
        $this->validate([
            'name' => 'required|min:4',
        ]);

        if ($this->customer) {
            $this->customer= tap($this->customer)->update(['name' => $this->name]);
            session()->flash('message', 'Customer successfully updated.');

        }else {
            $this->customer = Customer::create(['name' => $this->name]);
            session()->flash('message', 'Customer successfully created.');

        }


        $this->step++;
    }

    public function submit2()
    {
        $this->validate([
            'email' => 'email|required',
        ]);

        $this->customer = tap($this->customer)->update(['email' => $this->email]);

        $this->step++;
    }
    public function submit3()
    {
        $this->validate([
            'color' => 'required',
        ]);

        $this->customer = tap($this->customer)->update(['color' => $this->color]);

        session()->flash('message', 'Wow! '. $this->customer->color .' is nice color '. $this->customer->name);

        $this->step++;

    }


    public $testOne;
    public $testTwo;
    public $testThree;

    public $test = 'y';
    public $idCotizacion = 0;
    #Atributos de Fecha
    public $fechaDia = '';
    public $fechaRangoIncial = '';
    public $fechaRangoFinal = '';

    #Variable Buscador
    public $perPage = 50;
    public $search;

    public $sw = false;
    public $clienteAgregadoPorSeleccion = false;
    public $clientes;
    public $testing;

    public $idUser;
     #Atributos del primer Formulario
    public $intermediario;
    public $clienteObtenidoSelect;
    public $clienteManual;
    public $atencionA;
    public $tipoServicio;
    public $fechaCotizacion;

    public $reporte;

    public $codiccionesVenta;
    public $puntosMuestreo;
    public $promedio;
    public $tipoMuestra;
    public $frecuencia;
    public $normaFormularioUno;
    public $clasifacionNorma;
    public $tipoDescarga;
    public $estadoCotizacion;
    public $correo;
    public $telefono;
    public $direccion;
    public $value;
    public $usuario;

    #Atributos del segundo Formulario
    public $normaFormularioDos;
    /**
     * Muestra la Pantalla Inicial
     */
    public function render()
    {
        $detallesTipoCuerpos = DetallesTipoCuerpo::all();
        $parametros  = Parametro::all();
        $intermediarios = IntermediariosView::all();
        $cliente = Clientes::all();
        $norma = Norma::all();
        $model = Cotizaciones::where('Cliente','LIKE',"%{$this->search}%")
        ->orWhere('Folio_servicio','LIKE',"%{$this->search}%")
        ->orWHere('Cotizacion_folio','LIKE',"%{$this->search}%")
        ->orWhere('Empresa','LIKE',"%{$this->search}%")
        ->orWhere('Servicio','LIKE',"%{$this->search}%")
        ->orWhere('Supervicion','LIKE',"%{$this->search}%")
        ->orwhere("Fecha_cotizacion","LIKE","%{$this->search}%")->paginate(50);

        //->orwhere("Fecha_cotizacion","=","{$this->fechaRangoIncial}")

        //   ->orwhere('Fecha_cotizacion', '>=' , "{$this->fechaRangoIncial}")
        //->orwhere('Fecha_cotizacion', '<=' , "{$this->fechaRangoFinal}")
//--------------
        // ->where('Cliente','LIKE',"%{$this->search}%")
        // ->where('Fecha_cotizacion', '>=' , '{$fechaRangoIncial}')
        // ->where('Fecha_cotizacion', '<=' , '{$fechaRangoFinal}')
        // ->orWhere('Folio_servicio','LIKE',"%{$this->search}%")
        // ->orWHere('Cotizacion_folio','LIKE',"%{$this->search}%")
        // ->orWhere('Empresa','LIKE',"%{$this->search}%")
        // ->orWhere('Servicio','LIKE',"%{$this->search}%")
        // ->orWhere('Supervicion','LIKE',"%{$this->search}%")
        // ->orWhere('Fecha_cotizacion','=','{$this->$fechaDia}');

        return view('livewire.cotizacion.cotizacion',compact('model','norma',
        'cliente','intermediarios','parametros','detallesTipoCuerpos'));
    }


    public function clienteAgregadoPorSeleccion(){
        $this->clienteAgregadoPorSeleccion = true;
    }
    public $tab = '1';
    public $tabNombre = '';
    public function controlTab($control)
    {
        $this->tab = $control;
        switch ($this->tab) {
            case '1':
                $this->tabNombre = 'Información Basica';
                break;
            case '2':
                $this->tabNombre = 'Parametros';
                break;
            case '3':
                $this->tabNombre = 'Información Cotización';
                break;
            default:
                # code...
                break;
        }
    }

    public function edit($id){
        $cotizacion = Cotizaciones::where('Id_cotizacion',$id)->first();

        $this->clienteManual =  $cotizacion->Cliente;
        // $cotizacion->Folio_servicio;
        // $cotizacion->Cotizacion_folio;
        $this->atencionA =  $cotizacion->Empresa;
        $this->tipoServicio = $cotizacion->Servicio;
        $this->fechaCotizacion = $cotizacion->Fecha_cotizacion;
        // $cotizacion->Supervicion;
    }

    public function details($id){
        $cotizacion = Cotizaciones::where('Id_cotizacion',$id)->first();
    }

    public function create(): void
    {
        # code...
        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
           Cotizaciones::create([
            'Cliente' => $this->clienteManual,
            'Folio_servicio' => '24-03/'.$num,
            'Cotizacion_folio' => '24-03/'.$num,
            'Empresa' => $this->atencionA,
            'Servicio' => $this->tipoServicio,
            'Fecha_cotizacion' => $this->fechaCotizacion,
            'Supervicion' => 'por Asignar',
            'deleted_at' => NULL,
            'created_by' =>  $this->idUser
        ]);
        $data = Cotizaciones::latest('Id_cotizacion')->first();
        $this->test = $data->Id_cotizacion;
         $this->resetValidation();
    }

}
