<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\Students;
use App\Models\User;
use App\Models\Department;
use App\Models\Departments;
use App\Notifications\EvaluationPeriodActivatedEmail;
use App\Notifications\EvaluationPeriodCompletedEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EvaluationNotificationService
{
    /**
     * Send notification emails when evaluation period becomes active
     * 
     * @param EvaluationPeriod $evaluationPeriod
     * @return void
     */
    public function sendActivationNotifications(EvaluationPeriod $evaluationPeriod): void
    {
        try {
            // Send to students
            $this->notifyStudents($evaluationPeriod, 'active');
            
            // Send to faculty
            $this->notifyFaculty($evaluationPeriod, 'active');
            
            // Send to departments
            $this->notifyDepartments($evaluationPeriod, 'active');
            
            Log::info('Evaluation period activation notifications sent successfully', [
                'evaluation_period_id' => $evaluationPeriod->id,
                'academic_year' => $evaluationPeriod->academic_year,
                'type' => $evaluationPeriod->type
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send evaluation period activation notifications', [
                'evaluation_period_id' => $evaluationPeriod->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification emails when evaluation period is completed
     * 
     * @param EvaluationPeriod $evaluationPeriod
     * @return void
     */
    public function sendCompletionNotifications(EvaluationPeriod $evaluationPeriod): void
    {
        try {
            // Send to students
            $this->notifyStudents($evaluationPeriod, 'completed');
            
            // Send to faculty
            $this->notifyFaculty($evaluationPeriod, 'completed');
            
            // Send to departments
            $this->notifyDepartments($evaluationPeriod, 'completed');
            
            Log::info('Evaluation period completion notifications sent successfully', [
                'evaluation_period_id' => $evaluationPeriod->id,
                'academic_year' => $evaluationPeriod->academic_year,
                'type' => $evaluationPeriod->type
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send evaluation period completion notifications', [
                'evaluation_period_id' => $evaluationPeriod->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notify all active students about the evaluation period status
     * 
     * @param EvaluationPeriod $evaluationPeriod
     * @param string $status
     * @return void
     */
    protected function notifyStudents(EvaluationPeriod $evaluationPeriod, string $status): void
    {
        // Get only active students with valid emails
        $students = Students::where('is_active', true)
            ->whereNotNull('email')
            ->get();
        
        $sentCount = 0;
        $totalCount = $students->count();
        
        foreach ($students as $student) {
            try {
                if ($status === 'active') {
                    $student->notify(new EvaluationPeriodActivatedEmail($evaluationPeriod, 'Student'));
                } else {
                    $student->notify(new EvaluationPeriodCompletedEmail($evaluationPeriod, 'Student'));
                }
                $sentCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send notification to student', [
                    'student_id' => $student->id,
                    'email' => $student->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info("Sent notification emails to {$sentCount} out of {$totalCount} active students");
    }

    /**
     * Notify all active faculty members about the evaluation period status
     * 
     * @param EvaluationPeriod $evaluationPeriod
     * @param string $status
     * @return void
     */
    protected function notifyFaculty(EvaluationPeriod $evaluationPeriod, string $status): void
    {
        // Get only active faculty members with valid emails
        $faculty = User::where('role', 'faculty')
            ->where('is_active', true)
            ->whereNotNull('email')
            ->get();
        
        $sentCount = 0;
        $totalCount = $faculty->count();
        
        foreach ($faculty as $member) {
            try {
                if ($status === 'active') {
                    $member->notify(new EvaluationPeriodActivatedEmail($evaluationPeriod, 'Faculty'));
                } else {
                    $member->notify(new EvaluationPeriodCompletedEmail($evaluationPeriod, 'Faculty'));
                }
                $sentCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send notification to faculty member', [
                    'faculty_id' => $member->id,
                    'email' => $member->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info("Sent notification emails to {$sentCount} out of {$totalCount} active faculty members");
    }

    /**
     * Notify department users about the evaluation period status
     * 
     * @param EvaluationPeriod $evaluationPeriod
     * @param string $status
     * @return void
     */
    protected function notifyDepartments(EvaluationPeriod $evaluationPeriod, string $status): void
    {
        // Only send to users with role 'department' who are active
        $departmentUsers = User::where('role', 'department')
            ->where('is_active', true)
            ->whereNotNull('email')
            ->get();
        
        $sentCount = 0;
        $totalCount = $departmentUsers->count();
        
        // Send to department users
        foreach ($departmentUsers as $user) {
            try {
                if ($status === 'active') {
                    $user->notify(new EvaluationPeriodActivatedEmail($evaluationPeriod, 'Department'));
                } else {
                    $user->notify(new EvaluationPeriodCompletedEmail($evaluationPeriod, 'Department'));
                }
                $sentCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send notification to department user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info("Sent notification emails to {$sentCount} out of {$totalCount} department users");
    }
}
