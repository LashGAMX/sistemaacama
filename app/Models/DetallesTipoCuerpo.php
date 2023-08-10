<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesTipoCuerpo extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'detalle_tipocuerpos';
    protected $primaryKey = 'Id_detalle';
    
    protected $fillable = [
        'Id_tipo',
        'Detalle',
        'Id_user_c',
        'Id_user_m'
    ];
} 
 