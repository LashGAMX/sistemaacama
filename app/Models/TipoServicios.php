<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicios extends Model
{
    use HasFactory;
    protected $table = 'tipo_servicios';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Servicio',
        'Descripcion'
    ];
}
