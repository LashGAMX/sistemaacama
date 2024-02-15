<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SolicitudesGeneradas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'solicitudes_generadas';
    protected $primaryKey = 'Id_solicitudGen';
    public $timestamps = true;

    protected $fillable = [
      'Id_solicitudGen',
      'Folio',
      'Id_solicitud',
      'Id_solPadre',
      'Punto_muestreo',
      'Captura',
      'Id_muestreador',
      'A_paterno',
      'A_materno',
      'Nombres',
      'Observacion',
      'Estado',
      'Id_superviso', 
      'Id_user_c',
      'Id_user_m'
    ];
}
