<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];
}
