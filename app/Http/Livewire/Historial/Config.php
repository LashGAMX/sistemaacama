<?php

namespace App\Http\Livewire\Historial;

use App\Models\HistorialSucursal;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Config extends Component
{
    public $sw = 0;
    public $subModulo;
    public $categoria;
    public $search = '';

    public $idHist;
    public $idSuc;
    public $sucursal;
    public $unidad;
    // public $res;
    //public $model;

    public function render()
    {
        
        // $sucursal=;
        // if($this->subModulo == 1){
        //     if($this->categoria == 1){
        //         $this->config();
        //     $this->res = "config";
        //     }elseif($this->categoria == 2){
        //         $this->res = "unidad";
        //     }
        // }
        
        return view('livewire.historial.config');
    } 

    public function config()
    {
        $this->sw = 1;
       

    }

    public function getConfig()
    {
        
        // $model='';
      
        // //  if($this->subModulo == 1)
        // //  {
        // //     if($this->categoria == 1){
        // //         $model = DB::table('hist_laboratorioSuc')->get();
        // //     $this->res = "config";
        // //     }elseif($this->categoria == 2){
        // //         $this->res = "unidad";
        // //     }
        // // }

        // switch ($this->subModulo) {
        //     case 1:
        //         switch ($this->categoria) {
        //             case 1:
        //                 $model = DB::table('hist_laboratorioSuc')->get();
        //                 break;
        //             case 2:
        //                 $model = DB::table('hist_laboratorioUni')->get();
        //                 break;
        //         }
        //         break;
        //     case 2:
        //         echo "i es igual a 2";
        //         break;
        // }

        // $this->sucursal = $model;
        // $this->unidad = $model;
    }
    public function clientes()
    {
        $this->sw = 2;

    }
}
