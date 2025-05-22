<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
           'initiator_id',
           'target_id',
    ];
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }


    public function messages(){
        return $this->hasMany(Message::class);
    }
}
