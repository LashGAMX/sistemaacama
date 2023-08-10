<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrisolesGA extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'crisol_ga';  
    protected $primaryKey = 'Id_crisol'; 
    public $timestamps = true;

    protected $fillable = [
        'Num_serie',
        'Peso',
        'Min',
        'Max',
        'Estado'  
    ];  
}
 