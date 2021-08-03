<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CampoTempCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_tempCalidad';
    protected $primaryKey = 'Id_tempCalidad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Temperatura'
    ];
}
