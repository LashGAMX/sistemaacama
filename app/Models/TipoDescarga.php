<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDescarga extends Model
{
    use HasFactory;
    protected $table = 'tipo_descargas';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Descarga',
        'Descripcion'
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
