<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecadoCartucho extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'secado_cartuchos';
    protected $primaryKey = 'Id_secado';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Temperatura',        
        'Entrada',
        'Salida',        
        'Id_user_c',
        'Id_user_m',
    ];
}