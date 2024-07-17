<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'message',
        'file',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($message) {
            // Busca a los miembros del  grupo y auntenta su contador 
            GroupUser::where('group_id', $message->group_id)
                ->where('user_id', '!=', $message->user_id)
                ->increment('count_message');
        });
    }


    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
