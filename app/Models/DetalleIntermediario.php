<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleIntermediario extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'detalle_intermediarios';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_intermediario',
        'Id_nivel',
        'Descuento',
    ];
}
