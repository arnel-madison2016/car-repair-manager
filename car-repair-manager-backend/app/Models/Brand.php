<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    protected $fillable = [
        'manufacturer_id',
        'name',
        'url_picture',
    ];

    public function manufacturer() {

        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function car_models() {

        return $this->hasMany(CarModel::class);
    }
}
