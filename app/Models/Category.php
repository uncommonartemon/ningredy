<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'slug']; 

    protected $casts = [
        'name' => 'array', 
    ];

    public function products()
    {
        return $this->hasMany(Product::class); 
    }
    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        });
        static::updating(function ($category) {
            if ($category->isDirty('image') && $category->getOriginal('image')) {
                Storage::disk('public')->delete($category->getOriginal('image'));
            }
        });
        // static::creating(function ($category) {
        //     dd($category->getAttributes());  // Покажет все атрибуты, которые приходят в модель
        // });
    }
}