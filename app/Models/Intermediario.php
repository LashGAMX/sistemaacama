<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intermediario extends Model
{
    use HasFactory;
    protected $table = 'intermediarios';
    protected $primaryKey = 'Id_intermediario';
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Laboratorio',
        'Correo',
        'Direccion',
        'Tel_oficina',
        'Extension',
        'Celular',
        'Detalle'
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
