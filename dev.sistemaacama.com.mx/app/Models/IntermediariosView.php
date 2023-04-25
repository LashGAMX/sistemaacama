<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntermediariosView extends Model
{
    use HasFactory;
    protected $table = 'ViewIntermediarios';
    protected $primaryKey = 'Id_intermediario';

}
