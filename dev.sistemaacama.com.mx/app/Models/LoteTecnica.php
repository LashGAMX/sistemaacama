<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteTecnica extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'lote_tecnica';
    protected $primaryKey = 'Id_tecnica';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Fecha_gestacion',
        'Longitud_onda',
        'Flujo_gas',
        'No_inventario',
        'No_lampara',
        'Sit',
        'Corriente',
        'Energia',
        'Con_std',
        'Gas',
        'Aire',
        'Oxido_nitroso',
        'Id_user_c',
        'Id_user_m'
    ];
}
