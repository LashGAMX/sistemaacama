<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intermediario extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'intermediarios';
    protected $primaryKey = 'Id_intermediario';
    public $timestamps = true;

    protected $fillable = [
        'Id_intermediario',
        'Id_cliente',
        'Nombres',
        'A_paterno',
        'A_materno',
        'RFC',
        'Laboratorio',
        'Correo',
        'Direccion',
        'Tel_oficina',
        'Extension',
        'Celular1',
        'Detalle',
        'Id_user_c',
        'Id_user_m',
    ];
} 
