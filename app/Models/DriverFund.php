<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverFund extends Model
{
    use SoftDeletes;

    protected $table = 'driver_funds';

    protected $fillable = [
        'driver_id',
        'boundary_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'float',
        'balance_after' => 'float',
        'date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function boundary()
    {
        return $this->belongsTo(Boundary::class, 'boundary_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
