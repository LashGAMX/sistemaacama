<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use App\Models\MenuRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    //
    public function index()
    {
        $model = Usuario::all();
        return view('usuarios.lista_usuarios', compact('model'));
    }
    public function menuPerfil()
    {
        $roles = DB::table('roles')->get();
        $model = DB::table('menu_items')->get();
        $padre = DB::table('menu_items')->where('parent_id',NULL)->get();
        return view('usuarios.menuPerfil',compact('roles','model','padre'));
    }
    public function getMenuPerfil(Request $res)
    {
        
        $data = array(

        );
    }
    public function setMenuPerfil(Request $res)
    {
        DB::table('menu_rol')->where('Id_rol',$res->perfil)->delete();
        for ($i=0; $i < sizeof($res->menus); $i++) { 
            MenuRol::create([
                'Id_rol' => $res->perfil,
                'Id_item' => $res->menus[$i],
            ]);
        }
        $data = array(
            'res' => $res->menus,
        );
        return response()->json($data);
    }
}
