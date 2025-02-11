<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class AdminAccounts extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function getFilamentName(): string
    {
        return $this->admin_name ?? 'Admin User';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'office-admin' => $this->role === 'Office Admin',
            'research-admin' => $this->role === 'Research Admin',
            default => false,
        };
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_name',
        'email',
        'password',
        'admin_avatar',
        'status',
        'role',
        'password_reset_token',
        'password_reset_expires_at',
        'email_verified_at',
        'department_entity_id'
    ];

    /**
     * Get the department entity this admin account belongs to
     */
    public function departmentEntity()
    {
        return $this->belongsTo(DepartmentEntity::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

}
