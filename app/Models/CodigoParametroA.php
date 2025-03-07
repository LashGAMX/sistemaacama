<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoParametroA extends Model
{
    use HasFactory;
    protected $table = 'codigo_parametroa';
    protected $primaryKey = 'Id_codigo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_parametro',
        'Codigo',
        'Num_muestra',
        'Resultado',
        'Resultado2',
        'Ph_muestra',
        'Id_lote',
        'Cadena',
        'Asignado',
        'Analizo',
        'Reporte',
        'Mensual',
        'Cancelado',
        'Liberado',
        'Id_user_c',
        'Id_user_m',
        'Historial',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
