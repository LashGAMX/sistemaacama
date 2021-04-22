<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoUsuario extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'grupos_usuarios';
    protected $primaryKey = 'Id_grupos_usuarios';
    public $timestamps = true;

    protected $fillable = [
        'Grupo',
        'Usuario'
    ];
}
