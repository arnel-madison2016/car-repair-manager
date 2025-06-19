<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {

    protected $fillable = [
        'customer_id',
        'vehicule_id',
        'selected_date',
        'selected_time',
        'notes',
        'status',
    ];

    public function customer() {

        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function vehicule() {

        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }
}
