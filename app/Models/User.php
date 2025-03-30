<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements HasAvatar, FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function facultyCourses()
    {
        return $this->hasMany(FacultyCourse::class, 'faculty_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'gender',
        'email',
        'password',
        'role',
        'is_active',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class)
            ->withoutGlobalScopes();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default_pfp.svg');
    }

    /**
     * Scope query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFilamentName(): string
    {
        return $this->name ?? 'User';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();
        
        if ($panelId === 'calape') {
            return true;
        }

        return match($panelId) {
            'office-admin' => $this->is_active && $this->role === 'office-admin',
            'research-admin' => $this->is_active && $this->role === 'research-admin',
            'department' => $this->is_active && $this->role === 'department',
            'faculty' => $this->is_active && $this->role === 'faculty',
            'office' => $this->is_active && $this->role === 'office',
            default => false,
        };
    }

    /**
     * Get the feedback records associated with the office user.
     */
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'office_id');
    }
}
