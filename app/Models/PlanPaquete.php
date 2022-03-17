<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanPaquete extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'plan_paquete';
    protected $primaryKey = 'Id_plan';
    public $timestamps = true;

    protected $fillable = [
        'Id_paquete',
        'Id_area',
        'Id_recipiente',
        'Cantidad'
    ];
}
