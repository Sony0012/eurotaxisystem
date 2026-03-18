<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenance_records';

    protected $fillable = [
        'unit_id',
        'type',
        'description',
        'maintenance_date',
        'cost',
        'mechanic_id',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'cost' => 'float',
        'maintenance_date' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
