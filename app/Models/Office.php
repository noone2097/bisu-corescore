<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class Office extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable, SoftDeletes;

    public function getFilamentName(): string
    {
        return $this->office_name ?? 'Office User';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'office' && $this->status === 'active';
    }

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
