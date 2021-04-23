<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'parametros';
    protected $primaryKey = 'Id_parametro';
    public $timestamps = true;

    protected $fillable = [
        'Id_laboratorio',
        'Id_tipo_formula',
        'Id_rama',
        'Parametro',
        'Id_unidad',
        'Id_metodo',
        'Id_norma',
        'Limite',
        'Id_procedimiento',
        'Id_matriz',
        'Id_simbologia',
        'F_inicio_vigencia',
        'F_fin_vigencia',
        'Precio',
        'Id_user_c',
        'Id_user_m'
    ];
}
