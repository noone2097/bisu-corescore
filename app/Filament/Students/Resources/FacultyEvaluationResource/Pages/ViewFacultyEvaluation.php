<?php

namespace App\Filament\Students\Resources\FacultyEvaluationResource\Pages;

use App\Filament\Students\Resources\FacultyEvaluationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;

class ViewFacultyEvaluation extends ViewRecord
{
    protected static string $resource = FacultyEvaluationResource::class;

    public function getRecord(): Model
    {
        $record = parent::getRecord();
        $record->load([
            'facultyCourse.faculty:id,name,avatar',
            'facultyCourse.evaluationPeriod.semester'
        ]);
        return $record;
    }
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make(2)
                ->schema([
                    Grid::make(2)
                        ->columnSpan(2)
                        ->extraAttributes(['class' => 'gap-2'])
                        ->schema([
                            Group::make([])
                                ->extraAttributes(['class' => 'space-y-4 text-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm flex flex-col items-center justify-center h-[320px]'])
                                ->schema([
                                    ImageEntry::make('facultyCourse.faculty.avatar')
                                        ->label('')
                                        ->visibility('public')
                                        ->disk('public')
                                        ->circular()
                                        ->size(80)
                                        ->defaultImageUrl(asset('images/default_pfp.svg'))
                                        ->alignment('center'),
                                    TextEntry::make('facultyCourse.faculty.name')
                                        ->label('')
                                        ->weight('bold')
                                        ->size('lg')
                                        ->alignment('center'),
                                    TextEntry::make('facultyCourse.course.code')
                                        ->label('Course')
                                        ->formatStateUsing(fn ($state, $record) => "{$state} - {$record->facultyCourse->course->name}")
                                        ->color('gray')
                                        ->size('xs')
                                        ->alignment('center'),
                                    TextEntry::make('facultyCourse.evaluationPeriod.semester.name')
                                        ->label('Semester')
                                        ->size('xs')
                                        ->alignment('center'),
                                    TextEntry::make('facultyCourse.evaluationPeriod.start_date')
                                        ->label('Evaluation Period')
                                        ->formatStateUsing(fn ($state, $record) => 
                                            $record->facultyCourse->evaluationPeriod->start_date->format('M d, Y') . ' - ' . 
                                            $record->facultyCourse->evaluationPeriod->end_date->format('M d, Y')
                                        )
                                        ->color('gray')
                                        ->size('xs')
                                        ->alignment('center'),
                                    TextEntry::make('created_at')
                                        ->label('')
                                        ->formatStateUsing(fn ($state) => 'Evaluated on ' . $state->format('M d, Y'))
                                        ->color('gray')
                                        ->size('xs')
                                        ->alignment('center'),
                                ])->columnSpan(1),
                                
                            Group::make([])
                                ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm h-[320px] flex flex-col items-center justify-center'])
                                ->schema([
                                    TextEntry::make('chart')
                                        ->label('')
                                        ->view('filament.resources.faculty-evaluation-resource.pages.overall-ratings')
                                        ->viewData(['record' => $this->record])
                                        ->extraAttributes(['class' => 'flex-1 w-full']),
                                    Grid::make(4)
                                        ->schema([
                                            TextEntry::make('commitment_average')
                                                ->label('Commitment')
                                                ->formatStateUsing(fn ($state) => new \Illuminate\Support\HtmlString(
                                                    str_repeat('★', round($state)) . str_repeat('☆', 5 - round($state)) .
                                                    "<div class='text-[10px] mt-1'>" . number_format($state, 2) . "</div>"
                                                ))
                                                ->color(fn ($state) => match(true) {
                                                    $state >= 5 => 'success',
                                                    $state >= 4 => 'info',
                                                    $state >= 3 => 'warning',
                                                    default => 'danger',
                                                })
                                                ->size('xs')
                                                ->html()
                                                ->alignment('center'),
                                            TextEntry::make('knowledge_average')
                                                ->label('Knowledge')
                                                ->formatStateUsing(fn ($state) => new \Illuminate\Support\HtmlString(
                                                    str_repeat('★', round($state)) . str_repeat('☆', 5 - round($state)) .
                                                    "<div class='text-[10px] mt-1'>" . number_format($state, 2) . "</div>"
                                                ))
                                                ->color(fn ($state) => match(true) {
                                                    $state >= 5 => 'success',
                                                    $state >= 4 => 'info',
                                                    $state >= 3 => 'warning',
                                                    default => 'danger',
                                                })
                                                ->size('xs')
                                                ->html()
                                                ->alignment('center'),
                                            TextEntry::make('teaching_average')
                                                ->label('Teaching')
                                                ->formatStateUsing(fn ($state) => new \Illuminate\Support\HtmlString(
                                                    str_repeat('★', round($state)) . str_repeat('☆', 5 - round($state)) .
                                                    "<div class='text-[10px] mt-1'>" . number_format($state, 2) . "</div>"
                                                ))
                                                ->color(fn ($state) => match(true) {
                                                    $state >= 5 => 'success',
                                                    $state >= 4 => 'info',
                                                    $state >= 3 => 'warning',
                                                    default => 'danger',
                                                })
                                                ->size('xs')
                                                ->html()
                                                ->alignment('center'),
                                            TextEntry::make('management_average')
                                                ->label('Management')
                                                ->formatStateUsing(fn ($state) => new \Illuminate\Support\HtmlString(
                                                    str_repeat('★', round($state)) . str_repeat('☆', 5 - round($state)) .
                                                    "<div class='text-[10px] mt-1'>" . number_format($state, 2) . "</div>"
                                                ))
                                                ->color(fn ($state) => match(true) {
                                                    $state >= 5 => 'success',
                                                    $state >= 4 => 'info',
                                                    $state >= 3 => 'warning',
                                                    default => 'danger',
                                                })
                                                ->size('xs')
                                                ->html()
                                                ->alignment('center'),
                                        ])
                                        ->extraAttributes(['class' => 'mt-2 gap-1']),
                                ])
                                ->columnSpan(1),
                        ]),

                    Tabs::make('Evaluation')
                        ->extraAttributes(['class' => 'flex flex-col items-center'])
                        ->tabs([
                            Tabs\Tab::make('A. Commitment')
                                ->schema([
                                    $this->buildRatingSection(
                                        'commitment_ratings',
                                        FacultyEvaluationResource::$commitmentCriteria
                                    ),
                                ]),

                            Tabs\Tab::make('B. Knowledge of Subject')
                                ->schema([
                                    $this->buildRatingSection(
                                        'knowledge_ratings',
                                        FacultyEvaluationResource::$knowledgeCriteria
                                    ),
                                ]),

                            Tabs\Tab::make('C. Teaching for Independent Learning')
                                ->schema([
                                    $this->buildRatingSection(
                                        'teaching_ratings',
                                        FacultyEvaluationResource::$teachingCriteria
                                    ),
                                ]),

                            Tabs\Tab::make('D. Management of Learning')
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            ...$this->buildRatingSection(
                                                'management_ratings',
                                                FacultyEvaluationResource::$managementCriteria
                                            )->getChildComponents(),
                                        ])
                                ]),

                            Tabs\Tab::make('Comments')
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            TextEntry::make('comments')
                                                ->label('Additional Comments')
                                                ->markdown()
                                                ->columnSpanFull(),
                                            ImageEntry::make('signature')
                                                ->label('Student Signature')
                                                ->disk('public')
                                                ->visibility('public')
                                                ->columnSpanFull()
                                                ->extraImgAttributes(['class' => 'bg-white']),
                                        ])
                                ]),
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    protected function buildRatingSection(string $field, array $criteria): Section
    {
        return Section::make()
            ->schema([
                TextEntry::make('scale')
                    ->label('')
                    ->html('
                        <div class="mb-4 text-sm">
                            <div class="font-medium mb-2 text-gray-600 dark:text-gray-300">Rating Scale:</div>
                            <div class="space-y-1 text-gray-500 dark:text-gray-400">
                                <div>5 ★★★★★ - Outstanding</div>
                                <div>4 ★★★★☆ - Very Satisfactory</div>
                                <div>3 ★★★☆☆ - Satisfactory</div>
                                <div>2 ★★☆☆☆ - Fair</div>
                                <div>1 ★☆☆☆☆ - Poor</div>
                            </div>
                        </div>
                    '),
                ...
                collect($criteria)
                    ->map(fn ($criterion, $index) =>
                        TextEntry::make(match($field) {
                            'commitment_ratings' => 'a' . ($index + 1) . '_' . static::getFieldSuffix('commitment', $index),
                            'knowledge_ratings' => 'b' . ($index + 1) . '_' . static::getFieldSuffix('knowledge', $index),
                            'teaching_ratings' => 'c' . ($index + 1) . '_' . static::getFieldSuffix('teaching', $index),
                            'management_ratings' => 'd' . ($index + 1) . '_' . static::getFieldSuffix('management', $index),
                        })
                            ->label('')
                            ->html()
                            ->formatStateUsing(function ($state) use ($criterion) {
                                if (!$state) {
                                    return '<span class="text-gray-400">Not rated</span>';
                                }

                                $starColors = [
                                    5 => '#22c55e',
                                    4 => '#3b82f6',
                                    3 => '#eab308',
                                    2 => '#f97316',
                                    1 => '#ef4444',
                                ];

                                $isDark = strpos($_SERVER['HTTP_USER_AGENT'] ?? '', 'dark') !== false;
                                $starColor = $isDark ? $starColors[$state] : $starColors[$state];
                                
                                $filledStar = '<span style="color: ' . $starColor . '">★</span>';
                                $emptyStar = '<span style="color: #9ca3af">☆</span>';
                                $stars = str_repeat($filledStar, $state) . str_repeat($emptyStar, 5 - $state);

                                return "
                                    <div class='my-2'>
                                        <div class='flex justify-between gap-4 items-start'>
                                            <div class='flex-1'>
                                                <div class='text-sm text-gray-600 dark:text-gray-300'>{$criterion}</div>
                                            </div>
                                            <div class='flex items-center gap-1 shrink-0'>
                                                <span class='text-lg leading-none'>{$stars}</span>
                                                <span class='text-sm text-gray-500 dark:text-gray-400'>{$state}</span>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            })
                    )
                    ->toArray()
            ])
            ->columns(1);
    }

    protected static function getFieldSuffix(string $prefix, int $index): string
    {
        $suffixMap = [
            'commitment' => [
                0 => 'demonstrates_sensitivity',
                1 => 'integrates_learning_objectives',
                2 => 'makes_self_available',
                3 => 'comes_to_class_prepared',
                4 => 'keeps_accurate_records',
            ],
            'knowledge' => [
                0 => 'demonstrates_mastery',
                1 => 'draws_information',
                2 => 'integrates_subject',
                3 => 'explains_relevance',
                4 => 'demonstrates_up_to_date',
            ],
            'teaching' => [
                0 => 'creates_teaching_strategies',
                1 => 'enhances_self_esteem',
                2 => 'allows_student_creation',
                3 => 'allows_independent_thinking',
                4 => 'encourages_extra_learning',
            ],
            'management' => [
                0 => 'creates_opportunities',
                1 => 'assumes_various_roles',
                2 => 'designs_learning',
                3 => 'structures_learning',
                4 => 'uses_instructional_materials',
            ],
        ];

        return $suffixMap[$prefix][$index];
    }
}
