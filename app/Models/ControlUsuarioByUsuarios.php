<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlUsuarioByUsuarios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'control_usuario_by_usuarios';
    protected $primaryKey = 'Id_usuario_vista';
    public $timestamps = true;

    protected $fillable = [
        'Id_usuario',
        'Id_modulo',
        'Nombres',        
        'Nombre_modulo',        
        'Url',
        'Nodo_hijo',        
        'status_checkbox',
        'Id_user_c',
        'Id_user_m'
    ];
}
