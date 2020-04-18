<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }
}
