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
 public function User()
    {
      return $this->belongsTo(User::class,"Id_user_c","id");

    }
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'Id_solicitud', 'Id_solicitud');
    }
    public function puntos()
    {
        return $this->belongsTo(SolicitudPuntos::class, 'Id_solicitud', 'Id_solicitud');
    }
     public function phmuestra()
    {
        return $this->belongsTo(PhMuestra::class, 'Id_solicitud', 'Id_solicitud');
    }
  
    
}