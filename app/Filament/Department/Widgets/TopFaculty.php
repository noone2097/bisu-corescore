<?php

namespace App\Filament\Department\Widgets;

use App\Models\FacultyEvaluation;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class TopFaculty extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'topFaculty';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected function getHeading(): string
    {
        $departmentUser = auth()->user();
        if ($departmentUser && $departmentUser->department_id) {
            return 'Top Faculty - ' . $departmentUser->department->name;
        }
        return 'Top Faculty';
    }

    protected static ?int $sort = 1;

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $query = FacultyEvaluation::selectRaw('
            fc.faculty_id,
            u.name as faculty_name,
            d.name as department_name,
            AVG(
                (a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records +
                b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date +
                c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning +
                d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 20.0
            ) as average_rating
        ')
        ->join('faculty_courses as fc', 'faculty_evaluations.faculty_course_id', '=', 'fc.id')
        ->join('users as u', 'fc.faculty_id', '=', 'u.id')
        ->join('departments as d', 'u.department_id', '=', 'd.id')
        ->where('u.role', 'faculty');

        // Filter by department if user is from a specific department
        $departmentUser = auth()->user();
        if ($departmentUser && $departmentUser->department_id) {
            $query->where('u.department_id', $departmentUser->department_id);
        }

        $topFaculty = $query->groupBy('fc.faculty_id', 'u.name', 'd.name')
            ->orderByDesc('average_rating')
            ->limit(5)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'toolbar' => [
                    'show' => false,
                ],
                'animations' => [
                    'enabled' => true,
                    'easing' => 'easeinout',
                    'speed' => 800,
                    'animateGradually' => [
                        'enabled' => true,
                        'delay' => 150,
                    ],
                    'dynamicAnimation' => [
                        'enabled' => true,
                        'speed' => 350,
                    ],
                ],
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent'],
            ],
            'series' => $topFaculty->map(fn($faculty) => [
                'name' => $faculty->faculty_name,
                'data' => [round($faculty->average_rating, 2)]
            ])->values()->toArray(),
            'xaxis' => [
                'categories' => ['Rating'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'min' => 0,
                'max' => 5,
            ],
            'colors' => ['#22c55e', '#3b82f6', '#f43f5e', '#8b5cf6', '#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'columnWidth' => '12%',
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'theme' => [
                'mode' => 'light',
                'monochrome' => [
                    'enabled' => false,
                ],
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#e5e7eb',
                'strokeDashArray' => 4,
                'padding' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 10,
                ],
            ],
            'legend' => [
                'show' => true,
                'position' => 'bottom',
                'horizontalAlign' => 'center',
                'floating' => false,
                'fontSize' => '14px',
                'fontFamily' => 'inherit',
                'itemMargin' => [
                    'horizontal' => 10,
                    'vertical' => 0,
                ],
                'onItemClick' => [
                    'toggleDataSeries' => true,
                ],
                'onItemHover' => [
                    'highlightDataSeries' => true,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '12px',
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
