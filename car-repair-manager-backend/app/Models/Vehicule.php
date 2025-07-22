<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model {

    protected $fillable = [
        'customer_id',
        'car_model_id',
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

    public function car_model() {

        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function customer() {

        // a vehicle belongs to a special customer
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function appointments() {

        return $this->hasMany(Appointment::class);
    }

    public function getDisplayNameAttribute() {

        return "{$this->car_model->brand->name} {$this->car_model->name} ({$this->license_plate})";
    }
}
