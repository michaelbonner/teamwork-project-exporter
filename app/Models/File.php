<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    protected $guarded = [];

    public function getFilesystemPathAttribute()
    {
        if ($this->data['category-name']) {
            return 'public/' . $this->data['category-name'] . '/' . $this->data['display-name'];
        } else {
            return 'public/' . $this->data['display-name'];
        }
    }

    public function getPublicLinkAttribute()
    {
        return Storage::url($this->filesystemPath);
    }
}
