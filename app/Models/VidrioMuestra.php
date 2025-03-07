<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VidrioMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'vidrio_muestra';
    protected $primaryKey = 'Id_vidrio';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Num_toma',
        'Oxigeno',
        'Burbujas',
        'Activo',
        'Id_user_c',
        'Id_user_m'
    ];

}
