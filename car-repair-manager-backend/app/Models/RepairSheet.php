<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairSheet extends Model {

    protected $fillable = [
        'vehicule_id',
        'probleme_reporte',
        'date_arrivee',
        'date_sortie',
        'status'
    ];

    protected $withCount = ['tasks'];
    
    public function tasks() {

        return $this->hasMany(Task::class);
    }

    public function vehicule() {

        return $this->belongsTo(Vehicule::class, 'vehicule_id');
    }
}
