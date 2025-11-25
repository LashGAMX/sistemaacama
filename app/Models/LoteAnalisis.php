<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteAnalisis extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'lote_analisis';
    protected $primaryKey = 'Id_lote';
    public $timestamps = true;

    protected $fillable = [
        'Id_area',
        'Id_tecnica',
        'Id_modulo',
        'Asignado',
        'Liberado',
        'Supervisado',
        'Id_superviso',
        'Fecha',
        'Hora',
        'Id_user_c',
        'Id_user_m',
        'Libero',
    ];
}
 