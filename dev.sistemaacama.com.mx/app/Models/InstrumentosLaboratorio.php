<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InstrumentosLaboratorio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instrumentos_laboratorios';
    protected $primaryKey = 'Id_instrumentos_laboratorios';
    public $timestamps = true;

    protected $fillable = [
        'Codigo_instrumento',
        'Descripcion',
        'Valor_real',
        'Valor_actual',
        'Valor_configurado',
        'Id_user_c',
        'Id_user_m'
    ];
}
