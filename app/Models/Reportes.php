<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reportes extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_muestra',
        'Texto'
    ];
}
