<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'role',
        'password',
        'password_hash',
        'is_active',
        'phone',
        'phone_number',
        'address',
        'github_id',
        'github_token',
        'github_refresh_token',
        'last_login',
        'profile_image',
        'otp_code',
        'otp_expires_at',
        'verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];



    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }
}
