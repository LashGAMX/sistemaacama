<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoReporte extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_reportes';
    protected $primaryKey = 'Id_tipo_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Tipo',
        'Descripcion',        
        'Id_user_c',
        'Id_user_m'
    ];
}
