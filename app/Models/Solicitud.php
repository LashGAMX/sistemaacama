<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'solicitudes';
    protected $primaryKey = 'Id_solicitud';
    public $timestamps = true;
    
    protected $fillable = [
      'Id_cotizacion',
      'Folio',
      'Folio_servicio',
      'Id_intermediario',
      'Id_cliente',
      'Siralab',
      'Id_sucursal',
      'Id_direccion',
      'Id_contacto',
      'Atencion',
      'Observacion',
      'Observacion_plan',
      'Id_servicio',
      'Id_descarga',
      'Id_norma',
      'Id_subnorma',
      'Fecha_muestreo',
      'Id_muestreo',
      'Num_tomas',
      'Id_muestra',
      'Id_promedio',
      'Id_reporte',
      'Id_reporte2',
      'Padre',
      'Hijo',
      'Nota_4',
      'Liberado',
      'Cancelado',
      'Obs_cancelado',
      'Id_user_c',
      'Id_user_m'
    ];

    public function direccion()
    {
     return $this->belongsTo(DireccionReporte::class, 'Id_direccion', 'Id_direccion');
    }
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'Id_cliente', 'Id_cliente');
    }
    public function Sucursal()
    {
        return $this->belongsTo(SucursalCliente::class, 'Id_sucursal', 'Id_sucursal');
    }
    public function norma()
    {
      return $this->belongsTo(Norma::class,"Id_norma","Id_norma");
    }
    public function servicio()
    {
        return $this->belongsTo(TipoServicios::class,"Id_servicio", "Id_tipo");
    }
    public function cotizacion()
    {
      return $this->belongsTo(Cotizacion::class,"Id_cotizacion","Id_cotizacion");
    }
    public function descarga()
    {
      return $this->belongsTo(TipoDescarga::class,"Id_descarga","Id_tipo");
    }
    public function UserC()
    {
      return $this->belongsTo(User::class,"Id_user_c","id");

    }
    public function UserM()
    {
      return $this->belongsTo(User::class,"Id_user_m","id");

    }
    public function puntos()
    {
      return $this->hasMany(SolicitudPuntos::class, 'Id_solicitud', 'Id_solicitud');
    }
    
    public function procesos()
    {
     return $this->hasMany(ProcesoAnalisis::class, 'Id_solicitud', 'Id_solicitud');
    }
   
   
  
    
}
