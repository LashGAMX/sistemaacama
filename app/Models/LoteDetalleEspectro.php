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
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Observacion',
        'Resultado',
        'Promedio',
        'Abs1',
        'Abs2',
        'Abs3',
        'Abs4',
        'Abs5',
        'Abs6',
        'Abs7',
        'Abs8',
        'B',
        'M',
        'R',
        'Ph_ini',
        'Ph_fin',
        'De_color',
        'Nitratos',
        'Nitritos',
        'Sulfuros',
        'Blanco',
        'Vol_dilucion',
        'Vol_aforo',
        'Vol_destilacion',
        'Vol_muestra',
        'Liberado',
        'Analizo',
    ];

}
