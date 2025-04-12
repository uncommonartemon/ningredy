<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $casts = [
        'text'=> 'array',
        'settings' => 'array'
    ];
    protected $fillable = [
        'link', 'image', 'order', 'clicks', 'settings', 'text'
    ];
    protected static function booted() {
        static::deleting(function ($slider) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
        });
        static::updating(function ($slider) {
            if ($slider->isDirty('image') && $slider->getOriginal('image')) {
                Storage::disk('public')->delete($slider->getOriginal('image'));
            }
        });
    }
}
