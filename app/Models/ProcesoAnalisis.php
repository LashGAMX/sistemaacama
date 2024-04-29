<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProcesoAnalisis extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'proceso_analisis'; 
    protected $primaryKey = 'Id_procAnalisis';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Folio',
        'Descarga',
        'Cliente',
        'Empresa',
        'Ingreso',
        'Proceso',
        'Supervicion',
        'Impresion_cadena',
        'Impresion_informe',
        'Entrega_resultado',
        'Historial_resultado',
        'Reporte',
        'ClienteG',
        'Hora_recepcion',
        'Hora_entrada',
        'Liberado',
        'Cancelado',
        'Obs_proceso',
        'Id_user_c',
        'Id_user_m'
    ];
}