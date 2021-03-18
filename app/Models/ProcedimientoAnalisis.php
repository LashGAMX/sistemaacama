<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedimientoAnalisis extends Model
{
    use HasFactory;
    protected $table = 'procedimiento_analisis';
    protected $primaryKey = 'Id_procedimiento';
    public $timestamps = true;

    protected $fillable = [
        'Procedimiento',
    ];
}
