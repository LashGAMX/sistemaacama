<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoMuestreo extends Model
{
    use HasFactory;
    protected $table = 'costo_muestreo';
    protected $primaryKey = 'Id_costo';
    public $timestamps = true;

    protected $fillable = [

    ];

}
