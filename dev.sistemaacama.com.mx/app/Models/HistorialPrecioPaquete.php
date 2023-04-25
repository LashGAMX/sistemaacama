<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialPrecioPaquete extends Model
{
    use HasFactory, SoftDeletes;

    use HasFactory, SoftDeletes;
    protected $table = 'hist_precioPaquete';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        
        'Id_precio',
        'Id_paquete',
        'Id_norma',
        'Norma',
        'Clave',
        'Precio',
        'Id_tipo',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m' 

    
    ];
}
