<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoRecepcionDB2 extends Model
{
    protected $connection = 'mysql2'; 
    protected $table = 'foto_recepcion'; 
    protected $primaryKey = 'Id_foto_recepcion';
    public $timestamps = false; 

    protected $fillable = 
    [
        'Id_solicitud',
        'Foto',
        'Id_user_c',
        'Id_user_m',
    ];
}
