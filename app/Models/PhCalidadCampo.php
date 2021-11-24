<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhCalidadCampo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ph_calidadCampo';
    protected $primaryKey = 'Id_phCalidad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Ph_calidad'
    ];
}
