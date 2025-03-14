<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudParametrosA extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'solicitud_parametrosa';
    protected $primaryKey = 'Id_solParametro';
    public $timestamps = true;

    protected $fillable = [
        'Id_muestra',
        'Id_solicitud',
        'Id_parametro',
    ];
    public function par()
  {
    return  $this->belongsTo(Parametro::class, 'Id_parametro', 'Id_parametro');
  }

}
