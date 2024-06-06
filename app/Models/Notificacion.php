<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificacions';
  
    public $timestamps = true;
    protected $primaryKey = 'Id_notificacion'; 
    public $incrementing = true; 


    protected $fillable = 
    [
        'Mensaje',
        'Id_user',
        'Leido',
    ];


}
