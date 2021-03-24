<?php

namespace App\Http\Livewire\Cotizacion;
use Illuminate\Support\Facades\DB;

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

    public $test = 'y';
    public $count = 0;

     //------
    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }

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
     #Atributos del primer Formulario
    public $intermediario;
    public $clienteObtenidoSelect;
    public $clienteManual;
    public $atencionA;
    public $tipoServicio;
    public $fechaCotizacion;

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
        ->where('Fecha_cotizacion', '>=' , "%{$this->fechaRangoIncial}%")
        ->orWhere('Fecha_cotizacion','<=',"%{$this->fechaDia}%");
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
    public function create(): void
    {
        # code...
        // $cotizacion = Cotizaciones::withTrashed()->get();
        // $num = count($cotizacion);
        // $num++;
        // $resultado = Cotizaciones::create([
        //     // 'Cliente' => $this->clienteManual,
        //     // 'Folio_servicio' => '23-03/'.$num,
        //     // 'Cotizacion_folio' => '23-03/'.$num,
        //     // 'Empresa' => $this->atencionA,
        //     // 'Servicio' => $this->tipoServicio,
        //     // 'Fecha_cotizacion' => $this->fechaCotizacion,
        //     // 'Supervicion' => 'por Asignar',
        //     // 'created_by' =>  'por Asignar',
        //     'Cliente' => 'testing',
        //     'Folio_servicio' => '23-03/'.$num,
        //     'Cotizacion_folio' => '23-03/'.$num,
        //     'Empresa' => 'testing',
        //     'Servicio' => 'testing',
        //     'Fecha_cotizacion' => 'testing',
        //     'Supervicion' => 'testing',
        //     'created_by' => 'testing',
        // ]);
         $this->test = 's';
    }

}
