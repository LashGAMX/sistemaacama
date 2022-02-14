<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleColiformes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_coliformes';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Id_control',
        'Dilucion1',
        'Dilucion2',
        'Dilucion3',
        'Indice',
        'Muestra_tubos',
        'Tubos_negativos',
        'Tubos_positivos',
        'Confirmativa1',
        'Confirmativa2',
        'Confirmativa3',
        'Confirmativa4',
        'Confirmativa5',
        'Confirmativa6',
        'Confirmativa7',
        'Confirmativa8',
        'Confirmativa9',
        'Presuntiva1',
        'Presuntiva2',
        'Presuntiva3',
        'Presuntiva4',
        'Presuntiva5',
        'Presuntiva6',
        'Presuntiva7',
        'Presuntiva8',
        'Presuntiva9',
        'Resultado',
        'Observacion',
        'Liberacion' 
    ];
}
