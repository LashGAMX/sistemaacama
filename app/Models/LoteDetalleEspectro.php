<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleEspectro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_espectro';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Descripcion',
        'Resultado',
        'Promedio',
        'Abs1',
        'Abs2',
        'Abs3',
        'De_color',
        'Nitratos',
        'Nitritos',
        'Sulfuros',
        'Vol_aforo',
        'Vol_destilacion',
        'Vol_muestra',
    ];

}
