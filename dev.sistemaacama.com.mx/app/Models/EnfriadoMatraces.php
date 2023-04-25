<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnfriadoMatraces extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'enfriado_matraces';
    protected $primaryKey = 'Id_enfriado';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Masa_constante',        
        'Entrada',
        'Salida',
        'Pesado_matraz',
        'Id_user_c',
        'Id_user_m',
    ];
}