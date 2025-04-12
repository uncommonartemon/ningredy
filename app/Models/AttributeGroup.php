<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AttributeGroup extends Model
{
    protected $casts = [
        'translate' => 'array'
    ];
    protected $fillable = [
        'title',
        'icon' ,
        'translate',
    ];
    protected static function booted()
    {
        //Dark icon
        static::deleting(function ($attributeGroup) {
            if($attributeGroup->icon) Storage::disk('public')->delete($attributeGroup->icon);
        });
        static::updating(function ($attributeGroup) {
            if($attributeGroup->isDirty('icon') && $attributeGroup->getOriginal('icon')) Storage::disk('public')->delete($attributeGroup->getOriginal('icon'));
        });   
        //Light icon
        static::deleting(function ($attributeGroup) {
            if($attributeGroup->icon_light) Storage::disk('public')->delete($attributeGroup->icon_light);
        });
        static::updating(function ($attributeGroup) {
            if($attributeGroup->isDirty('icon_light') && $attributeGroup->getOriginal('icon_light')) Storage::disk('public')->delete($attributeGroup->getOriginal('icon_kught'));
        });  
    }
}
