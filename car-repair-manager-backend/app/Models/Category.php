<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'category_services';

    protected $fillable = [
        'name',
        'cout_forfaitaire',
        'details',
    ];

    public function services() {

        return $this->hasMany(Service::class);        
    }
}
