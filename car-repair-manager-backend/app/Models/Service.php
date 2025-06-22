<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

    protected $fillable = [
        'category_service_id',
        'name',
        'details',
    ];

    public function category() {

        return $this->belongsTo(Category::class, 'category_service_id');
    }

    public function prices() {

       return $this->has(Pricing::class);
    }

    public function taxations() {

        return $this->hasMany(Taxation::class);
    }

    public function tasks() {

        return $this->hasMany(Task::class);
    }
}
