<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BitacoraColiformes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bitacora_coliformes';
    protected $primaryKey = 'Id_bitacora';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Sembrado',
        'Fecha_resiembra',
        'Num_tubo',
        'Bitacora',
        'Preparacion_pre',
        'Lectura_pre',
        'Medio_con',
        'Preparacion_con',
        'Lectura_con',
        'Id_user_C',
        'Id_user_m',
    ];
}
