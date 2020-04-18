<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];

    public function messageReplies()
    {
        return $this->hasMany(MessageReply::class);
    }
}
