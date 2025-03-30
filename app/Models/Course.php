<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'year_level_id',
        'department_id',
    ];

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

    public function facultyAssignments()
    {
        return $this->hasMany(FacultyCourse::class);
    }
public function faculty()
{
    return $this->belongsToMany(User::class, 'faculty_courses', 'course_id', 'faculty_id')
        ->withPivot('evaluation_period_id', 'assigned_at')
        ->withTimestamps();
}

public function department()
{
    return $this->belongsTo(Departments::class, 'department_id');
}
}

