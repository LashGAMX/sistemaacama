<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanComplemento extends Model
{
    
        use HasFactory,SoftDeletes;
        protected $table = 'plan_complemento';
        protected $primaryKey = 'Id_plan';
        public $timestamps = true;
    
        protected $fillable = [
            'Id_paquete',
            'Id_complemento',
            'Tipo'
        ];
}
