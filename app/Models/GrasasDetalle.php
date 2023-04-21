<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrasasDetalle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'grasas_detalle';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Calentamiento_temp1',
        'Calentamiento_temp2',
        'Calentamiento_temp3',
        'Calentamiento_entrada1',
        'Calentamiento_entrada2',
        'Calentamiento_entrada3',
        'Calentamiento_salida1',
        'Calentamiento_salida2',
        'Calentamiento_salida3',
        'Enfriado_entrada1',
        'Enfriado_entrada2',
        'Enfriado_entrada3',
        'Enfriado_salida1',
        'Enfriado_salida2',
        'Enfriado_salida3',
        'Enfriado_pesado1',
        'Enfriado_pesado2',
        'Enfriado_pesado3',
        'Secado_temp',
        'Secado_entrada',
        'Secado_salida',
       
        'Reflujo_entrada',
        'Reflujo_salida',
        'Enfriado_matraces_entrada',
        'Enfriado_matraces_salida',
    ];
}
