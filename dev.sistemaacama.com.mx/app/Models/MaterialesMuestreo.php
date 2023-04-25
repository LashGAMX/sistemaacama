<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MaterialesMuestreo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'materiales_muestreo';
    protected $primaryKey = 'Id_material';
    public $timestamps = true;

    protected $fillable = [
        'Id_formula',
        'Analisis',
        'Preservador',
        'Id_recipiente',
        'Volumen',
        'Id_unidad',
        'Id_user_c',
        'Id_user_m'
    ];
    
}
