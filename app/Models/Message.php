<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
         'chat_id',
         'sender_id',
         'message',
         'seen',
    ];
    public function chat(){
        return $this->belongsTo(Chat::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'sender_id');
    }
}
