<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialParametros extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hist_analisisPara';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
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
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m'
    ];
}
