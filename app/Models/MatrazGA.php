<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatrazGA extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'matraz_GA'; 
    protected $primaryKey = 'Id_matraz';
    public $timestamps = true;

    protected $fillable = [
        'Num_serie',
        'Peso',
        'Min',
        'Max',
        'Estado'
        
    ];
}
