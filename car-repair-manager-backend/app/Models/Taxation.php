<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxation extends Model {
    protected $table = 'tarifs';
    protected $fillable = [
        'service_id',
        'cout_prestation',
        'reduction',
    ];
    public function service() {

        return $this->belongsTo(Service::class, 'service_id');
    }
}
