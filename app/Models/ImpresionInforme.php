<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpresionInforme extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'impresion_informe';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Encabezado',
        'Nota',
        'Id_analizo',
        'Id_reviso',
        'Fecha_inicio',
        'Fecha_fin',
        'Clave', 
        'Num_rev',
        'Obs_impresion',
    ];
}
