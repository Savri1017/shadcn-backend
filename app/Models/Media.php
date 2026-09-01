<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $fillable = [
        'collection',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'size',
        'alt_text',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
