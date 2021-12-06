<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleMatrizParam extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'detalle_norma';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Clave',
        'Clasificacion',
        'Tipo_descarga',
        'Status',
        'Id_user_c',
        'Id_user_m'
    ];
}
