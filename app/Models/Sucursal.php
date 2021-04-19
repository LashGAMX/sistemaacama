<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';
    protected $primaryKey = 'Id_sucursal';
    public $timestamps = true;

    protected $fillable = [
        'Sucursal',
        'Id_user_c',
        'Id_user_m',
    ];
}
 