<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnfriadoMatraz extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'enfriado_matraz';
    protected $primaryKey = 'Id_enfriado';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',        
        'Entrada',
        'Salida',        
        'Id_user_c',
        'Id_user_m',
    ];
}