<?php

namespace App\Http\Livewire\Config;

use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TableSucursal extends Component
{

    use WithPagination;

    public $idUser;
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $perPage = 50;
    public $show = false;
    public $name;
    public $idSuc;
    public $alert = false;


    protected $rules = [ 
        'name' => 'required|min:6',
    ];
    protected $messages = [
        'name.required' => 'El nombre es un dato requerido',
    ];
 
    public function render() //Loop
    { 

        $sucursal = Sucursal::where('Sucursal','LIKE',"%{$this->search}%")
        ->orWhere('Id_sucursal','LIKE',"%{$this->search}%")
        ->paginate($this->perPage);
        return view('livewire.config.table-sucursal',compact('sucursal'));
    } 
    public function setSucursal()
    {
      $this->validate();
      Sucursal::create([
          'Sucursal' => $this->name,
          'Id_user_c' => $this->idUser, 
          'Id_user_m' => $this->idUser, 
      ]);
      $this->alert = true;
    }
    public function store()
    {
        $this->validate();
        $sucursal = Sucursal::find($this->idSuc);
        $sucursal->Sucursal = $this->name;
        $sucursal->Id_user_m = $this->idUser;
        $sucursal->save();
        $this->alert = true;
    }
    public function setData($id,$name)
    {
        $this->resetValidation();
        $this->idSuc = $id;
        $this->name = $name;
        $this->alert = false;
    }

    public function setBtn()
    {  
        $this->clean();
        $this->resetValidation();
        $this->show = true;
        $this->alert = false;
    }
    public function deleteBtn()
    {
        if($this->show == true)
        {
            $this->show = false;
        }
    }
    public function resetAlert()
    {
        $this->alert = false;
    }
    public function clean()
    {
        $this->name ='';
        $this->idSuc = '';
    }
    

}
  