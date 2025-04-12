<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['key', 'values', 'slug', 'translate', 'attribute_group_id']; 
    protected $casts = [
        'values' => 'array', 
        'translate' => 'array',
    ];
    protected static function booted()
    {
        static::saving(function ($attribute) {

        });
    }
    public function attributeGroup() {
        return $this->belongsTo(AttributeGroup::class, 'attribute_group_id'); 
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