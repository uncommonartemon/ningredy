<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['styles', 'settings'];
    protected $casts = [
        'styles' => 'array',
        'settings' => 'array',
    ];
}
