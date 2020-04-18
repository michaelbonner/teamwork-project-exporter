<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
