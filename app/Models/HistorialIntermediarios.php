<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialIntermediarios extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hist_clientesInter';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [   
        'Id_cliente',
        'Id_intermediario',
        'Laboratorio',
        'Correo',
        'Direccion',
        'Tel_oficina',
        'Extension',
        'Celular1',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
