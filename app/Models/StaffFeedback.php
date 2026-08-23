<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffFeedback extends Model
{
    use HasFactory;

    protected $table = 'staff_feedbacks';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'rating',
        'rating_label',
        'feedback',
        'page_url',
        'status',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
