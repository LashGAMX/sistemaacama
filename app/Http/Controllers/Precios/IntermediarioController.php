<?php

namespace App\Http\Controllers\Precios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class IntermediarioController extends Controller
{
    //
    use WithPagination;
    
    public $search = '';
    protected $queryString = ['search' => ['except' => '']];
    public $sw = false;
    public $perPage = 30;
    public $alert = false;
    
    public function index()
    {
        $model = DB::table('ViewPrecioPaq')->get();
        return view('precios.intermediario',compact('model'));
    }
}
