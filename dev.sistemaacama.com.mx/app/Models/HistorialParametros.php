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
        'Tipo_formula',
        'Rama',
        'Parametro',
        'Unidad',
        'Metodo',
        'Norma',
        'Limite',
        'Procedimiento',
        'Matriz',
        'Simbologia',
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
