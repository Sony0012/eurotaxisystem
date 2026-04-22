<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverBehavior extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'driver_behavior';
    public $timestamps = false; // Using custom timestamp columns mostly

    protected $fillable = [
        'unit_id', 'driver_id', 'incident_type', 'cause_of_incident', 'severity', 'description', 
        'third_party_name', 'third_party_vehicle', 'own_unit_damage_cost', 
        'third_party_damage_cost', 'is_driver_fault', 'total_charge_to_driver', 
        'total_paid', 'remaining_balance', 'charge_status', 'latitude', 'longitude', 
        'timestamp', 'incident_date', 'video_url', 'incentive_released_at'
    ];

    public function involvedParties()
    {
        return $this->hasMany(IncidentInvolvedParty::class, 'driver_behavior_id');
    }

    public function partsEstimates()
    {
        return $this->hasMany(IncidentPartsEstimate::class, 'driver_behavior_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
