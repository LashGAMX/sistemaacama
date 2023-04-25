<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetalesDetalle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'metales_detalle';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_detalle', 
        'Id_lote',
        'Fecha_digestion',
        'Longitud_onda',
        'No_inventario',
        'Corriente',
        'Gas',
        'Flujo_gas',
        'No_lampara',
        'Energia',
        'Aire',
        'Equipo',
        'Slit',
        'Conc_std',
        'Oxido_nitroso',
        'Verificacion_blanco',
        'Abs_teoricoB',
        'Abs1B',
        'Abs2B',
        'Abs3B',
        'Abs4B',
        'Abs5B',
        'PromedioB',
        'ConclusionB',
        'Std_calE',
        'Abs_teoricoE',
        'ConcE',
        'Abs1E',
        'Abs2E',
        'Abs3E',
        'Abs4E',
        'Abs5E',
        'PromedioE',
        'MasaE',
        'ConclusionE',
        'Conc_obtenidaE',
        'RecuperacionE',
        'CumpleE',
        'ConcI',
        'DesvI',
        'CumpleI',
        'Abs1I',
        'Abs2I',
        'Abs3I',
        'Abs4I',
        'Abs5I',
        'Bitacora',
        'Folio',
        'Valor',
    ];
}
