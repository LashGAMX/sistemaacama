<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionMetales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'configuracion_metales';
    protected $primaryKey = 'Id_configuracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Equipo',
        'No_inventario',
        'Energia',
        'Lampara',
        'No_lampara',
        'Concentracion',
        'Longitud_onda',
        'Slit',
        'Gas',
        'Flujo_gas',
        'Aire',
        'Oxido_nitroso',
        'Hidruros',
        'Bitacora_curva',
        'Sup_std1',
        'Sup_std2',
        'Sup_std3',
        'Sup_std4',
        'Sup_std5',
        'Abs_std1',
        'Abs_std2',
        'Abs_std3',
        'Abs_std4',
        'Abs_std5',
        'Inf_std1',
        'Inf_std2',
        'Inf_std3',
        'Inf_std4',
        'Inf_std5',
    ];
}
