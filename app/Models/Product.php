<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'discount', 'images', 
        'attributes', 'category_id', 'brand', 'country', 'used', 'active',
        'slug', 'type', 'discount_lifespan', 'views', 'views_uniq', 
    ];

    protected $casts = [
        'images' => 'array',
        'description' => 'array',
        'title' => 'array',
        'attributes' => 'array',
        'tags' => 'array',
        'info' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class); // Связь с категорией
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withPivot('value'); 
    }

    protected static function booted()
    {
        static::deleted(function ($product) {
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
        static::updating(function ($product) {
            $old_images = $product->getOriginal('images');
            $new_images = $product->images;
            $diff = array_diff($old_images, $new_images);
            foreach ($diff as $image) {
                Storage::disk('public')->delete($image);
            }
        });
    }
 
}