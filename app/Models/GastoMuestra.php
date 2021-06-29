<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GastoMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'temperatura_muestra';
    protected $primaryKey = 'Id_temperatura';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Gasto1',
        'Gasto2',
        'Gasto3',
        'Promedio',
        
    ];
}
