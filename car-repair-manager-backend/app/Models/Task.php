<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $fillable = [
        'repair_sheet_id',
        'service_id',
        'user_id',
        'remarks',
        'date_completed',
        'estimated_time',
        'status',
    ];

    public function repairsheet() {

        return $this->belongsTo(RepairSheet::class, 'repair_sheet_id');
    }

    public function service() {

        return $this->belongsTo(Service::class, 'service_id');
    }

    public function mechanic() {

        $this->belongsTo(User::class, 'user_id');
    }
}
