<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
