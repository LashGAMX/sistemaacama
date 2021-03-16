<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAnalisis extends Model
{
    use HasFactory;
    protected $table = 'area_analisis';
    protected $primaryKey = 'Id_area_analisis';
    public $timestamps = true;

    protected $fillable = [
        'Area_analisis',
        'Status'
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
