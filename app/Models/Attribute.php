<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    
    protected $fillable = ['key', 'values', 'slug', 'translate']; 
    protected $casts = [
        'values' => 'array', 
        'translate' => 'array',
    ];
    protected static function booted()
    {
        static::saving(function ($attribute) {
            // $attribute->values = collect($attribute->values)
            //     // ->map(fn($item) => is_array($item) ? ($item['value'] ?? null) : $item)
            //     ->filter()
            //     ->unique()
            //     ->values()
            //     ->toArray();
        });
    }
    public static function getAllAttributes() 
    {
        $attributes = self::all();

        return collect($attributes->map(function ($attribute) {
            return [
                'key' => $attribute->key,
                'values' => array_column($attribute->values, 'value')
            ];
        }));
    }

}