<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\OfficeMetricsResource\Pages;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OfficeMetricsExport;
use Maatwebsite\Excel\Facades\Excel;

class OfficeMetricsResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'office');
    }

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Office Metrics';

    protected static ?string $navigationGroup = 'Ratings';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->headerActions([
                Action::make('print_report')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn () => route('office-metrics.print'), true)
                    ->openUrlInNewTab(),
                
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-s-table-cells')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalDescription('All exported feedback will be moved to the bin. You can restore them later from the Feedback Bin.')
                    ->modalSubmitActionLabel('Export and Move to Bin')
                    ->action(function () {
                        $export = new OfficeMetricsExport(auth()->user());
                        return Excel::download($export, 'office-metrics-report.xlsx');
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Export completed')
                            ->body('Feedback has been moved to bin.')
                    )
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Office')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('overall_rating')
                    ->label('Overall Rating')
                    ->state(function (User $record): string {
                        $avgRating = $record->feedback()
                            ->selectRaw('AVG((responsiveness + reliability + access_facilities + communication + costs + integrity + assurance + outcome) / 8) as avg_rating')
                            ->first()
                            ->avg_rating ?? 0;
                        return number_format($avgRating, 2) . '/5.00';
                    }),
                TextColumn::make('cc_rating')
                    ->label('CC Rating')
                    ->state(function (User $record): string {
                        $ccRating = $record->feedback()
                            ->selectRaw('AVG((cc1 + cc2 + cc3) / 3) as cc_rating')
                            ->first()
                            ->cc_rating ?? 0;
                        return number_format($ccRating, 2) . '/5.00';
                    }),
                TextColumn::make('feedback_count')
                    ->counts('feedback')
                    ->label('Feedbacks')
                    ->sortable(),
                TextColumn::make('responsiveness')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('responsiveness') ?? 0, 2) . '/5.00'),
                TextColumn::make('reliability')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('reliability') ?? 0, 2) . '/5.00'),
                TextColumn::make('access')
                    ->label('Access & Facilities')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('access_facilities') ?? 0, 2) . '/5.00'),
                TextColumn::make('communication')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('communication') ?? 0, 2) . '/5.00'),
                TextColumn::make('costs')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('costs') ?? 0, 2) . '/5.00'),
                TextColumn::make('integrity')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('integrity') ?? 0, 2) . '/5.00'),
                TextColumn::make('assurance')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('assurance') ?? 0, 2) . '/5.00'),
                TextColumn::make('outcome')
                    ->state(fn (User $record): string =>
                        number_format($record->feedback()->avg('outcome') ?? 0, 2) . '/5.00')
            ])
            ->defaultSort('name', 'asc')
            ->paginated([10, 25, 50])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfficeMetrics::route('/')
        ];
    }
}