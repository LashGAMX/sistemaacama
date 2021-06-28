<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaLab extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'areas_lab';
    protected $primaryKey = 'Id_area';
    public $timestamps = true;

    protected $fillable = [
        'Area',
        'Descripcion'
    ];
}
