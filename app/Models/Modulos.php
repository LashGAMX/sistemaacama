<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'modulos';
    protected $primaryKey = 'Id_modulos';
    public $timestamps = true;

    protected $fillable = [
        'Nombre_modulo',
        'Url',        
        'Descripcion',
        'Nodo_padre',
        'Nodo_hijo',
        'Status',
        'icono',
        'Id_user_c',
        'Id_user_m'
    ];
}
