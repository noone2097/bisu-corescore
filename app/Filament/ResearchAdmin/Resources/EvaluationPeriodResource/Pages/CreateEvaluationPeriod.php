<?php

namespace App\Filament\ResearchAdmin\Resources\EvaluationPeriodResource\Pages;

use App\Filament\ResearchAdmin\Resources\EvaluationPeriodResource;
use App\Models\EvaluationPeriod;
use App\Models\Department;
use App\Models\Students;
use App\Models\User;
use App\Notifications\EvaluationPeriodActivated;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Traits\HasBackUrl;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CreateEvaluationPeriod extends CreateRecord
{
    use HasBackUrl;
    protected static string $resource = EvaluationPeriodResource::class;

    // Disable all default notifications
    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        \Illuminate\Support\Facades\Log::info('Mutating form data before create', [
            'original_status' => $data['status'] ?? null,
        ]);

        // Convert status to lowercase for consistency
        if (isset($data['status'])) {
            $data['status'] = strtolower($data['status']);
        }
        
        \Illuminate\Support\Facades\Log::info('Form data after mutation', [
            'final_status' => $data['status'] ?? null,
        ]);
        
        return $data;
    }

    protected function beforeCreate(): void
    {
        // Check for duplicate academic year and semester
        $academicYearCheck = EvaluationPeriod::academicYearExists(
            $this->data['academic_year'],
            $this->data['semester_id']
        );

        if ($academicYearCheck['exists']) {
            Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body($academicYearCheck['message'])
                ->send();

            $this->halt();
        }

        // Check for date overlap
        $overlap = EvaluationPeriod::hasDateOverlap(
            $this->data['start_date'],
            $this->data['end_date']
        );

        if ($overlap['exists']) {
            Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body($overlap['message'])
                ->send();

            $this->halt();
        }

        // Check if trying to set as active when another is active, regardless of semester
        if ($this->data['status'] === 'active') {
            $active = EvaluationPeriod::where('status', 'active')->first();
            if ($active) {
                // Update all other active periods to completed
                EvaluationPeriod::where('status', 'active')->update(['status' => 'completed']);
                
                \Illuminate\Support\Facades\Log::info('Updated existing active evaluation periods to completed');
            }
        }

        // Validate that start_date and end_date are not the same
        $startDate = \Carbon\Carbon::parse($this->data['start_date']);
        $endDate = \Carbon\Carbon::parse($this->data['end_date']);

        if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
            Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body('Start date cannot be the same as end date')
                ->send();

            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $isActive = $this->record->status === 'active';

        // Update cache
        // Cache::tags(['evaluation_periods'])->flush();
        
        if ($isActive) {
            $this->record->notifyStatusChange();
        } else {
            // Only show generic success message if not active (since notifyStatusChange shows its own message)
            Notification::make()
                ->success()
                ->title('Evaluation Period Created')
                ->body('The evaluation period has been created successfully.')
                ->send();
        }
    }

    protected function sendNotifications(): void
    {
        DB::transaction(function () {
            // Update all other active periods to completed first
            EvaluationPeriod::where('status', 'active')
                ->where('id', '!=', $this->record->id)
                ->update(['status' => 'completed']);

            // Get recipients using efficient queries
            $recipientIds = [
                'students' => Students::query()
                    ->where('is_active', true)
                    ->select('id')
                    ->orderBy('id')
                    ->pluck('id')
                    ->toArray(),
                'departments' => Department::query()
                    ->select('id')
                    ->orderBy('id')
                    ->pluck('id')
                    ->toArray(),
                'faculty' => User::query()
                    ->where('role', 'faculty')
                    ->where('is_active', true)
                    ->select('id')
                    ->orderBy('id')
                    ->pluck('id')
                    ->toArray()
            ];

            // Send notifications directly without batching
            foreach (['students', 'departments', 'faculty'] as $type) {
                $model = $this->getModelForType($type);
                foreach (array_chunk($recipientIds[$type], 100) as $chunk) {
                    $model::query()
                        ->select(['id'])
                        ->whereIn('id', $chunk)
                        ->orderBy('id')
                        ->chunk(25, function ($recipients) {
                            $recipients->each(fn ($recipient) => 
                                $recipient->notify(new EvaluationPeriodActivated($this->record))
                            );
                        });
                }
            }

            Notification::make()
                ->success()
                ->title('Evaluation Period Created')
                ->body('The evaluation period has been created and set to active. Notifications have been sent to all users.')
                ->send();
        });
    }

    protected function getModelForType(string $type): string
    {
        return match($type) {
            'students' => Students::class,
            'departments' => Department::class,
            'faculty' => User::class,
            default => throw new \InvalidArgumentException("Invalid model type: {$type}")
        };
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}