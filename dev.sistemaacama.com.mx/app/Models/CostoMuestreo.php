<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostoMuestreo extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'costo_muestreo';
    protected $primaryKey = 'Id_costo';
    public $timestamps = true;

    protected $fillable = [
        'Insumo',
        'Desgaste_km',
        'Rendimiento',
        'Gasolina',
        'Comida',
        'Pago_muestreador',
        'Ganancia',
        'Id_user_c',
        'Id_user_m'
    ];

}
