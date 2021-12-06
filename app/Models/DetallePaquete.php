<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePaquete extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'detalles_paquetes';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_paquete',
        'Id_parametro',
        'F_creacion',
        'F_modificacion',
        'Status',
        'Id_user_c',
        'Id_user_m'
    ];
}
