<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CurvaConstantes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'curva_constantes';
    protected $primaryKey = 'Id_curvaConst';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'B',
        'M',
        'R',
        'Fecha_inicio',
        'Fecha_fin',
        'Id_area',
        'Id_parametro',
        'Id_user_c',
        'Id_user_m'
    ];
}