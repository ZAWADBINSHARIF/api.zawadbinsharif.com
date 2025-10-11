<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'location',
        'twitter',
        'linkedin',
        'github',
        'facebook',
        'instagram',
        'youtube'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
