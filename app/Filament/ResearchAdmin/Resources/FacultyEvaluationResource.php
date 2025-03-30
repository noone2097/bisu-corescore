<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource\Pages;
use App\Models\FacultyEvaluation;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Departments;
use Filament\Forms\Components\Select;
use App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource\Exports\DepartmentEvaluationsExport;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Filament\ResearchAdmin\Resources\FacultyEvaluationBinResource;

class FacultyEvaluationResource extends Resource
{
    protected static ?string $model = FacultyEvaluation::class;
    
    protected static ?string $navigationLabel = 'Faculty Evaluation';

    protected static ?string $modelLabel = 'Faculty Evaluation';

    protected static ?string $navigationGroup = 'Evaluation Management';

    protected static ?int $navigationSort = 2;

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
                Tables\Columns\TextColumn::make('commitment_average')
                    ->label('Commitment')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('knowledge_average')
                    ->label('Knowledge')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('teaching_average')
                    ->label('Teaching')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('management_average')
                    ->label('Management')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('overall_average')
                    ->label('Overall')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2))
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw('(
                            (
                                (a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records) / 5 +
                                (b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date) / 5 +
                                (c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning) / 5 +
                                (d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 5
                            ) / 4
                        ) ' . $direction);
                    }),
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
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->icon('heroicon-m-eye')
                    ->tooltip('View Details')
                    ->color('gray'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('print-department')
                ->label('Print by Department')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->form([
                    Select::make('department_id')
                        ->label('Department')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(Departments::all()->pluck('name', 'id'))
                ])
                ->requiresConfirmation()
                ->modalHeading('Print Department Report')
                ->modalSubmitActionLabel('Print')
                ->modalIcon('heroicon-o-printer')
                ->action(function (array $data) {
                    $departmentId = $data['department_id'];
                    $url = url("/faculty-evaluations/print/{$departmentId}");
                    return redirect()->to($url);
                }),
                ExportAction::make('export-department')
                    ->label('Export by Department')
                    ->color('primary')
                    ->icon('heroicon-o-building-office')
                    ->form([
                        Select::make('department_id')
                        ->label('Department')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->options(Departments::all()->pluck('name', 'id'))
                        ])
                        ->action(function (array $data) {
                            $department = Departments::findOrFail($data['department_id']);
                            
                            $export = new class($data['department_id']) implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping {
                                public function __construct(private int $departmentId)
                            {
                            }

                            public function query()
                            {
                                return FacultyEvaluation::query()
                                    ->whereHas('facultyCourse.faculty.department', function ($q) {
                                        $q->where('id', $this->departmentId);
                                    })
                                    ->with(['facultyCourse.faculty.department']);
                            }

                            public function map($row): array
                            {
                                return [
                                    $row->facultyCourse->faculty->name,
                                    $row->facultyCourse->faculty->department->name,
                                    $row->commitment_average,
                                    $row->knowledge_average,
                                    $row->teaching_average,
                                    $row->management_average,
                                    $row->overall_average,
                                ];
                            }

                            public function headings(): array
                            {
                                return [
                                    'Faculty Name',
                                    'Department',
                                    'Commitment',
                                    'Knowledge',
                                    'Teaching',
                                    'Management',
                                    'Overall'
                                ];
                            }

                            public function columnFormats(): array
                            {
                                return [
                                    'C' => NumberFormat::FORMAT_NUMBER_00,
                                    'D' => NumberFormat::FORMAT_NUMBER_00,
                                    'E' => NumberFormat::FORMAT_NUMBER_00,
                                    'F' => NumberFormat::FORMAT_NUMBER_00,
                                    'G' => NumberFormat::FORMAT_NUMBER_00,
                                ];
                            }
                        };

                        return Excel::download($export, "Department Evaluations - {$department->name} - " . now()->format('Y-m-d') . '.xlsx');
                    }),
                ExportAction::make('export-all')
                    ->label('Export All')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->requiresConfirmation()
                    ->modalDescription('All exported faculty evaluations will be moved to the bin. You can restore them later from the Faculty Evaluation Bin.')
                    ->modalSubmitActionLabel('Export and Move to Bin')
                    ->action(function () {
                        $export = new class implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting {
                            public function query()
                            {
                                return FacultyEvaluation::query()->with(['facultyCourse.faculty.department']);
                            }

                            public function headings(): array
                            {
                                return [
                                    'Faculty Name',
                                    'Department',
                                    'Commitment',
                                    'Knowledge',
                                    'Teaching',
                                    'Management',
                                    'Overall',
                                ];
                            }

                            public function map($row): array
                            {
                                return [
                                    $row->facultyCourse->faculty->name ?? 'Unknown Faculty',
                                    $row->facultyCourse->faculty->department->name ?? 'No Department',
                                    number_format($row->commitment_average, 2),
                                    number_format($row->knowledge_average, 2),
                                    number_format($row->teaching_average, 2),
                                    number_format($row->management_average, 2),
                                    number_format($row->overall_average, 2),
                                ];
                            }

                            public function columnFormats(): array
                            {
                                return [
                                    'C' => NumberFormat::FORMAT_NUMBER_00,
                                    'D' => NumberFormat::FORMAT_NUMBER_00,
                                    'E' => NumberFormat::FORMAT_NUMBER_00,
                                    'F' => NumberFormat::FORMAT_NUMBER_00,
                                    'G' => NumberFormat::FORMAT_NUMBER_00,
                                ];
                            }
                        };

                        // Move to bin after export
                        $now = now();
                        $userId = auth()->id();
                        
                        // Update records with export info before soft deleting
                        FacultyEvaluation::query()
                            ->update([
                                'exported_by' => $userId,
                                'exported_at' => $now,
                            ]);
                        
                        // Soft delete all records
                        FacultyEvaluation::query()->delete();
                        
                        Notification::make()
                            ->success()
                            ->title('Export completed')
                            ->body('Faculty evaluations have been moved to bin.')
                            ->send();

                        return Excel::download($export, 'Faculty Evaluations - ' . now()->format('Y-m-d') . '.xlsx');
                    }),
                Tables\Actions\Action::make('view_bin')
                    ->label('View Bin')
                    ->icon('heroicon-o-trash')
                    ->color('gray')
                    ->url(fn () => FacultyEvaluationBinResource::getUrl())
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('selected_faculty_evaluations')
                         ->fromTable()
                            ->modifyQueryUsing(fn ($query) => $query->with(['facultyCourse.faculty.department']))
                            ->withColumns([
                                Column::make('facultyCourse.faculty.name')
                                    ->heading('Faculty Name'),
                                Column::make('facultyCourse.faculty.department.name')
                                    ->heading('Department'),
                                Column::make('commitment_average')
                                    ->heading('Commitment')
                                    ->format(NumberFormat::FORMAT_NUMBER_00),
                                Column::make('knowledge_average')
                                    ->heading('Knowledge')
                                    ->format(NumberFormat::FORMAT_NUMBER_00),
                                Column::make('teaching_average')
                                    ->heading('Teaching')
                                    ->format(NumberFormat::FORMAT_NUMBER_00),
                                Column::make('management_average')
                                    ->heading('Management')
                                    ->format(NumberFormat::FORMAT_NUMBER_00),
                                Column::make('overall_average')
                                    ->heading('Overall')
                                    ->format(NumberFormat::FORMAT_NUMBER_00),
                            ])
                            ->withFilename('Selected Faculty Evaluations - ' . now()->format('Y-m-d'))
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacultyEvaluations::route('/'),
            'view' => Pages\ViewFacultyEvaluation::route('/{record}'),
        ];
    }

}