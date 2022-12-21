<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParametroUsuario extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'parametro_usuarios';
    protected $primaryKey = 'Id_pa';
    public $timestamps = true;

    protected $fillable = [
        'Id_user',
        'Id_parametro',
    ];
}

