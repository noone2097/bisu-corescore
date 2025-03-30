<?php

namespace App\Filament\Department\Resources;

use App\Filament\Department\Resources\FacultyCourseResource\Pages;
use App\Models\User;
use App\Models\Course;
use App\Models\YearLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FacultyCourseResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Course Assignments';

    protected static ?string $modelLabel = 'Course Assignment';

    protected static ?string $pluralModelLabel = 'Course Assignments';

    protected static ?string $navigationGroup = 'Course Assignments Management';

    protected static ?int $navigationSort = 3;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with(['facultyCourses' => function($query) {
                $query->with(['course.yearLevel', 'evaluationPeriod'])
                    ->whereHas('evaluationPeriod', function($q) {
                        $q->where('status', 'active');
                    });
            }, 'department']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ImageColumn::make('avatar')
                            ->label('Avatar')
                            ->disk('public')
                            ->visibility('public')
                            ->circular()
                            ->size(80)
                            ->defaultImageUrl(asset('images/default_pfp.svg')),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('name')
                                ->label('Faculty Name')
                                ->searchable()
                                ->weight('bold')
                                ->size('lg')
                                ->extraAttributes(['class' => 'dark:text-white']),
                            Tables\Columns\TextColumn::make('email')
                                ->label('Email')
                                ->color('gray')
                                ->size('sm')
                                ->extraAttributes(['class' => 'dark:text-gray-400']),
                            Tables\Columns\TextColumn::make('department.name')
                                ->label('Department')
                                ->color('gray')
                                ->size('sm')
                                ->extraAttributes(['class' => 'dark:text-gray-400']),
                        ])->space(1),
                    ])->from('left'),
                    Tables\Columns\TextColumn::make('facultyCourses')
                        ->label('Assigned Courses')
                        ->formatStateUsing(function ($record) {
                            if (!$record->facultyCourses->count()) {
                                return 'No courses assigned';
                            }
            
                            // Group by year level
                            $byYearLevel = $record->facultyCourses->groupBy('course.year_level_id');
                            
                            $output = [];
                            foreach ($byYearLevel->sortKeys() as $yearLevelId => $courses) {
                                $yearLevel = YearLevel::find($yearLevelId);
                                $yearName = $yearLevel->name ?? '';
                                
                                $bgColor = match ($yearLevelId) {
                                    1 => 'style="background-color: #10b981; color: white;"',
                                    2 => 'style="background-color: #3b82f6; color: white;"',
                                    3 => 'style="background-color: #f59e0b; color: white;"',
                                    default => 'style="background-color: #8b5cf6; color: white;"',
                                };
                                
                                $output[] = "<div class='inline-flex items-center justify-center min-w-[24px] rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap mt-3 mb-2' {$bgColor}>{$yearName}</div>";
                                
                                foreach ($courses->sortBy('course.code') as $facultyCourse) {
                                    $course = $facultyCourse->course;
                                    $evaluationPeriod = $facultyCourse->evaluationPeriod;
                                    $output[] = "
                                        <div class='ml-3 py-0.5 text-xs flex items-center justify-between group'>
                                            <div>
                                                <span class='text-gray-400 dark:text-gray-500'>•</span>
                                                <span class='font-medium text-gray-700 dark:text-gray-200'>{$course->code}</span>
                                                <span class='text-gray-400 dark:text-gray-500 mx-1'>-</span>
                                                <span class='font-medium text-gray-700 dark:text-gray-200'>{$course->name}</span>
                                                <span class='text-gray-400 dark:text-gray-500 mx-1'>({$evaluationPeriod->academic_year})</span>
                                            </div>
                                            <button
                                                type='button'
                                                class='ml-2 text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity'
                                                wire:click='removeAssignment({$record->id}, {$facultyCourse->id})'
                                                title='Remove course'
                                            >
                                                <svg class='w-4 h-4 inline-block' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                                                </svg>
                                            </button>
                                        </div>
                                    ";
                                }
                            }
                            
                            return implode("", $output);
                        })
                        ->html()
                        ->alignStart(),
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit Assignments')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (User $record): string => static::getUrl('edit', ['record' => $record]))
                    ->color('primary')
                    ->button()
                    ->size('sm')
                    ->extraAttributes(['class' => 'text-xs']),
                Tables\Actions\Action::make('unassign_all')
                    ->label('Remove All')
                    ->icon('heroicon-o-trash')
                    ->labeledFrom('sm')
                    ->size('sm')
                    ->extraAttributes(['class' => 'text-xs'])
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove All Courses')
                    ->modalDescription(fn (User $record) => "Are you sure you want to remove all courses from {$record->name}?")
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->action(function (User $record) {
                        // This will now use soft deletes
                        $record->facultyCourses()->each(function ($course) {
                            $course->delete();
                        });
                    })
                    ->visible(fn (User $record) => $record->facultyCourses()->count() > 0),
            ])
            ->recordClasses('!grid-cols-1 bg-white dark:bg-gray-900 shadow-sm rounded-lg p-6 pb-8 ring-1 ring-gray-950/5 dark:ring-gray-800 space-y-4');
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
            'index' => Pages\ListFacultyCourses::route('/'),
            'edit' => Pages\EditFacultyCourse::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'faculty')
            ->where('department_id', auth()->user()->department_id)
            ->where('is_active', true);
    }
}
