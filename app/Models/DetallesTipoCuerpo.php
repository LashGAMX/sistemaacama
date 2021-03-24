<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesTipoCuerpo extends Model
{
    use HasFactory;
    protected $table = 'detalles_tipoCuerpo';
    protected $primaryKey = 'Id_detalle';
    

    protected $fillable = [
        'Id_detalle',
        'Id_cuerpo',
        'Categoria'
    ];
}
