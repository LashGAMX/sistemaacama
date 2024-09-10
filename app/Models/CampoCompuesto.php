<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CampoCompuesto extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_compuesto';
    protected $primaryKey = 'Id_campo';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Metodo_aforo',
        'Con_tratamiento',
        'Tipo_tratamiento',
        'Proce_muestreo',
        'Observaciones',
        'Obser_solicitud',
        'Ph_muestraComp',
        'Temp_muestraComp',
        'Volumen_calculado',
        'Cloruros',
        'Cloro',
        'Id_user_c',
        'Id_user_m'
    ];
}
