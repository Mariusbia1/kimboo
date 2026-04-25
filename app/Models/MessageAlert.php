<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageAlert extends Model
{
    protected $fillable = [
        'message_id', 'sender_id', 'receiver_id',
        'alert_type', 'matched_content', 'status',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
