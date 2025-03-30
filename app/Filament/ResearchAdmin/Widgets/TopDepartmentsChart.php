<?php

namespace App\Filament\ResearchAdmin\Widgets;

use App\Models\Departments;
use App\Models\FacultyEvaluation;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TopDepartmentsChart extends ApexChartWidget
{
    protected static ?int $sort = 2;
    
    protected static ?string $chartId = 'topDepartmentsChart';
    protected static ?string $heading = 'Top 5 Departments Performance';
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected function getOptions(): array
    {
        $departments = $this->getTopDepartments();
        
        $colors = [
            '#4285F4', // blue
            '#DB4437', // red
            '#f59e0b', // amber
            '#0F9D58', // green
        ];

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'width' => '100%',
                'redrawOnParentResize' => true,
                'redrawOnWindowResize' => true,
                'stacked' => false,
                'toolbar' => [
                    'show' => true,
                    'export' => [
                        'csv' => [
                            'filename' => 'Top Departments Performance',
                            'columnDelimiter' => ',',
                            'headerCategory' => 'Category',
                            'headerValue' => 'Value',
                        ],
                        'svg' => [
                            'filename' => 'Top Departments Performance',
                        ],
                        'png' => [
                            'filename' => 'Top Departments Performance',
                        ],
                    ],
                ],
            ],
            'series' => [
                [
                    'name' => 'Commitment',
                    'data' => $departments->pluck('commitment_avg')->toArray(),
                ],
                [
                    'name' => 'Knowledge',
                    'data' => $departments->pluck('knowledge_avg')->toArray(),
                ],
                [
                    'name' => 'Teaching',
                    'data' => $departments->pluck('teaching_avg')->toArray(),
                ],
                [
                    'name' => 'Management',
                    'data' => $departments->pluck('management_avg')->toArray(),
                ],
            ],
            'colors' => $colors,
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '70%',
                    'barHeight' => '85%',
                    'distributed' => false,
                    'borderRadiusApplication' => 'end',
                    'borderRadius' => 2,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent'],
            ],
            'xaxis' => [
                'categories' => $departments->pluck('name')->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'min' => 0,
                'max' => 5,
                'tickAmount' => 5,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'fill' => [
                'opacity' => 1,
            ],
            'tooltip' => [
                'y' => [
                    'formatter' => 'function (val) { return val.toFixed(2) }',
                ],
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'center',
                'offsetY' => 0,
                'itemMargin' => [
                    'horizontal' => 15
                ]
            ],
        ];
    }

    protected function getTopDepartments()
    {
        return Departments::select([
            'departments.id',
            'departments.name',
            DB::raw('ROUND(AVG((
                a1_demonstrates_sensitivity + 
                a2_integrates_learning_objectives + 
                a3_makes_self_available + 
                a4_comes_to_class_prepared + 
                a5_keeps_accurate_records
            ) / 5), 2) as commitment_avg'),
            DB::raw('ROUND(AVG((
                b1_demonstrates_mastery + 
                b2_draws_information + 
                b3_integrates_subject + 
                b4_explains_relevance + 
                b5_demonstrates_up_to_date
            ) / 5), 2) as knowledge_avg'),
            DB::raw('ROUND(AVG((
                c1_creates_teaching_strategies + 
                c2_enhances_self_esteem + 
                c3_allows_student_creation + 
                c4_allows_independent_thinking + 
                c5_encourages_extra_learning
            ) / 5), 2) as teaching_avg'),
            DB::raw('ROUND(AVG((
                d1_creates_opportunities + 
                d2_assumes_various_roles + 
                d3_designs_learning + 
                d4_structures_learning + 
                d5_uses_instructional_materials
            ) / 5), 2) as management_avg'),
            DB::raw('ROUND(AVG((
                (a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records) / 5 +
                (b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date) / 5 +
                (c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning) / 5 +
                (d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 5
            ) / 4), 2) as overall_avg')
        ])
        ->join('users', 'departments.id', '=', 'users.department_id')
        ->whereHas('faculty.facultyCourses.facultyEvaluations')
        ->join('faculty_courses', 'users.id', '=', 'faculty_courses.faculty_id')
        ->join('faculty_evaluations', 'faculty_courses.id', '=', 'faculty_evaluations.faculty_course_id')
        ->where('users.role', 'faculty')
        ->groupBy('departments.id', 'departments.name')
        ->orderByDesc('overall_avg')
        ->take(5)
        ->get();
    }
}