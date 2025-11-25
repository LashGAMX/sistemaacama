<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudMuestraA extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'solicitud_muestrasa';
  protected $primaryKey = 'Id_muestra';
  public $timestamps = true;

  protected $fillable = [
    'Id_solicitud',
    'Muestra',
    'Id_norma',
    'Fecha_muestreo',
    'Tem_muestra',
    'Tem_recepcion',
    'Observacion',
    'Unidad',
    'Cantidad',
    'Calculo',
    'Cumple',
    'Motivo',
    'Cancelado'
  ];

  public function parametrosMatriz()
  {
    return $this->belongsTo(MatrizParametro::class, 'Id_matriz_parametro', 'Id_matriz_parametro');
  }
  public  function recepcion()
  {
    return $this->hasOne(RecepcionAlimentos::class, 'Id_rep', 'Id_muestra');
  }
  protected static function boot()
  {
      parent::boot();
      static::updated(function ($solicitud) {
          // Verificar si el campo Muestra fue modificado    
          if ($solicitud->isDirty('Muestra')) {
              $nuevoValor = $solicitud->Muestra;
              $idMuestra = $solicitud->Id_muestra;
              // Buscar en RecepcionAlimentos y actualizar el campo Muestra
              $recepcion = RecepcionAlimentos::where('Id_muestra', $idMuestra)->first();
              if ($recepcion) {
                  $recepcion->Muestra = $nuevoValor;
                  $recepcion->save();
       }
          }
      });
  }
}
    