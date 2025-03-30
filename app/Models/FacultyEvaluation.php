<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'rating_period_start' => 'date',
        'rating_period_end' => 'date',
        'exported_at' => 'datetime',
    ];

    protected $appends = [
        'commitment_average',
        'knowledge_average',
        'teaching_average',
        'management_average',
        'overall_average'
    ];

    protected $with = ['facultyCourse.faculty.department'];

    protected static function booted()
    {
        static::created(function ($evaluation) {
            \Log::debug('New faculty evaluation created', [
                'evaluation_id' => $evaluation->id,
                'faculty_course_id' => $evaluation->faculty_course_id,
                'faculty_id' => $evaluation->facultyCourse?->faculty_id,
                'department_id' => $evaluation->facultyCourse?->faculty?->department_id
            ]);
        });
    }

    public function facultyCourse(): BelongsTo
    {
        return $this->belongsTo(FacultyCourse::class)->withTrashed();
    }

    public function getFacultyAttribute()
    {
        $facultyCourse = $this->facultyCourse;
        $faculty = $facultyCourse?->faculty;

        \Log::debug('FacultyEvaluation getting faculty', [
            'evaluation_id' => $this->id,
            'faculty_course_id' => $facultyCourse?->id,
            'faculty_info' => $faculty ? [
                'id' => $faculty->id,
                'name' => $faculty->name,
                'department_id' => $faculty->department_id,
                'role' => $faculty->role,
                'is_active' => $faculty->is_active,
                'is_trashed' => $faculty->trashed()
            ] : null
        ]);

        return $faculty;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function getCourseDetailsAttribute()
    {
        if (!$this->facultyCourse) {
            \Log::debug('FacultyEvaluation courseDetails: No facultyCourse found', [
                'evaluation_id' => $this->id
            ]);
            return null;
        }

        $course = $this->facultyCourse->course;
        $department = $course ? $course->department : null;
        $faculty = $this->faculty;

        \Log::debug('FacultyEvaluation courseDetails', [
            'evaluation_id' => $this->id,
            'has_course' => !is_null($course),
            'has_department' => !is_null($department),
            'has_faculty' => !is_null($faculty),
            'department_name' => $department?->name,
            'faculty_name' => $faculty?->name
        ]);

        return [
            'faculty' => $faculty,
            'course' => $course,
            'department' => $department,
            'semester' => $this->facultyCourse->semester,
            'assigned_at' => $this->facultyCourse->assigned_at,
            'is_deleted' => $this->facultyCourse->trashed()
        ];
    }

    public function getCommitmentAverageAttribute(): float
    {
        return round(collect([
            $this->a1_demonstrates_sensitivity,
            $this->a2_integrates_learning_objectives,
            $this->a3_makes_self_available,
            $this->a4_comes_to_class_prepared,
            $this->a5_keeps_accurate_records,
        ])->avg(), 2);
    }

    public function getKnowledgeAverageAttribute(): float
    {
        return round(collect([
            $this->b1_demonstrates_mastery,
            $this->b2_draws_information,
            $this->b3_integrates_subject,
            $this->b4_explains_relevance,
            $this->b5_demonstrates_up_to_date,
        ])->avg(), 2);
    }

    public function getTeachingAverageAttribute(): float
    {
        return round(collect([
            $this->c1_creates_teaching_strategies,
            $this->c2_enhances_self_esteem,
            $this->c3_allows_student_creation,
            $this->c4_allows_independent_thinking,
            $this->c5_encourages_extra_learning,
        ])->avg(), 2);
    }

    public function getManagementAverageAttribute(): float
    {
        return round(collect([
            $this->d1_creates_opportunities,
            $this->d2_assumes_various_roles,
            $this->d3_designs_learning,
            $this->d4_structures_learning,
            $this->d5_uses_instructional_materials,
        ])->avg(), 2);
    }

    public function getOverallAverageAttribute(): float
    {
        return round(collect([
            $this->commitment_average,
            $this->knowledge_average,
            $this->teaching_average,
            $this->management_average,
        ])->avg(), 2);
    }

    public function exportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
