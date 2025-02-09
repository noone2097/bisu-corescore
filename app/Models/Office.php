<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Office extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'office_avatar',
        'office_name',
        'email',
        'password',
        'status',
        'password_reset_token',
        'password_reset_expires_at',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'password_reset_token',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];
}
