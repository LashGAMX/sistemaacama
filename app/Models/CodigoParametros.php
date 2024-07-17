<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users;


class CodigoParametros extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'codigo_parametro';
    protected $primaryKey = 'Id_codigo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud', 
        'Id_parametro', 
        'Codigo',       
        'Num_muestra',
        'Resultado',
        'Resultado2',
        'Resultado_aux',  
        'Ph_muestra',
        'Id_lote',
        'Asignado',
        'Analizo',
        'Reporte',
        'Mensual',
        'Cadena',
        'Cancelado',
        'Liberado',
        'Id_user_c',
        'Id_user_m',
        'Historial',
    ];
  

  
}
