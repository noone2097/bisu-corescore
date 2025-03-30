<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EvaluationPeriod extends Model
{
    use HasFactory;

    public const STATUSES = [
        'Draft' => 'Draft',
        'Active' => 'Active',
        'Completed' => 'Completed',
        'Archived' => 'Archived'
    ];

    protected $fillable = [
        'academic_year',
        'semester_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public static function getAvailableStatuses(?int $recordId = null): array
    {
        $statuses = self::STATUSES;

        // Check if there's an active evaluation period
        $activeExists = self::where('status', 'Active')
            ->when($recordId, fn($query) => $query->where('id', '!=', $recordId))
            ->exists();

        // If there's an active evaluation period, only show Draft status for new/other records
        if ($activeExists) {
            return ['Draft' => 'Draft'];
        }

        return $statuses;
    }

    public function facultyCourses()
    {
        return $this->hasMany(FacultyCourse::class);
    }

    /**
     * Check if there is a date overlap with existing evaluation periods
     *
     * @param string $startDate
     * @param string $endDate
     * @param int|null $excludeId
     * @return array
     */
    /**
     * Check if an evaluation period with given academic year and semester exists
     *
     * @param string $academicYear
     * @param int $semesterId
     * @param int|null $excludeId
     * @return array
     */
    public static function academicYearExists($academicYear, $semesterId, $excludeId = null): array
    {
        $query = self::where('academic_year', $academicYear)
            ->where('semester_id', $semesterId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existing = $query->first();

        if ($existing) {
            $semesterName = $existing->semester->name ?? 'Unknown Semester';
            return [
                'exists' => true,
                'message' => "An evaluation period for academic year {$academicYear} in {$semesterName} already exists."
            ];
        }

        return [
            'exists' => false,
            'message' => null
        ];
    }

    public static function hasDateOverlap($startDate, $endDate, $excludeId = null): array
    {
        $query = self::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $overlapping = $query->first();

        if ($overlapping) {
            return [
                'exists' => true,
                'message' => "Date range overlaps with existing evaluation period: {$overlapping->academic_year} ({$overlapping->start_date->format('M d, Y')} - {$overlapping->end_date->format('M d, Y')})"
            ];
        }

        return [
            'exists' => false,
            'message' => null
        ];
    }

    /**
     * Send notifications about updated fields to relevant users
     *
     * @param array $changes
     * @return bool
     */
    public function notifyUpdate(array $changes): bool
    {
        // If status changed to active, use the existing notification flow
        if (isset($changes['status']) && strtolower($changes['status']) === 'active') {
            $this->notifyStatusChange();
            return true;
        }

        // Otherwise, just log the changes
        \Illuminate\Support\Facades\Log::info('Evaluation period updated', [
            'evaluation_period_id' => $this->id,
            'changes' => $changes
        ]);

        return true;
    }

    /**
     * Send notifications when status changes to active
     */
    public function notifyStatusChange(): void
    {
        // Send email notifications through the service
        $evaluationService = new \App\Services\EvaluationNotificationService();
        $evaluationService->sendActivationNotifications($this);

        $semesterName = $this->semester->name ?? 'Current';

        // Send Filament notifications to faculty
        $faculty = User::query()
            ->where('role', 'faculty')
            ->where('is_active', true)
            ->get();
            
        foreach ($faculty as $member) {
            \Filament\Notifications\Notification::make()
                ->title('Evaluation Period Activated')
                ->icon('heroicon-o-clipboard-document-check')
                ->body("The {$semesterName} evaluation period for {$this->academic_year} is now active. The evaluation will run from {$this->start_date->format('M d, Y')} to {$this->end_date->format('M d, Y')}. You can now track your performance rating.")
                ->warning()
                ->sendToDatabase($member);
        }

        // Send Filament notifications to active students
        $students = \App\Models\Students::query()
            ->where('is_active', true)
            ->get();

        foreach ($students as $student) {
            \Filament\Notifications\Notification::make()
                ->title('Evaluation Period Activated')
                ->icon('heroicon-o-clipboard-document-check')
                ->body("The {$semesterName} evaluation period for {$this->academic_year} is now active. The evaluation will run from {$this->start_date->format('M d, Y')} to {$this->end_date->format('M d, Y')}. You may now start evaluating faculties.")
                ->warning()
                ->sendToDatabase($student);
        }

        // Send Filament notifications to departments
        $departments = User::query()
            ->where('role', 'department')
            ->where('is_active', true)
            ->get();

        foreach ($departments as $department) {
            \Filament\Notifications\Notification::make()
                ->title('Evaluation Period Activated')
                ->icon('heroicon-o-clipboard-document-check')
                ->body("The {$semesterName} evaluation period for {$this->academic_year} is now active. The evaluation will run from {$this->start_date->format('M d, Y')} to {$this->end_date->format('M d, Y')}. You can now start assigning courses to faculty for evaluation.")
                ->warning()
                ->sendToDatabase($department);
        }

        \Illuminate\Support\Facades\Log::info('Notifications sent for evaluation period activation', [
            'evaluation_period_id' => $this->id,
            'faculty_count' => $faculty->count(),
            'student_count' => $students->count(),
            'department_count' => $departments->count()
        ]);
    }
}