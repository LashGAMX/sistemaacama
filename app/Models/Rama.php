<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rama extends Model
{
    use HasFactory;
    protected $table = 'ramas';
    protected $primaryKey = 'Id_rama';
    public $timestamps = true;

    protected $fillable = [
        'Rama',
        'Status',
        'Id_user_c',
        'Id_user_m',
    ];
}
