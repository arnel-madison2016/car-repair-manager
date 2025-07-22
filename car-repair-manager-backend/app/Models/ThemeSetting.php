<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model {

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'dark_mode',
        'font_family',
        'logo_path',
        'favicon_path',
        'custom_css',
    ];

    protected $casts = [
        'dark_mode' => 'boolean',
        'custom_css' => 'array',
    ];

    public static function active() {
        
        return self::where('is_active', true)->first();
    }

}
