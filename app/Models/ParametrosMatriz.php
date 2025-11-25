<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParametrosMatriz extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'parametros_matriz';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Id_matriz_parametro',
        'Id_unidad',
        'Limite',
        'Dias_analisis'
    ];

  
    public function matriz()
    {
        return $this->belongsTo(MatrizParametro::class, 'Id_matriz_parametro', 'Id_matriz_parametro');
    }
    public function parametro()
    {
        return $this->belongsTo(Parametro::class, 'Id_parametro','Id_parametro');
    }
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'Id_unidad','Id_unidad');
    }

}

 
