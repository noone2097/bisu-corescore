<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\FeedbackBinResource\Pages;
use App\Filament\OfficeAdmin\Resources\FeedbackBinResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class FeedbackBinResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    
    protected static ?string $navigationLabel = 'Feedback Bin';
    
    protected static ?string $navigationGroup = 'Feedback Bin Management';

    protected static ?string $pluralModelLabel = 'Feeback Bin';
    
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->onlyTrashed()
            ->with(['office', 'exportedBy'])
            ->latest('exported_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('office.name')
                    ->label('Office')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_visit')
                    ->label('Visit Date')
                    ->date('M j, Y : g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('averageRating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . '/5.00'),
                Tables\Columns\TextColumn::make('exportedBy.name')
                    ->label('Exported By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('exported_at')
                    ->label('Export Date')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Moved to Bin')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable(),
            ])
            ->defaultSort('exported_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('office')
                    ->relationship('office', 'name'),
                Tables\Filters\Filter::make('export_date')
                    ->form([
                        Forms\Components\DatePicker::make('exported_from'),
                        Forms\Components\DatePicker::make('exported_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['exported_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('exported_at', '>=', $date),
                            )
                            ->when(
                                $data['exported_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('exported_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->action(fn (Feedback $record) => $record->restore())
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('forceDelete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn (Feedback $record) => $record->forceDelete())
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to permanently delete this feedback? This action cannot be undone.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('restore')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->restore())
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('forceDelete')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->forceDelete())
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to permanently delete these feedback records? This action cannot be undone.')
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListFeedbackBins::route('/'),
        ];
    }
}
