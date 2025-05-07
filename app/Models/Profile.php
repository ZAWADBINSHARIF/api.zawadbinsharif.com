<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'full_name',
        'short_title',
        'introduction',
        'about_me',
        'image',
        'resume',
        'worked_technologies'
    ];

    protected $casts = [
        'worked_technologies' => 'array',
    ];


    protected static function booted(): void
    {
        static::updating(function (Profile $model) {
            if ($model->isDirty('image') && $model->getOriginal('image')) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            if ($model->isDirty('resume') && $model->getOriginal('resume')) {
                Storage::disk('public')->delete($model->getOriginal('resume'));
            }
        });

        static::deleting(function (Profile $model) {
            if ($model->getOriginal('image')) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            if ($model->isDirty('resume') && $model->getOriginal('resume')) {
                Storage::disk('public')->delete($model->getOriginal('resume'));
            }
        });
    }
}
