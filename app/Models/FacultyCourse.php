<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'course_id',
        'evaluation_period_id',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime'
    ];

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id', 'id')
            ->withoutGlobalScopes()
            ->withTrashed();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class);
    }

    public function facultyEvaluations()
    {
        return $this->hasMany(FacultyEvaluation::class);
    }
}
