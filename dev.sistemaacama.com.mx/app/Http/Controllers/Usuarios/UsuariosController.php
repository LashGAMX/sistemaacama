<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use App\Models\MenuRol;
use App\Models\MenuUsuarios;
use App\Models\ParametroUsuario;
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
    
    public function menuUser($id)
    {
        $model = DB::table('menu_usuarios')->where('Id_user',$id)->get();
        $padre = DB::table('menu_items')->where('parent_id',NULL)->where('menu_id',1)->get();
        $hijo = DB::table('menu_items')->get();
        $user = DB::table('users')->where('id',$id)->first();
        return view('usuarios.menuUser',compact('model','padre','hijo','user'));
    }
    public function assignMenu(Request $res)
    {
        $model = DB::table('users')->where('id',$res->idUser)->first();
        $menuPerfil = DB::table('menu_rol')->where('Id_rol',$model->role_id)->get();
        DB::table('menu_usuarios')->where('Id_user',$res->idUser)->delete();
        foreach ($menuPerfil as $item) {
            MenuUsuarios::create([
                'Id_user' => $res->idUser,
                'Id_item' => $item->Id_item,
            ]);
        }
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setMenuUser(Request $res)
    {
        DB::table('menu_usuarios')->where('Id_user',$res->idUser)->delete();
        for ($i=0; $i < sizeof($res->menus); $i++) { 
            MenuUsuarios::create([
                'Id_user' => $res->idUser,
                'Id_item' => $res->menus[$i],
            ]);
        }
        $data = array(
            'res' => $res->menus,
        );
        return response()->json($data);
    }
    public function menuPerfil()
    {
        $roles = DB::table('roles')->get();
        $model = DB::table('menu_items')->get();
        $padre = DB::table('menu_items')->where('parent_id',NULL)->where('menu_id',1)->get();
        return view('usuarios.menuPerfil',compact('roles','model','padre'));
    }
    public function getMenuPerfil(Request $res)
    { 
        $model = DB::table('menu_rol')->where('Id_rol',$res->perfil)->get();
        $padre = DB::table('menu_items')->where('parent_id',NULL)->where('menu_id',1)->get();
        $hijo = DB::table('menu_items')->get();
        $data = array(
            'model' => $model,
            'padre' => $padre, 
            'hijo' => $hijo,
        );
        return response()->json($data);
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
    public function listaxparam()
    {
        $model = Usuario::all();
        return view('usuarios.lista_x_usuarios', compact('model'));
    }
    public function parametroUser($id)
    {
        $model = DB::table('ViewParametros')->get();
        $parametros = ParametroUsuario::where('Id_user',$id)->get();
        $user = DB::table('users')->where('id',$id)->first();
        return view('usuarios.parametroUser',compact('model','user','parametros'));
    }
    public function setParametroUser(Request $res)
    {
        DB::table('parametro_usuarios')->where('Id_user',$res->idUser)->delete();
        for ($i=0; $i < sizeof($res->parametros); $i++) { 
            ParametroUsuario::create([
                'Id_user' => $res->idUser,
                'Id_parametro' => $res->parametros[$i],
            ]);
        }
        $data = array(
            'res' => $res->parametros,
        );
        return response()->json($data);
    }
}
