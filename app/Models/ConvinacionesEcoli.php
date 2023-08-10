<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConvinacionesEcoli extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'convinacion_ecoli';
    protected $primaryKey = 'Id_convinacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_detalle',
        'Id_lote',
        'Codigo',
        'Colonia',
        'Indol',
        'Rm',
        'Vp',
        'Citrato',
        'BGN',
        'Indol2',
        'Rm2',
        'Vp2',
        'Citrato2',
        'BGN2',
        'ResUno',
        'ResDos',
        'Resultado',
        'Observacion'
        
    ];
}
