<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TituloConsecion extends Model
{
    use HasFactory,SoftDeletes;
        
    protected $table = 'titulo_concesion';
    protected $primaryKey = 'Id_titulo';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Titulo',  
    ];
}
