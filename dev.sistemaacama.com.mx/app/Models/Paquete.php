<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'paquetes';
    protected $primaryKey = 'Id_paquete';
    public $timestamps = true;

    protected $fillable = [
        'Nombre_paquete',
        'Tipo_paquete',        
        'Id_user_c',
        'Id_user_m',
        'Status'
    ];
}
