<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SolicitudesAlimentos extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'solicitudes_alimentos';
  protected $primaryKey = 'Id_solicitud';
  public $timestamps = true;

  protected $fillable = [
    'Folio',
    'Num_muestras',
    'Id_cliente',
    'Cliente',
    'Id_sucursal',
    'Sucursal',
    'Fecha_muestreo',
    'Hora_muestreo',
    'Id_direccion',
    'Direccion',
    'Atencion',
    'Id_contacto',
    'Id_servicio',
    'Servicio',
    'Id_norma',
    'Norma',
    'Id_subnorma',
    'Sub_norma',
    'Estatus',
    'Liberado',
    'Cancelado',
    'Observacion',
    'Creado_por',
    'Actualizado_por',
    'Id_cotizacion',
  ];
  public function cotizacion()
  {
    return $this->belongsTo(Cotizacion::class, 'Id_cotizacion', 'Id_cotizacion');
  }
  public function contacto()
  {
    return $this->belongsTo(SucursalContactos::class, 'Id_contacto', 'Id_contacto');
  }
  public function servicio()
  {
    return $this->belongsTo(TipoServicios::class, 'Id_servicio', 'Id_tipo');
  }
  public function dir()
  {
    return  $this->belongsTo(DireccionReporte::class, 'Id_direccion', 'Id_direccion');
  }
  public function usuario()
  {
    return  $this->belongsTo(User::class, 'Creado_por', 'id');
  }
    public function usuario2()
  {
    return  $this->belongsTo(User::class, 'Actualizado_por', 'id');
  }

}
