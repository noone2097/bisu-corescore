<?php

namespace App\Filament\ResearchAdmin\Resources\EvaluationPeriodResource\Pages;

use App\Filament\ResearchAdmin\Resources\EvaluationPeriodResource;
use App\Models\Department;
use App\Models\EvaluationPeriod;
use App\Models\Students;
use App\Models\User;
use App\Notifications\EvaluationPeriodActivated;
use App\Traits\HasBackUrl;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Halt;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\LazyCollection;
use Illuminate\Bus\Batch;
use Illuminate\Validation\ValidationException;

class EditEvaluationPeriod extends EditRecord
{
    use HasBackUrl;

    protected static string $resource = EvaluationPeriodResource::class;

    // Add eager loading for relationships
    public function getRecord(): Model
    {
        $record = parent::getRecord();
        
        if ($record instanceof EvaluationPeriod) {
            return $record->load(['semester']);
        }
        
        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    private function getCacheKey(string $type): string
    {
        return match($type) {
            'validation' => "evaluation_period_validation_{$this->record->id}",
            'recipients' => 'notification_recipients',
            'conflict' => "conflicting_period_{$this->record->id}",
            default => throw new \InvalidArgumentException("Invalid cache key type: {$type}")
        };
    }

    protected function beforeSave(): void
    {
        // Use cached validation with eager loading
        $validationResult = Cache::remember(
            $this->getCacheKey('validation'),
            now()->addSeconds(30),
            fn () => $this->validateEvaluationPeriod()
        );

        if ($validationResult !== null) {
            Notification::make()
                ->danger()
                ->title($validationResult['error'])
                ->body($validationResult['message'])
                ->send();

            $this->halt();
        }
    }

    protected function afterSave(): void
    {
        // Clear all related cache keys
        collect(['validation', 'recipients', 'conflict'])
            ->each(fn ($type) => Cache::forget($this->getCacheKey($type)));
    }

    protected function processActiveStatus(): void
    {
        DB::transaction(function () {
            // Update other active periods efficiently
            EvaluationPeriod::query()
                ->where('id', '!=', $this->record->id)
                ->where('status', 'active')
                ->update(['status' => 'completed']);

            // Get recipients using efficient queries with chunking
            $recipientIds = Cache::remember($this->getCacheKey('recipients'), now()->addMinutes(5), function () {
                return [
                    'students' => Students::query()
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
                        ->select('id')
                        ->where('role', 'faculty')
                        ->orderBy('id')
                        ->pluck('id')
                        ->toArray()
                ];
            });

            // Process notifications in optimized batches
            $jobs = collect(['students', 'departments', 'faculty'])
                ->flatMap(function ($type) use ($recipientIds) {
                    return collect(array_chunk($recipientIds[$type], 100))
                        ->map(function ($chunk) use ($type) {
                            return function () use ($type, $chunk) {
                                $model = $this->getModelForType($type);
                                $model::query()
                                    ->select(['id', 'email'])
                                    ->whereIn('id', $chunk)
                                    ->orderBy('id')
                                    ->chunk(25, function ($recipients) {
                                        $recipients->each(fn ($recipient) => 
                                            $recipient->notify(new EvaluationPeriodActivated($this->record))
                                        );
                                    });
                            };
                        });
                })
                ->all();

            // Execute jobs in a batch for better performance
            if (!empty($jobs)) {
                Bus::batch($jobs)
                    ->allowFailures()
                    ->dispatch();

                // Clear the recipients cache after successful update
                Cache::forget($this->getCacheKey('recipients'));
            }

            Notification::make()
                ->success()
                ->title('Status Updated')
                ->body("The evaluation period has been set to active. Notifications will be sent to all users.")
                ->send();
        });
    }

    protected function validateEvaluationPeriod(): ?array
    {
        return DB::transaction(function () {
            // Use index hints and efficient queries
            $duplicateExists = EvaluationPeriod::query()
                ->select('id')
                ->where('academic_year', $this->data['academic_year'])
                ->where('semester_id', $this->data['semester_id'])
                ->where('id', '!=', $this->record->id)
                ->exists();

            if ($duplicateExists) {
                return [
                    'error' => 'Validation Error',
                    'message' => "An evaluation period already exists for Academic Year {$this->data['academic_year']} and selected semester."
                ];
            }

            // Efficient date overlap check with index usage
            $conflictingPeriod = Cache::remember(
                $this->getCacheKey('conflict'),
                now()->addSeconds(30),
                fn () => EvaluationPeriod::query()
                    ->select(['id', 'academic_year', 'status', 'start_date', 'end_date'])
                    ->where('id', '!=', $this->record->id)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->where('start_date', '<=', $this->data['end_date'])
                              ->where('end_date', '>=', $this->data['start_date']);
                        })->orWhere('status', 'active');
                    })
                    ->orderBy('id')
                    ->first()
            );

            if ($conflictingPeriod) {
                if ($conflictingPeriod->status === 'active' && $this->data['status'] === 'active') {
                    return [
                        'error' => 'Validation Error',
                        'message' => "Another evaluation period is already active for Academic Year {$conflictingPeriod->academic_year}"
                    ];
                }

                if ($conflictingPeriod->start_date <= $this->data['end_date'] && 
                    $conflictingPeriod->end_date >= $this->data['start_date']) {
                    return [
                        'error' => 'Validation Error',
                        'message' => "Date range overlaps with existing period for Academic Year {$conflictingPeriod->academic_year}"
                    ];
                }
            }

            return null;
        });
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('beforeSave');

            // Get what fields were changed
            $changes = $this->record->getChanges();
            
            // Remove timestamps from changes
            unset($changes['updated_at']);
            
            // Show notification to research admin about status change
            if (isset($changes['status'])) {
                $status = ucfirst($this->record->status);
                Notification::make()
                    ->success()
                    ->title('Status Changed')
                    ->body("The evaluation period has been set to {$status}")
                    ->send();
            }
            
            // Send notifications if there were actual changes
            if (!empty($changes)) {
                try {
                    $notificationsSent = $this->record->notifyUpdate($changes);
                    
                    // Notification removed to prevent duplicates
                } catch (\Exception $e) {
                    Log::error('Failed to send update notifications', [
                        'evaluation_period_id' => $this->record->id,
                        'error' => $e->getMessage()
                    ]);
                    
                    if ($shouldSendSavedNotification) {
                        Notification::make()
                            ->warning()
                            ->title('Update Successful')
                            ->body('The evaluation period was updated but there was an error sending notifications: ' . $e->getMessage())
                            ->send();
                    }
                }
            } else if ($shouldSendSavedNotification) {
                Notification::make()
                    ->success()
                    ->title('Changes Saved')
                    ->body('The evaluation period has been updated successfully.')
                    ->send();
            }

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        } catch (ValidationException $exception) {
            $this->onValidationError($exception);

            return;
        }

        if (! $shouldRedirect) {
            return;
        }

        $this->redirect($this->getRedirectUrl());
    }

    protected function afterFormFilled(): void
    {
        $changedFields = $this->record->getDirty();
        
        if (!empty($changedFields) && isset($changedFields['status']) && $this->record->status === 'active') {
            $this->processActiveStatus();
        } else if (!empty($changedFields) && isset($changedFields['status'])) {
            Notification::make()
                ->success()
                ->title('Status Updated')
                ->body("The evaluation period status has been updated to {$this->record->status}.")
                ->send();
        } else if (!empty($changedFields)) {
            Notification::make()
                ->success()
                ->title('Changes Saved')
                ->body('The evaluation period has been updated successfully.')
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Override to handle validation errors
    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->danger()
            ->title('Error')
            ->body('Please check the form for errors.')
            ->send();

        parent::onValidationError($exception);
    }

    // Additional validation before form submission
    protected function beforeValidate(): void
    {
        if (strtotime($this->data['start_date']) > strtotime($this->data['end_date'])) {
            Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body('Start date must be before end date.')
                ->send();

            $this->halt();
        }
    }
}