<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model {

    protected $fillable = [
        'name',
        'email',
        'adresse',
        'phone',
        'bank_account',
        'url_site',
        'url_logo',
    ];
}
