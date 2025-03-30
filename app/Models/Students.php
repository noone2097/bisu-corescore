<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;
use App\Models\Department;
use App\Models\YearLevel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Students extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'avatar',
        'signature',
        'studentID',
        'name',
        'gender',
        'email',
        'password',
        'is_active',
        'department_id',
        'program',
        'year_level_id',
        'email_verified_at',
        'student_type',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'students';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

    public function facultyEvaluations()
    {
        return $this->hasMany(FacultyEvaluation::class, 'student_id');
    }

    public function departmentFacultyCourses()
    {
        return FacultyCourse::join('users', 'faculty_courses.faculty_id', '=', 'users.id')
            ->where('users.department_id', $this->department_id)
            ->where('users.is_active', true)
            ->whereHas('course', function($query) {
                $query->where('year_level_id', $this->year_level);
            })
            ->whereHas('semester', function($query) {
                $query->where('status', 'active');
            })
            ->select('faculty_courses.*')
            ->with(['faculty', 'course', 'semester']);
    }

    public function getEvaluationDetailsAttribute()
    {
        $totalFaculty = $this->departmentFacultyCourses()->count();

        $evaluatedFaculty = $this->facultyEvaluations()
            ->whereHas('courseDetails', function ($query) {
                $query->whereHas('semester', function ($q) {
                    $q->where('status', 'active');
                });
            })
            ->count();

        return [
            'totalFaculty' => $totalFaculty,
            'evaluatedFaculty' => $evaluatedFaculty,
            'remainingFaculty' => $totalFaculty - $evaluatedFaculty,
            'progress' => $totalFaculty > 0 ? ($evaluatedFaculty / $totalFaculty) * 100 : 0,
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default_pfp.svg');
    }

    public function getSocialiteRedirectUrl(): string
    {
        return route('filament.students.pages.dashboard');
    }

    public function getSocialiteGuard(): string
    {
        return 'students';
    }

    public function getEmailForPasswordReset(): string
    {
        if (!str_ends_with($this->email, '@bisu.edu.ph')) {
            return $this->email . '@bisu.edu.ph';
        }
        return $this->email;
    }

}
