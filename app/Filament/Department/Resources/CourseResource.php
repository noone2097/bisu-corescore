<?php

namespace App\Filament\Department\Resources;

use App\Filament\Department\Resources\CourseResource\Pages;
use App\Filament\Department\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use App\Models\Departments;
use App\Models\YearLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $modelLabel = 'Course';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Courses';

    protected static ?string $recordTitleAttribute = 'code';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('department_id', auth()->user()->department_id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Enter the fundamental details of the course')
                    ->icon('heroicon-o-academic-cap')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., CS 101')
                            ->helperText('Enter a unique course code')
                            ->label('Course Code'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Introduction to Fundamentals of Programming')
                            ->helperText('Enter the complete course name')
                            ->label('Course Name'),
                    ]),

                Forms\Components\Section::make('Course Details')
                    ->description('Specify the academic details of the course')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        Forms\Components\Select::make('year_level_id')
                            ->label('Year Level')
                            ->options(YearLevel::all()->pluck('name', 'id'))
                            ->required()
                            ->relationship('yearLevel', 'name')
                            ->helperText('Select the year level this course belongs to')
                            ->preload(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Course Description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                            ])
                            ->placeholder('Enter a detailed description of the course content and objectives')
                            ->helperText('Provide a comprehensive overview of the course')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        // Course Code and Year Level
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('code')
                                ->formatStateUsing(fn ($state) =>
                                    "<div class='font-bold text-lg text-gray-900 dark:text-white'>{$state}</div>"
                                )
                                ->copyable()
                                ->searchable()
                                ->sortable()
                                ->html(),
                            
                            Tables\Columns\TextColumn::make('yearLevel.name')
                                ->formatStateUsing(fn ($state) =>
                                    match(true) {
                                        str_contains($state, 'First') => "<div style='display: inline-block; background-color: #16a34a !important; color: white !important; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;'>{$state}</div>",
                                        str_contains($state, 'Second') => "<div style='display: inline-block; background-color: #2563eb !important; color: white !important; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;'>{$state}</div>",
                                        str_contains($state, 'Third') => "<div style='display: inline-block; background-color: #9333ea !important; color: white !important; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;'>{$state}</div>",
                                        str_contains($state, 'Fourth') => "<div style='display: inline-block; background-color: #ea580c !important; color: white !important; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;'>{$state}</div>",
                                        default => "<div style='display: inline-block; background-color: #4b5563 !important; color: white !important; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;'>{$state}</div>"
                                    }
                                )
                                ->html(),
                        ])->from('left'),

                        // Course Name
                        Tables\Columns\TextColumn::make('name')
                            ->formatStateUsing(fn ($state) =>
                                "<div class='mt-2 text-sm font-medium text-gray-700 dark:text-gray-200'>{$state}</div>"
                            )
                            ->searchable()
                            ->sortable()
                            ->html(),

                        // Description
                        Tables\Columns\TextColumn::make('description')
                            ->formatStateUsing(fn ($state) =>
                                "<div class='mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-3'>{$state}</div>"
                            )
                            ->html()
                            ->wrap(),

                        // Department and Timestamps
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('department.name')
                                ->formatStateUsing(fn ($state) =>
                                    "<div class='mt-3 text-xs text-gray-500 dark:text-gray-400'>{$state}</div>"
                                )
                                ->html(),
                            Tables\Columns\TextColumn::make('updated_at')
                                ->formatStateUsing(fn ($state) =>
                                    "<div class='text-xs text-gray-400 dark:text-gray-500'>Last updated " . $state->diffForHumans() . "</div>"
                                )
                                ->html(),
                        ])->space(1),
                    ])->space(1),
                ])
                ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow duration-200']),
            ])
            ->defaultSort('code', 'asc')
            ->recordClasses('border border-gray-200 dark:border-gray-700')
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Department'),
                Tables\Filters\SelectFilter::make('year_level')
                    ->relationship('yearLevel', 'name')
                    ->label('Filter by Year Level')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->recordUrl(fn(Course $record): string => Pages\EditCourse::getUrl([$record]))
            ->defaultSort('code', 'asc');
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
