<?php

namespace App\Http\Livewire\Config\Campo;

use App\Models\TermFactorCorreccionTemp;
use App\Models\TermometroCampo;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TermometroCalibrado extends Component
{
    use WithPagination;
    // protected $paginationTheme = 'bootstrap';
    public $idUser;
    public $alert = false;
    public $sw = false;
    public $tab = true;

    public $idTermo;
    public $equipo; 
    public $marca;
    public $modelo;
    public $serie;
    public $muestreador;
    public $status = 0;

    public $msg;

    public $fa1;
    public $fa2;
    public $fa3;
    public $fa4;
    public $fa5;
    public $fa6;
    public $fa7;
    public $fa8;
    public $fa9;
    public $fa10;
    
    public $apl1;
    public $apl2;
    public $apl3;
    public $apl4;
    public $apl5;
    public $apl6;
    public $apl7;
    public $apl8;
    public $apl9;
    public $apl10;
 
    public function render()
    {
        // $model = TermometroCampo::all();
        $muestreadores = Usuario::where('role_id',8)->orWhere('role_id',1)->get();
        $model = TermometroCampo::join('users','users.id','=','termometro_campo.Id_muestreador')
        ->get();
        $factores = TermFactorCorreccionTemp::where('Id_termometro',$this->idTermo)->get();
        return view('livewire.config.campo.termometro-calibrado',compact('model','muestreadores','factores'));
    }
    public function tabla()
    {
        if($this->tab == true)
        {
            $this->tab = false;
        }else if($this->tab == false){
            $this->tab = true;
        }
    }
    public function  create()
    {
        $termo = TermometroCampo::create([
            'Id_muestreador' => $this->muestreador,
            'Equipo' => $this->equipo,
            'Marca' => $this->marca,
            'Modelo' => $this->modelo,
            'Serie' => $this->serie,
        ]);

        $de = 0;
        $a = 5;
        for ($i=0; $i < 10; $i++) { 
            # code...
            TermFactorCorreccionTemp::create([
                'Id_termometro' => $termo->Id_termometro,
                'De_c' => $de,
                'A_c' => $a,
            ]);
            $de += 5;
            $a += 5;
        }

        $this->alert = true; 
    }
    public function store()
    {
        TermometroCampo::withTrashed()->where('Id_termometro',$this->idTermo)->restore();
        $model = TermometroCampo::find($this->idTermo);
        $model->Equipo = $this->equipo;
        $model->Marca = $this->marca;
        $model->Modelo = $this->modelo;
        $model->Serie = $this->serie;
        $model->Id_muestreador = $this->muestreador;
        $model->save();
        if($this->status != 1) 
        {
            TermometroCampo::find($this->idTermo)->delete();
        }
        $this->alert = true;
    }
    public function storeFactor()
    {
        $model = TermFactorCorreccionTemp::where('Id_termometro',$this->idTermo)->get();
        
        $termo = TermFactorCorreccionTemp::find($model[0]->Id_factor);
        $termo->Factor = $this->fa1;

        if($this->fa1 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa1 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl1 */;
        }

        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[1]->Id_factor);
        $termo->Factor = $this->fa2;

        if($this->fa2 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa2 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl2 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[2]->Id_factor);
        $termo->Factor = $this->fa3;

        if($this->fa3 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa3 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl3 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[3]->Id_factor);
        $termo->Factor = $this->fa4;

        if($this->fa4 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa4 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl4 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[4]->Id_factor);
        $termo->Factor = $this->fa5;

        if($this->fa5 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa5 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl5 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[5]->Id_factor);
        $termo->Factor = $this->fa6;

        if($this->fa6 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa6 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl6 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[6]->Id_factor);
        $termo->Factor = $this->fa7;

        if($this->fa7 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa7 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl7 */;
        }
        
        $termo->save();
        
        $termo = TermFactorCorreccionTemp::find($model[7]->Id_factor);
        $termo->Factor = $this->fa8;

        if($this->fa8 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa8 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl8 */;
        }
        
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[8]->Id_factor);
        $termo->Factor = $this->fa9;

        if($this->fa9 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa9 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl8 */;
        }
         
        $termo->save();

        $termo = TermFactorCorreccionTemp::find($model[9]->Id_factor);
        $termo->Factor = $this->fa10;

        if($this->fa10 >= 0.5){
            $termo->Factor_aplicado = 1;
        }else if($this->fa10 <= -0.5){
            $termo->Factor_aplicado = -1;
        }else{
            $termo->Factor_aplicado = 0 /* $this->apl8 */;
        }
       
        
        $termo->save();



        $this->alert = true;
    }
    public function setData2($idTermo)
    {
        $this->clean();
       $model = TermometroCampo::find($idTermo);
       $this->idTermo = $model->Id_termometro;

       $model = TermFactorCorreccionTemp::where('Id_termometro',$this->idTermo)->get();

       $termo = TermFactorCorreccionTemp::find($model[0]->Id_factor);
        $this->fa1 = $termo->Factor;

        if($this->fa1 >= 0.5){
            $this->apl1 = 1;
        }else if($this->fa1 <= -0.5){
            $this->apl1 = -1;
        }else{
            $this->apl1 = 0;
        }
        
        //$this->apl1 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[1]->Id_factor);
        $this->fa2 = $termo->Factor;

        if($this->fa2 >= 0.5){
            $this->apl2 = 1;
        }else if($this->fa2 <= -0.5){
            $this->apl2 = -1;
        }else{
            $this->apl2 = 0;
        }
       
        //$this->apl2 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[2]->Id_factor);
        $this->fa3 = $termo->Factor;
       
        if($this->fa3 >= 0.5){
            $this->apl3 = 1;
        }else if($this->fa3 <= -0.5){
            $this->apl3 = -1;
        }else{
            $this->apl3 = 0;
        }
       
        //$this->apl3 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[3]->Id_factor);
        $this->fa4 = $termo->Factor;

        if($this->fa4 >= 0.5){
            $this->apl4 = 1;
        }else if($this->fa4 <= -0.5){
            $this->apl4 = -1;
        }else{
            $this->apl4 = 0;
        }

       //$this->apl4 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[4]->Id_factor);
        $this->fa5 = $termo->Factor;
        
        if($this->fa5 >= 0.5){
            $this->apl5 = 1;
        }else if($this->fa5 <= -0.5){
            $this->apl5 = -1;
        }else{
            $this->apl5 = 0;
        }
        
        //$this->apl5 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[5]->Id_factor);
        $this->fa6 = $termo->Factor;

        if($this->fa6 >= 0.5){
            $this->apl6 = 1;
        }else if($this->fa6 <= -0.5){
            $this->apl6 = -1;
        }else{
            $this->apl6 = 0;
        }
        
        //$this->apl6 = $termo->Factor_aplicado ;
       

       $termo = TermFactorCorreccionTemp::find($model[6]->Id_factor);
        $this->fa7 = $termo->Factor;

        if($this->fa7 >= 0.5){
            $this->apl7 = 1;
        }else if($this->fa7 <= -0.5){
            $this->apl7 = -1;
        }else{
            $this->apl7 = 0;
        }

        //$this->apl7 = $termo->Factor_aplicado ;
       
       
       $termo = TermFactorCorreccionTemp::find($model[7]->Id_factor);
        $this->fa8 = $termo->Factor;

        if($this->fa8 >= 0.5){
            $this->apl8 = 1;
        }else if($this->fa8 <= -0.5){
            $this->apl8 = -1;
        }else{
            $this->apl8 = 0;
        }

        $termo = TermFactorCorreccionTemp::find($model[8]->Id_factor);
        $this->fa9 = $termo->Factor;

        if($this->fa9 >= 0.5){
            $this->apl9 = 1;
        }else if($this->fa9 <= -0.5){
            $this->apl9 = -1;
        }else{
            $this->apl9 = 0;
        }

        $termo = TermFactorCorreccionTemp::find($model[9]->Id_factor);
        $this->fa10 = $termo->Factor;

        if($this->fa10 >= 0.5){
            $this->apl10 = 1;
        }else if($this->fa10 <= -0.5){
            $this->apl10 = -1;
        }else{
            $this->apl10 = 0;
        }

        //$this->apl8 = $termo->Factor_aplicado ;
       
    }
    public function setData($idTermo)
    {
        $this->clean();
       $model = TermometroCampo::find($idTermo);
       $this->idTermo = $model->Id_termometro;
       $this->equipo = $model->Equipo;
       $this->marca = $model->Marca;
       $this->modelo = $model->Modelo;
       $this->serie = $model->Serie;
       $this->muestreador = $model->Id_muestreador;
       if ($model->deleted != null) { 
            $this->status = 0;
        } else {
           $this->status = 1;
        }
        $this->sw = true;
    }
    public function btnCreate()
    {
        $this->clean();
    }
    public function clean()
    {
        $this->alert = false;
        $this->equipo = "";
        $this->marca = "";
        $this->modelo = "";
        $this->serie = "";
        $this->muestreador = 0;
        $this->status = 0;

        $this->fa1 = '';
        $this->fa2 = '';
        $this->fa3 = '';
        $this->fa4 = '';
        $this->fa5 = '';
        $this->fa6 = '';
        $this->fa7 = '';
        $this->fa8 = '';
        
        $this->apl1 = '';
        $this->apl2 = '';
        $this->apl3 = '';
        $this->apl4 = '';
        $this->apl5 = '';
        $this->apl6 = '';
        $this->apl7 = '';
        $this->apl8 = '';
    }
}
