<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name_en','name_cz','name_ru', 'image', 'slug']; 

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products'); 
    }
    protected static function booted()
    {
        //Dark icon
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
        //Light icon
        static::deleting(function ($category) {
            if ($category->image_light) {
                Storage::disk('public')->delete($category->image_light);
            }
        });
        static::updating(function ($category) {
            if ($category->isDirty('image_light') && $category->getOriginal('image_light')) {
                Storage::disk('public')->delete($category->getOriginal('image_light'));
            }
        });
    }
}