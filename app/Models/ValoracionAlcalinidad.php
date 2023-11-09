<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionAlcalinidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_alcalinidad';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true; 

    protected $fillable = [
        'Id_lote',
        'Id_parametro', 
        'Granos_carbon1',
        'Granos_carbon2',
        'Granos_carbon3',
        'Titulado1',
        'Titulado2',
        'Titulado3',
        'Granos_equivalente',
        'Factor_conversion',
        'Fecha_inicio',
        'Fecha_fin',
        'Resultado',
    ];
}
