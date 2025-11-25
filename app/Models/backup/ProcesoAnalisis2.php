<?php
namespace App\Models\backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProcesoAnalisis2 extends Model
{
    use HasFactory,SoftDeletes;
    protected $connection = 'mysqlrespaldo'; 
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
        'Emision_informe',
        'Firma_aut',
        'Firma_superviso',
        'Firma_autorizo',
        'Liberado',
        'Cancelado',
        'Obs_proceso',
        'Obs_recepcion',
        'Pass_archivo',
        'Pagado',
        'Auditoria',
        'Id_user_c',
        'Id_user_m'
    ];
}