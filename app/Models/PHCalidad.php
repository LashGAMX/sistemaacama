<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PHCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'ph_calidad';
    protected $primaryKey = 'Id_ph';
    public $timestamps = true;

    protected $fillable = [
        'Ph_calidad',
        'Marca',
        'Lote',
        'Inicio_caducidad',
        'Fin_caducidad',
        'Id_user_c',
        'Id_user_m'
    ];
}
