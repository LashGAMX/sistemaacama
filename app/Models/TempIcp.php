<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempIcp extends Model
{
    use HasFactory,SoftDeletes;
        
    protected $table = 'temp_icp';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'Temp',
        'colRes',
    ];
}
