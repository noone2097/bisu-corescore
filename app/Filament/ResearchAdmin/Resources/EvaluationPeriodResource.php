<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\EvaluationPeriodResource\Pages;
use App\Models\EvaluationPeriod;
use App\Models\Students;
use App\Models\User;
use App\Notifications\EvaluationPeriodActivated;
use App\Services\EvaluationNotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluationPeriodResource extends Resource
{
    protected static ?string $model = EvaluationPeriod::class;

    protected static ?string $navigationLabel = 'Evaluation Periods';

    protected static ?string $modelLabel = 'Evaluation Period';

    protected static ?string $navigationGroup = 'Evaluation Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Evaluation Period Details')
                    ->description('Manage evaluation period information')
                    ->schema([
                        Forms\Components\TextInput::make('academic_year')
                            ->label('Academic Year')
                            ->required()
                            ->placeholder('2024-2025')
                            ->helperText('Format: YYYY-YYYY')
                            ->regex('/^\d{4}-\d{4}$/')
                            ->validationAttribute('academic year'),

                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->relationship('semester', 'name')
                            ->required(),

                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('F d, Y')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && $get('end_date') && $state === $get('end_date')) {
                                    $set('start_date_error', 'Start date cannot be the same as end date.');
                                    return;
                                }

                                if ($state && $get('end_date')) {
                                    $overlap = EvaluationPeriod::hasDateOverlap(
                                        $state,
                                        $get('end_date'),
                                        request()->route('record')
                                    );
                                    
                                    if ($overlap['exists']) {
                                        $set('start_date_error', $overlap['message']);
                                        return;
                                    }
                                }

                                $set('start_date_error', null);
                            })
                            ->validationMessages([
                                'required' => 'Please select a start date',
                            ])
                            ->extraAttributes(fn (Forms\Get $get) => [
                                'error' => $get('start_date_error'),
                            ]),

                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('F d, Y')
                            ->afterOrEqual('start_date')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && $get('start_date')) {
                                    if ($state === $get('start_date')) {
                                        $set('end_date_error', 'End date cannot be the same as start date.');
                                        return;
                                    }

                                    $overlap = EvaluationPeriod::hasDateOverlap(
                                        $get('start_date'),
                                        $state,
                                        request()->route('record')
                                    );
                                    
                                    if ($overlap['exists']) {
                                        $set('end_date_error', $overlap['message']);
                                        return;
                                    }
                                }

                                $set('end_date_error', null);
                            })
                            ->validationMessages([
                                'required' => 'Please select an end date',
                                'after_or_equal' => 'End date must be after or equal to start date',
                            ])
                            ->extraAttributes(fn (Forms\Get $get) => [
                                'error' => $get('end_date_error'),
                            ]),

                        Forms\Components\Select::make('status')
                            ->options(function (?EvaluationPeriod $record) {
                                return EvaluationPeriod::getAvailableStatuses($record?->id);
                            })
                            ->default('draft')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state === 'active') {
                                    $active = EvaluationPeriod::where('status', 'active')
                                        ->where('id', '!=', request()->route('record'))
                                        ->first();

                                    if ($active) {
                                        $set('status_error', "Another evaluation period is already active: {$active->name}");
                                        return;
                                    }
                                }
                                $set('status_error', null);
                            })
                            ->extraAttributes(fn (Forms\Get $get) => [
                                'error' => $get('status_error'),
                            ]),

                        Forms\Components\Hidden::make('start_date_error'),
                        Forms\Components\Hidden::make('end_date_error'),
                        Forms\Components\Hidden::make('status_error'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->date('F d, Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_date')
                    ->date('F d, Y')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'active',
                        'primary' => 'completed',
                        'danger' => 'archived',
                    ]),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester_id')
                    ->label('Semester')
                    ->relationship('semester', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(EvaluationPeriod::STATUSES),
            ])
            ->actions([
                // Add Toggle Status action
                Tables\Actions\Action::make('toggleStatus')
                    ->label(fn (EvaluationPeriod $record): string => $record->status === 'active' ? 'Archive' : 'Activate')
                    ->icon(fn (EvaluationPeriod $record): string => $record->status === 'active' ? 'heroicon-o-archive-box' : 'heroicon-o-play')
                    ->color(fn (EvaluationPeriod $record): string => $record->status === 'active' ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (EvaluationPeriod $record): string => $record->status === 'active' ? 'Archive Evaluation Period' : 'Activate Evaluation Period')
                    ->modalDescription(fn (EvaluationPeriod $record): string =>
                        $record->status === 'active'
                            ? 'Are you sure you want to archive this evaluation period?'
                            : 'Are you sure you want to activate this evaluation period?'
                    )
                    ->visible(function (EvaluationPeriod $record): bool {
                        // If record is active, show the toggle (to archive)
                        if ($record->status === 'active') {
                            return true;
                        }
                        
                        // If record is draft, only show if no other active period exists
                        if ($record->status === 'draft') {
                            return !EvaluationPeriod::where('status', 'active')
                                ->where('id', '!=', $record->id)
                                ->exists();
                        }
                        
                        return false;
                    })
                    ->action(function (EvaluationPeriod $record): void {
                        $oldStatus = $record->status;
                        
                        if ($oldStatus === 'active') {
                            $record->status = 'draft';
                            $record->save();
                            
                            Notification::make()
                                ->success()
                                ->title("Status changed to draft")
                                ->send();
                        } else if ($oldStatus === 'draft') {
                            $activeExists = EvaluationPeriod::where('status', 'active')
                                ->where('id', '!=', $record->id)
                                ->exists();
                                
                            if ($activeExists) {
                                Notification::make()
                                    ->warning()
                                    ->title('Unable to activate')
                                    ->body('Another evaluation period is already active. Please archive or set it to draft first.')
                                    ->send();
                                return;
                            }
                            
                            DB::beginTransaction();
                            try {
                                Log::info('Starting status change transaction', [
                                    'record_id' => $record->id,
                                    'old_status' => $oldStatus,
                                    'new_status' => 'active'
                                ]);

                                $record->status = 'active';
                                $record->save();
                                
                                // Call the notifyStatusChange method to send database notifications
                                $record->notifyStatusChange();
                                
                                DB::commit();

                                Log::info('Status change completed successfully', [
                                    'record_id' => $record->id,
                                    'status' => $record->status
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title("Status changed to active")
                                    ->body("Notifications have been sent to all active users")
                                    ->send();

                            } catch (\Exception $e) {
                                DB::rollback();
                                Log::error('Failed to process status change', [
                                    'record_id' => $record->id,
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);

                                Notification::make()
                                    ->danger()
                                    ->title('Error')
                                    ->body('Failed to update status: ' . $e->getMessage())
                                    ->send();
                            }
                        }
                    }),

                // Add Send Notifications action
                Tables\Actions\Action::make('sendNotifications')
                    ->label('Send Notifications')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Send Notifications')
                    ->modalDescription('This will send email notifications to all active faculty, department roles, and students about this evaluation period.')
                    ->modalSubmitActionLabel('Yes, Send Notifications')
                    ->visible(fn (EvaluationPeriod $record) => in_array(strtolower($record->status), ['active', 'completed']))
                    ->action(function (EvaluationPeriod $record) {
                        try {
                            // Use notification service to send emails
                            $service = new EvaluationNotificationService();
                            
                            if ($record->status === 'active') {
                                $service->sendActivationNotifications($record);
                                $type = 'activation';
                            } else if ($record->status === 'archived') {
                                $service->sendCompletionNotifications($record);
                                $type = 'completion';
                            } else {
                                // For other statuses, use activation template
                                $service->sendActivationNotifications($record);
                                $type = 'information';
                            }
                            
                            // Get count of recipients for reporting
                            $studentCount = Students::where('is_active', true)->whereNotNull('email')->count();
                            $facultyCount = User::where('role', 'faculty')->where('is_active', true)->whereNotNull('email')->count();
                            $departmentCount = User::where('role', 'department')->where('is_active', true)->whereNotNull('email')->count();
                            $totalCount = $studentCount + $facultyCount + $departmentCount;
                            
                            // Log success
                            Log::info("Manual notifications sent for evaluation period", [
                                'evaluation_period_id' => $record->id,
                                'type' => $type,
                                'student_count' => $studentCount,
                                'faculty_count' => $facultyCount,
                                'department_count' => $departmentCount
                            ]);
                            
                            // Show success notification
                            Notification::make()
                                ->title('Notifications Sent')
                                ->body("Email notifications have been sent to {$totalCount} recipients ({$studentCount} students, {$facultyCount} faculty, {$departmentCount} department users).")
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            // Log error
                            Log::error('Failed to send manual notifications', [
                                'evaluation_period_id' => $record->id,
                                'error' => $e->getMessage()
                            ]);
                            
                            // Show error notification
                            Notification::make()
                                ->title('Error Sending Notifications')
                                ->body('An error occurred while sending notifications: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluationPeriods::route('/'),
            'create' => Pages\CreateEvaluationPeriod::route('/create'),
            'edit' => Pages\EditEvaluationPeriod::route('/{record}/edit'),
        ];
    }
}