<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConvinacionesEcoli extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'convinaciones_Ecoli';
    protected $primaryKey = 'Id_convinacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_detalle',
        'Colonia',
        'Indol',
        'Rm',
        'Vp',
        'Citraro',
        'BGN',
        'Observacion'
        
    ];
}
