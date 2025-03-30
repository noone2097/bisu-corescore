<?php

namespace App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource\Pages;

use App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewFacultyEvaluation extends ViewRecord
{
    protected static string $resource = FacultyEvaluationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Infolists\Components\Section::make('Faculty Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('courseDetails.faculty.name')
                            ->label('Faculty Name'),
                        Infolists\Components\TextEntry::make('courseDetails.course.department.name')
                            ->label('Department'),
                        Infolists\Components\TextEntry::make('courseDetails.semester.type')
                            ->label('Semester'),
                        Infolists\Components\TextEntry::make('courseDetails.semester.academic_year')
                            ->label('Academic Year'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Evaluation Scores')
                    ->schema([
                        Infolists\Components\TextEntry::make('commitment_average')
                            ->label('A. Commitment')
                            ->numeric(2),
                        Infolists\Components\TextEntry::make('knowledge_average')
                            ->label('B. Knowledge of Subject')
                            ->numeric(2),
                        Infolists\Components\TextEntry::make('teaching_average')
                            ->label('C. Teaching for Independent Learning')
                            ->numeric(2),
                        Infolists\Components\TextEntry::make('management_average')
                            ->label('D. Management of Learning')
                            ->numeric(2),
                        Infolists\Components\TextEntry::make('overall_average')
                            ->label('Overall Rating')
                            ->numeric(2)
                            ->weight('bold'),
                    ])
                    ->columns(5),
                
                Infolists\Components\Section::make('Detailed Ratings')
                    ->schema([
                        // A. Commitment
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\Section::make('A. Commitment')
                                    ->description('Commitment to teaching and learning')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('a1_demonstrates_sensitivity')
                                            ->label('1. Demonstrates sensitivity to students')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('a2_integrates_learning_objectives')
                                            ->label('2. Integrates learning objectives')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('a3_makes_self_available')
                                            ->label('3. Makes self available')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('a4_comes_to_class_prepared')
                                            ->label('4. Comes to class prepared')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('a5_keeps_accurate_records')
                                            ->label('5. Keeps accurate records')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('commitment_average')
                                            ->label('Average')
                                            ->numeric(2)
                                            ->weight('bold')
                                    ])
                                    ->columns(6)
                            ])
                            ->columnSpan('full'),

                        // B. Knowledge
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\Section::make('B. Knowledge of Subject')
                                    ->description('Knowledge and mastery of the subject matter')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('b1_demonstrates_mastery')
                                            ->label('1. Demonstrates mastery')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('b2_draws_information')
                                            ->label('2. Draws information')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('b3_integrates_subject')
                                            ->label('3. Integrates subject')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('b4_explains_relevance')
                                            ->label('4. Explains relevance')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('b5_demonstrates_up_to_date')
                                            ->label('5. Demonstrates up-to-date knowledge')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('knowledge_average')
                                            ->label('Average')
                                            ->numeric(2)
                                            ->weight('bold')
                                    ])
                                    ->columns(6)
                            ])
                            ->columnSpan('full'),

                        // C. Teaching
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\Section::make('C. Teaching for Independent Learning')
                                    ->description('Teaching for independent learning')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('c1_creates_teaching_strategies')
                                            ->label('1. Creates teaching strategies')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('c2_enhances_self_esteem')
                                            ->label('2. Enhances self-esteem')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('c3_allows_student_creation')
                                            ->label('3. Allows student creation')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('c4_allows_independent_thinking')
                                            ->label('4. Allows independent thinking')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('c5_encourages_extra_learning')
                                            ->label('5. Encourages extra learning')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('teaching_average')
                                            ->label('Average')
                                            ->numeric(2)
                                            ->weight('bold')
                                    ])
                                    ->columns(6)
                            ])
                            ->columnSpan('full'),

                        // D. Management
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\Section::make('D. Management of Learning')
                                    ->description('Management of learning')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('d1_creates_opportunities')
                                            ->label('1. Creates opportunities')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('d2_assumes_various_roles')
                                            ->label('2. Assumes various roles')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('d3_designs_learning')
                                            ->label('3. Designs learning')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('d4_structures_learning')
                                            ->label('4. Structures learning')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('d5_uses_instructional_materials')
                                            ->label('5. Uses instructional materials')
                                            ->numeric(2),
                                        Infolists\Components\TextEntry::make('management_average')
                                            ->label('Average')
                                            ->numeric(2)
                                            ->weight('bold')
                                    ])
                                    ->columns(6)
                            ])
                            ->columnSpan('full')
                    ]),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('comments')
                            ->label('Comments')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Evaluated By'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime('M j, Y : g:i A'),
                    ])
                    ->columns(2),
            ]);
    }
}