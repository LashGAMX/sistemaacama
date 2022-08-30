<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromedioCot extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'promedioCot';
    protected $primaryKey = 'Id_promedioCot';
    public $timestamps = true;

    protected $fillable = [ 
        'Promedio',
    ];
}
