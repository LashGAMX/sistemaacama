<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialCampoCaptCompuesto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hist_campoCaptCompuesto';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_campo',
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
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m',
    ];
}
