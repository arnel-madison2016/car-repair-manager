<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    protected $fillable = [
        'manufacturer_id',
        'name',
    ];

    public function manufacturer() {

        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }
}
