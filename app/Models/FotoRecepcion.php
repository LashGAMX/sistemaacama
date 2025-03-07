<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoRecepcion extends Model
{
    use HasFactory;
    
    protected $table = 'foto_recepcion';
    protected $primaryKey = 'Id_foto_recepcion';
    public $timestamps = true;

    protected $fillable = 
    [
        'Id_solicitud',
        'Foto',
        'Id_user_c',
        'Id_user_m',
    ];
}