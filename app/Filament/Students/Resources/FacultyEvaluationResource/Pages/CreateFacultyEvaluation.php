<?php

namespace App\Filament\Students\Resources\FacultyEvaluationResource\Pages;

use App\Filament\Students\Resources\FacultyEvaluationResource;
use App\Models\FacultyEvaluation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreateFacultyEvaluation extends CreateRecord
{
    protected static string $resource = FacultyEvaluationResource::class;

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = Auth::guard('students')->id();
        
        // Log the form data before create
        Log::debug('Form Data Before Create', [  
            'data' => $data,
            'faculty_course_id' => $data['faculty_course_id'] ?? null
        ]);
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Add debugging
        Log::debug('Faculty Evaluation Submission', [
            'faculty_course_id' => $data['faculty_course_id'] ?? null,
            'data' => $data
        ]);
        
        // Basic validation for faculty_course_id
        if (empty($data['faculty_course_id'])) {
            // Add error to form
            $this->addError('faculty_course_id', 'Please select a faculty member to evaluate.');
            $this->halt();
        }
        
        // Check if student has already evaluated this faculty member
        $facultyCourse = \App\Models\FacultyCourse::find($data['faculty_course_id']);
        
        if (!$facultyCourse) {
            // Try to find it among soft-deleted records
            $facultyCourse = \App\Models\FacultyCourse::withTrashed()->find($data['faculty_course_id']);
        }
        
        // If still not found, show error
        if (!$facultyCourse) {
            $this->addError('faculty_course_id', 'The selected faculty is no longer available. Please select another faculty member.');
            $this->halt();
        }
        
        $facultyId = $facultyCourse->faculty_id;
        
        // Check for existing evaluations
        $existing = static::getModel()::query()
            ->where('student_id', Auth::guard('students')->id())
            ->join('faculty_courses', 'faculty_evaluations.faculty_course_id', '=', 'faculty_courses.id')
            ->where('faculty_courses.faculty_id', $facultyId)
            ->exists();

        if ($existing) {
            $this->addError(
                'faculty_course_id',
                'You have already evaluated this faculty member. You can only evaluate a faculty member once, regardless of how many courses they teach.'
            );
            $this->halt();
        }

        return static::getModel()::create($data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Faculty evaluation submitted successfully';
    }
}
