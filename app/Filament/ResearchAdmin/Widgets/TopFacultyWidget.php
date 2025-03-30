<?php

namespace App\Filament\ResearchAdmin\Widgets;

use App\Models\Departments;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;

class TopFacultyWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected static ?string $heading = 'Top Faculty by Department';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'faculty')
                    ->whereHas('facultyCourses.facultyEvaluations')
                    ->select([
                        'users.*',
                        'departments.name as department_name',
                        DB::raw('(
                            SELECT AVG(
                                (
                                    (a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records) / 5 +
                                    (b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date) / 5 +
                                    (c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning) / 5 +
                                    (d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 5
                                ) / 4
                            )
                            FROM faculty_evaluations 
                            JOIN faculty_courses ON faculty_evaluations.faculty_course_id = faculty_courses.id 
                            WHERE faculty_courses.faculty_id = users.id
                        ) as avg_rating')
                    ])
                    ->join('departments', 'users.department_id', '=', 'departments.id')
                    ->orderByDesc('avg_rating')
                    ->groupBy('users.id', 'departments.name')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Faculty Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('avg_rating')
                    ->label('Average Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->sortable()
                    ->alignCenter(),
            ])
            ->defaultSort('avg_rating', 'desc')
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5);
    }
}