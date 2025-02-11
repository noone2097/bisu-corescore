<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class DepartmentAccount extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable, SoftDeletes;

    protected $table = 'department_accounts';

    public function getFilamentName(): string
    {
        return $this->department_name ?? 'Department User';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'department' && $this->status === 'active';
    }

    protected $fillable = [
        'department_avatar',
        'department_name',
        'email',
        'password',
        'status',
        'password_reset_token',
        'password_reset_expires_at',
        'email_verified_at',
        'department_entity_id'
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

    /**
     * Get the department entity this account belongs to (e.g., CTE, CTAS)
     */
    public function departmentEntity()
    {
        return $this->belongsTo(DepartmentEntity::class);
    }
}