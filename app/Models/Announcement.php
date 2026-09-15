<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'message',
        'is_active',
        'is_pinned',
        'created_by',
        'start_date',
        'valid_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
        'start_date' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getEffectiveStartDateAttribute()
    {
        return $this->start_date ?? $this->created_at;
    }

    public function getDurationDaysAttribute()
    {
        if (!$this->valid_until) return null;
        $start = $this->effective_start_date ?? now();
        return max(1, (int) round($start->diffInDays($this->valid_until) + 1));
    }
}
