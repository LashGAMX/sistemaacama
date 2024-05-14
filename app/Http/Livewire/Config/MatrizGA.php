<?php

namespace App\Http\Livewire\Config;

use App\Models\MatrazGA;
use Livewire\Component;
use Illuminate\Support\Facades\DB;



class MatrizGA extends Component
{
  // Variables publicas 
  public $idUser;
  public $show = false;
  public $alert = false;
  public $alert2= false;
  public $alert3= false;
  public $search = '';

  // Variables form
  public $idMat;
  public $delete=false;
  public $serie;
  public $peso;
  public $min;
  public $max;
  public $nota;

  public function render()
  {
      $model = MatrazGA::withTrashed()
      ->where('Num_serie','LIKE',"%{$this->search}%")
      ->orWhere('Peso','LIKE',"%{$this->search}%")
      ->get();
      return view('livewire.config.matriz-g-a',compact('model'));
  }
  public function create()
  {
      $model = MatrazGA::create([
          'Num_serie' => $this->serie,
          'Peso' => $this->peso,
          'Min' => ($this->peso - 0.0005),
          'Max' => ($this->peso + 0.0005),
        //   'Id_user_c' => $this->idUser,  
        //   'Id_user_m' => $this->idUser,
      ]);
      $this->idPro = $model->Id_matraz;
    //   $this->nota = "Creación de registro";
    //   $this->historial();
      $this->alert = true;
  }
  public function store()
  {
    if($this->delete==true)
    {
      $model=MatrazGA::where('Id_matraz',$this->idMat)->delete();
      $this->alert2=true;
    }else{
      if($this->delete==false){
$model=MatrazGA::withTrashed()->find($this->idMat)->restore();
$this->alert3=true;
      }
    
      //$this->historial();
      $model = MatrazGA::find($this->idMat);
      $model->Num_serie = $this->serie;
      $model->Peso = $this->peso;
      $model->Min = ($this->peso - 0.0005);
      $model->Max = ($this->peso + 0.0005);
      $model->save();
      $this->alert = true;
  }
}
  public function setData($idMat,$serie,$peso,$min,$max)
  {
    $model = MatrazGA::withTrashed()->where('Id_matraz', $idMat)->first();
    
    if ($model) {
        $this->idMat = $idMat;
        $this->serie = $serie;
        $this->peso = $peso;
        $this->min = $min;
        $this->max = $max;

        // Verifica si el modelo está eliminado o no
        if ($model->trashed()) {
            $this->delete = true;
        } else {
            $this->delete = false;
        }
    } 
  }
//   Public function historial()
//   {
//       $model = DB::table('procedimiento_analisis')->where('Id_procedimiento',$this->idPro)->first();
//       HistorialProcedimientoAnalisis::create([
//           'Id_procedimiento' => $this->idPro,
//           'Procedimiento' => $model->Procedimiento,
//           'Descripcion' => $model->Descripcion,
//           'Nota' => $this->nota,
//           'F_creacion' => $model->created_at,
//           'Id_user_c' => $model->Id_user_c,
//           'F_modificacion' => $model->updated_at,
//           'Id_user_m' => $model->Id_user_m,
//       ]);
//   }
  public function btnCreate()
  {
      $this->clean();
      $this->show = true;
      $this->alert = false;
  }
  public function btnCancel()
  {
      $this->show = false;
  }
  public function clean()
  {
      $this->idMat = '';
      $this->serie = '';
      $this->peso = '';
      $this->min = '';
      $this->max = '';
  }
}
