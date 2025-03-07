<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudMuestraA extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'solicitud_muestrasa';
    protected $primaryKey = 'Id_muestra';
    public $timestamps = true;
    
    protected $fillable = [
      'Id_solicitud',
      'Muestra',
      'Id_norma',
      'Tem_muestra',
      'Tem_recepcion',
      'Observacion',
    ];

}
