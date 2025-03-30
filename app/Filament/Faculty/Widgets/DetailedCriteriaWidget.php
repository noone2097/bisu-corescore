<?php

namespace App\Filament\Faculty\Widgets;

use App\Models\FacultyEvaluation;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class DetailedCriteriaWidget extends Widget
{
    protected static string $view = 'filament.faculty.widgets.detailed-criteria';
    
    protected static ?int $sort = 3;

    protected static $fieldMap = [
        'a1' => 'a1_demonstrates_sensitivity',
        'a2' => 'a2_integrates_learning_objectives',
        'a3' => 'a3_makes_self_available',
        'a4' => 'a4_comes_to_class_prepared',
        'a5' => 'a5_keeps_accurate_records',
        'b1' => 'b1_demonstrates_mastery',
        'b2' => 'b2_draws_information',
        'b3' => 'b3_integrates_subject',
        'b4' => 'b4_explains_relevance',
        'b5' => 'b5_demonstrates_up_to_date',
        'c1' => 'c1_creates_teaching_strategies',
        'c2' => 'c2_enhances_self_esteem',
        'c3' => 'c3_allows_student_creation',
        'c4' => 'c4_allows_independent_thinking',
        'c5' => 'c5_encourages_extra_learning',
        'd1' => 'd1_creates_opportunities',
        'd2' => 'd2_assumes_various_roles',
        'd3' => 'd3_designs_learning',
        'd4' => 'd4_structures_learning',
        'd5' => 'd5_uses_instructional_materials',
    ];

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getViewData(): array
    {
        $faculty = Auth::user();
        
        $evaluations = FacultyEvaluation::query()
            ->join('faculty_courses as fc', 'faculty_evaluations.faculty_course_id', '=', 'fc.id')
            ->join('evaluation_periods as ep', 'fc.evaluation_period_id', '=', 'ep.id')
            ->where('fc.faculty_id', $faculty->id)
            ->where('ep.status', 'Active')
            ->get();
            
        if ($evaluations->isEmpty()) {
            return [
                'hasData' => false,
            ];
        }

        $criteriaAverages = [];
        foreach (static::$fieldMap as $key => $field) {
            $criteriaAverages[$key] = $evaluations->avg($field);
        }

        $criteriaDetails = [
            'commitment' => [
                'name' => 'Commitment',
                'icon' => 'heroicon-o-heart',
                'metrics' => [
                    'a1' => 'Demonstrates sensitivity to students',
                    'a2' => 'Integrates learning objectives',
                    'a3' => 'Makes self available beyond hours',
                    'a4' => 'Comes to class on time and prepared',
                    'a5' => 'Keeps accurate records'
                ],
                'data' => [
                    'a1' => $this->getMetricData($evaluations, 'a1', $criteriaAverages['a1']),
                    'a2' => $this->getMetricData($evaluations, 'a2', $criteriaAverages['a2']),
                    'a3' => $this->getMetricData($evaluations, 'a3', $criteriaAverages['a3']),
                    'a4' => $this->getMetricData($evaluations, 'a4', $criteriaAverages['a4']),
                    'a5' => $this->getMetricData($evaluations, 'a5', $criteriaAverages['a5'])
                ]
            ],
            'knowledge' => [
                'name' => 'Knowledge of Subject',
                'icon' => 'heroicon-o-book-open',
                'metrics' => [
                    'b1' => 'Subject matter mastery',
                    'b2' => 'Information sharing',
                    'b3' => 'Practical integration',
                    'b4' => 'Relevance explanation',
                    'b5' => 'Current trends awareness'
                ],
                'data' => [
                    'b1' => $this->getMetricData($evaluations, 'b1', $criteriaAverages['b1']),
                    'b2' => $this->getMetricData($evaluations, 'b2', $criteriaAverages['b2']),
                    'b3' => $this->getMetricData($evaluations, 'b3', $criteriaAverages['b3']),
                    'b4' => $this->getMetricData($evaluations, 'b4', $criteriaAverages['b4']),
                    'b5' => $this->getMetricData($evaluations, 'b5', $criteriaAverages['b5'])
                ]
            ],
            'teaching' => [
                'name' => 'Teaching for Independent Learning',
                'icon' => 'heroicon-o-light-bulb',
                'metrics' => [
                    'c1' => 'Teaching strategies',
                    'c2' => 'Student self-esteem',
                    'c3' => 'Student course creation',
                    'c4' => 'Independent thinking',
                    'c5' => 'Beyond-requirement learning'
                ],
                'data' => [
                    'c1' => $this->getMetricData($evaluations, 'c1', $criteriaAverages['c1']),
                    'c2' => $this->getMetricData($evaluations, 'c2', $criteriaAverages['c2']),
                    'c3' => $this->getMetricData($evaluations, 'c3', $criteriaAverages['c3']),
                    'c4' => $this->getMetricData($evaluations, 'c4', $criteriaAverages['c4']),
                    'c5' => $this->getMetricData($evaluations, 'c5', $criteriaAverages['c5'])
                ]
            ],
            'management' => [
                'name' => 'Management of Learning',
                'icon' => 'heroicon-o-cog',
                'metrics' => [
                    'd1' => 'Student participation',
                    'd2' => 'Facilitation roles',
                    'd3' => 'Learning conditions',
                    'd4' => 'Learning context',
                    'd5' => 'Instructional materials'
                ],
                'data' => [
                    'd1' => $this->getMetricData($evaluations, 'd1', $criteriaAverages['d1']),
                    'd2' => $this->getMetricData($evaluations, 'd2', $criteriaAverages['d2']),
                    'd3' => $this->getMetricData($evaluations, 'd3', $criteriaAverages['d3']),
                    'd4' => $this->getMetricData($evaluations, 'd4', $criteriaAverages['d4']),
                    'd5' => $this->getMetricData($evaluations, 'd5', $criteriaAverages['d5'])
                ]
            ]
        ];

        return [
            'hasData' => true,
            'criteriaDetails' => $criteriaDetails,
        ];
    }

    protected function getMetricData($evaluations, $key, $average): array
    {
        $score = round($average, 2);
        return [
            'score' => $score,
            'color' => $this->getScoreColor($score),
            'stars' => str_repeat('★', (int)$score) . str_repeat('☆', 5 - (int)$score),
        ];
    }

    protected function getScoreColor(float $score): string
    {
        return match(true) {
            $score >= 4.5 => 'text-emerald-500',
            $score >= 4.0 => 'text-blue-500',
            $score >= 3.5 => 'text-amber-500',
            $score >= 2.0 => 'text-orange-500',
            default => 'text-red-500',
        };
    }
}