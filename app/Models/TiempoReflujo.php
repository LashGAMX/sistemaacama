<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiempoReflujo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'tiempo_reflujo';
    protected $primaryKey = 'Id_tiempo';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',                
        'Entrada',
        'Salida',        
        'Id_user_c',
        'Id_user_m',
    ];
}