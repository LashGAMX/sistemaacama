<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrecioPaquete extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'precio_paquete';
    protected $primaryKey = 'Id_precio';
    public $timestamps = true;

    protected $fillable = [
        'Id_paquete',
        'Precio',
        'Id_user_c',
        'Id_user_m',
    ];
}
