<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Pengguna extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'peran'
    ];

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
