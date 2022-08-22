<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleColiformes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_enterococos';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Tipo',
        'Dilucion1',
        'Dilucion2',
        'Dilucion3',
        'Indice',
        'Muestra_tubos',
        'Tubos_negativos',
        'Tubos_positivos',
        'Presuntiva11',
        'Presuntiva12',
        'Presuntiva13',
        'Presuntiva14',
        'Presuntiva15',
        'Presuntiva16',
        'Presuntiva17',
        'Presuntiva18',
        'Presuntiva19',
        'Presuntiva11',
        'Presuntiva22',
        'Presuntiva23',
        'Presuntiva24',
        'Presuntiva25',
        'Presuntiva26',
        'Presuntiva27',
        'Presuntiva28',
        'Presuntiva29',
        'Confirmativa11',
        'Confirmativa12',
        'Confirmativa13',
        'Confirmativa14',
        'Confirmativa15',
        'Confirmativa16',
        'Confirmativa17',
        'Confirmativa18',
        'Confirmativa19',
        'Confirmativa21',
        'Confirmativa22',
        'Confirmativa23',
        'Confirmativa24',
        'Confirmativa25',
        'Confirmativa26',
        'Confirmativa27',
        'Confirmativa28',
        'Confirmativa29',
        'Resultado',
        'Observacion',
        'Liberado',
        'Analizo'
    ];
}
