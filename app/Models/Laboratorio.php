<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratorio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'laboratorios';
    protected $primaryKey = 'Id_laboratorio';
    public $timestamps = true;

    protected $fillable = [
        'Laboratorio',        
        'Id_usuario_c',
        'F_creacion',
        'Id_usuario_m',
        'F_modificacion',
        'Status'
    ];    
}
 