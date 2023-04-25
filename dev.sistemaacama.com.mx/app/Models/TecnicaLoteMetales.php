<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TecnicaLoteMetales extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'tecnica_lote_metales';
    protected $primaryKey = 'Id_tecnica';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Fecha_hora_dig',
        'Longitud_onda',
        'Flujo_gas',
        'Equipo',
        'Num_inventario',
        'Num_invent_lamp',
        'Slit',
        'Corriente',
        'Energia',
        'Conc_std',
        'Gas',
        'Aire',
        'Oxido_nitroso',
        'Fecha_preparacion',
        'Id_user_c',
        'Id_user_m'
    ];
}
