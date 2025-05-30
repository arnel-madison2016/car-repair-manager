<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    use HasFactory;
   
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'adresse',
        'postal_code',
        'country',
        'city',
        'profession',
        'url_photo',
        'company_name'
    ];

    public function user() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicules() {

        return $this->hasMany(Vehicule::class);
    }

}
