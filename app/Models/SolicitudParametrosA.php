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
        'Id_matrizar'
    ];
    public function par()
  {
    return  $this->belongsTo(Parametro::class, 'Id_parametro', 'Id_parametro');
  } //aqui solo se obtiene el campo  Parametro
  public function ParMat()
  {
    return  $this->belongsTo(ParametrosMatriz::class, 'Id_matrizar', 'Id');
  }//aqui solo se obtiene el campo Limite, Tambien debo obtener del Pivot    public function unidad(){return $this->belongsTo(Unidad::class, 'Id_unidad','Id_unidad');} la columna Unidad 

  public function Matriz()
  {
    return $this->belongsTo(MatrizParametro::class, 'Id_matrizar', 'Id_matriz_parametro');
  } // aqui solo se obtiene el campo Matriz
}
