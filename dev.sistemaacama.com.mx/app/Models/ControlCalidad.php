<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'control_calidad';
    protected $primaryKey = 'Id_control';
    public $timestamps = true;

    protected $fillable = [
        'Control',
        'Descripcion',
    ];
}
