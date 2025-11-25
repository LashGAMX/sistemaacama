<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotosRecepcionBlob extends Model
{
    use HasFactory;
    protected $connection = 'mysql2'; 
    // Nombre de la tabla
    protected $table = 'Fotos_recepcion';
    // Clave primaria
    protected $primaryKey = 'Id_foto';
    protected $fillable = [
        'Id_solicitud',
        'Foto',
        'Id_user_c',
        'Id_user_m',
        'created_at',
        'updated_at'
    ];
   
}

