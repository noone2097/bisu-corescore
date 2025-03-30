<?php

namespace App\Filament\Department\Resources\FacultyAccountsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\EvaluationPeriod;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'facultyCourses';

    protected static ?string $title = 'Assigned Courses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->label('Course')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'academic_year')
                    ->label('Evaluation Period')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(
                        EvaluationPeriod::all()->mapWithKeys(function ($period) {
                            return [$period->id => "{$period->academic_year} - {$period->type}"];
                        })
                    )
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $course = \App\Models\Course::find($state);
                            if ($course) {
                                $set('year_level', $course->yearLevel->name);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('year_level')
                    ->label('Year Level')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('course.code')
                    ->label('Course Code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.name')
                    ->label('Course Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('semester.academic_year')
                    ->label('Academic Year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester.type')
                    ->label('Semester')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First' => 'primary',
                        'Second' => 'success',
                        'Summer' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('semester.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'completed' => 'info',
                        'archived' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester')
                    ->relationship('semester', 'academic_year')
                    ->searchable()
                    ->preload()
                    ->label('Evaluation Period')
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'archived' => 'Archived',
                    ])
                    ->label('Status')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
