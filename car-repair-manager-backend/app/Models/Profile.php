<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'adresse',
        'country',
        'city',
        'postal_code',
        'employment_held',
        'url_picture',
    ];

    public function user() {

        return $this->belongsTo(User::class, 'user_id');
    }
}
