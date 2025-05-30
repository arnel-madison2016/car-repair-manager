<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model {

    protected $fillable = [
        'customer_id',
        'brand_id',
        'vehicle_type',
        'license_plate',
        'chassis_number',
        'odometer_reading',
        'year_registration',
        'fuel_type',        
        'gear_box',
        'engine_size',
        'url_pictures',
    ];

    public function brand() {

        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function customer() {

        // a vehicle belongs to a special customer
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
