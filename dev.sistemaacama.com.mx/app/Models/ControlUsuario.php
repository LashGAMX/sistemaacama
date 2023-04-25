<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlUsuario extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'control_usuarios';
    protected $primaryKey = 'Id_nivel_modulos';
    public $timestamps = true;

    protected $fillable = [
        'Id_modulos',
        'Id_usuarios_nivel',
        'Tipo_nivel',
        'Nombre_modulo',
        'Nodo_padre',
        'Nodo_hijo',
        'Url',
        'Icono',
        'status_checkbox',
        'Id_user_c',
        'Id_user_m'
    ];
}
