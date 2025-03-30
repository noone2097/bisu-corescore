<?php

namespace App\Filament\ResearchAdmin\Widgets;

use App\Models\Departments;
use App\Models\FacultyEvaluation;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class DepartmentRadarChart extends ApexChartWidget
{
    protected static ?int $sort = 3;
    
    protected static ?string $chartId = 'departmentRadarChart';
    protected static ?string $heading = 'Department Performance Analysis';
    protected static ?int $contentHeight = 400;
    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $departments = $this->getDepartmentPerformance();
        
        $series = [];
        foreach ($departments as $department) {
            $series[] = [
                'name' => $department->name,
                'data' => [
                    round($department->commitment_avg, 2),
                    round($department->knowledge_avg, 2),
                    round($department->teaching_avg, 2),
                    round($department->management_avg, 2),
                ],
            ];
        }

        return [
            'chart' => [
                'type' => 'radar',
                'height' => 400,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => $series,
            'xaxis' => [
                'categories' => ['Commitment', 'Knowledge', 'Teaching', 'Management'],
            ],
            'yaxis' => [
                'show' => true,
                'min' => 0,
                'max' => 5,
            ],
            'plotOptions' => [
                'radar' => [
                    'polygons' => [
                        'strokeColors' => '#e8e8e8',
                        'fill' => [
                            'colors' => ['#f8f8f8', '#fff']
                        ]
                    ]
                ]
            ],
            'stroke' => [
                'width' => 2,
            ],
            'fill' => [
                'opacity' => 0.2,
            ],
            'markers' => [
                'size' => 4,
            ],
            'legend' => [
                'position' => 'top',
            ],
        ];
    }

    protected function getDepartmentPerformance()
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
            ) / 5), 2) as management_avg')
        ])
        ->join('users', 'departments.id', '=', 'users.department_id')
        ->whereHas('faculty.facultyCourses.facultyEvaluations')
        ->join('faculty_courses', 'users.id', '=', 'faculty_courses.faculty_id')
        ->join('faculty_evaluations', 'faculty_courses.id', '=', 'faculty_evaluations.faculty_course_id')
        ->where('users.role', 'faculty')
        ->groupBy('departments.id', 'departments.name')
        ->orderBy('departments.name')
        ->get();
    }
}