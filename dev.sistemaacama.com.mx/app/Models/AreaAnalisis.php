<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaAnalisis extends Model
{
    use HasFactory,SoftDeletes; 
    protected $table = 'area_analisis';
    protected $primaryKey = 'Id_area_analisis';
    public $timestamps = true;

    protected $fillable = [
        'Area_analisis',
        'Id_user_c',
        'Id_user_m',
    ];
}
