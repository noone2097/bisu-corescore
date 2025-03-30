<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\FacultyEvaluationBinResource\Pages;
use App\Models\FacultyEvaluation;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyEvaluationBinResource extends Resource
{
    protected static ?string $model = FacultyEvaluation::class;
    
    protected static ?string $navigationLabel = 'Faculty Evaluation Bin';

    protected static ?string $modelLabel = 'Faculty Evaluation Bin';

    protected static ?string $navigationGroup = 'Evaluation Management';

    protected static ?int $navigationSort = 3;
    
    // Hide from navigation
    protected static bool $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->onlyTrashed();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('facultyCourse.faculty.name')
                    ->label('Faculty Name')
                    ->placeholder('Unknown Faculty')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('facultyCourse.faculty', function (Builder $q) use ($search) {
                            $q->withTrashed()->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('facultyCourse.faculty.department.name')
                    ->label('Department')
                    ->placeholder('No Department')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('facultyCourse.faculty.department', function (Builder $q) use ($search) {
                            $q->withoutGlobalScopes()->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_average')
                    ->label('Overall Rating')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('exported_at')
                    ->label('Exported At')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Moved to Bin')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('facultyCourse.faculty.department', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Department')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->color('success')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Faculty Evaluation')
                    ->modalDescription('Are you sure you want to restore this faculty evaluation? It will be available again in the main Faculty Evaluation list.')
                    ->modalSubmitActionLabel('Yes, restore evaluation')
                    ->action(function (FacultyEvaluation $record) {
                        $record->restore();
                        Notification::make()
                            ->title('Faculty evaluation restored successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('force_delete')
                    ->label('Delete')
                    ->modalHeading('Delete Faculty Evaluation Forever')
                    ->modalDescription('Are you sure you want to permanently delete this faculty evaluation? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete permanently')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(function (FacultyEvaluation $record) {
                        $record->forceDelete();
                        Notification::make()
                            ->title('Faculty evaluation permanently deleted')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('restore_selected')
                    ->label('Restore Selected')
                    ->color('success')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Selected Faculty Evaluations')
                    ->modalDescription('Are you sure you want to restore the selected faculty evaluations? They will be available again in the main Faculty Evaluation list.')
                    ->modalSubmitActionLabel('Yes, restore selected')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        $count = $records->count();
                        $records->each->restore();
                        Notification::make()
                            ->title("$count faculty evaluations restored successfully")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\BulkAction::make('force_delete_selected')
                    ->label('Delete Forever')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Selected Faculty Evaluations Forever')
                    ->modalDescription('Are you sure you want to permanently delete the selected faculty evaluations? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete permanently')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        $count = $records->count();
                        $records->each->forceDelete();
                        Notification::make()
                            ->title("$count faculty evaluations permanently deleted")
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('No Faculty Evaluations in Bin')
            ->emptyStateDescription('Evaluations moved to bin after export will appear here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacultyEvaluationBin::route('/'),
        ];
    }
}
