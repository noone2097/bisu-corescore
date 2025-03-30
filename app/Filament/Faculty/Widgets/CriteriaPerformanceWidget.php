<?php

namespace App\Filament\Faculty\Widgets;

use App\Models\FacultyEvaluation;
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class CriteriaPerformanceWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'criteriaPerformance';
    
    protected static ?int $sort = 2;

    protected function getHeading(): string
    {
        return 'Performance by Criteria';
    }

    protected function getOptions(): array
    {
        $faculty = Auth::user();
        $evaluations = FacultyEvaluation::query()
            ->join('faculty_courses as fc', 'faculty_evaluations.faculty_course_id', '=', 'fc.id')
            ->join('evaluation_periods as ep', 'fc.evaluation_period_id', '=', 'ep.id')
            ->where('fc.faculty_id', $faculty->id)
            ->where('ep.status', 'Active')
            ->select('faculty_evaluations.*')
            ->get();

        if ($evaluations->isEmpty()) {
            return [
                'chart' => [
                    'type' => 'radar',
                    'height' => 350,
                ],
                'series' => [],
                'xaxis' => [
                    'categories' => ['Commitment', 'Knowledge', 'Teaching', 'Management'],
                ],
            ];
        }

        // Get the pre-calculated averages from evaluations
        $averages = [
            round($evaluations->avg('commitment_average'), 2),
            round($evaluations->avg('knowledge_average'), 2),
            round($evaluations->avg('teaching_average'), 2),
            round($evaluations->avg('management_average'), 2),
        ];

        return [
            'chart' => [
                'type' => 'radar',
                'height' => 350,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Average Score',
                    'data' => $averages,
                ],
            ],
            'xaxis' => [
                'categories' => ['Commitment', 'Knowledge', 'Teaching', 'Management'],
            ],
            'yaxis' => [
                'show' => true,
                'min' => 0,
                'max' => 5,
            ],
            'markers' => [
                'size' => 4,
            ],
            'stroke' => [
                'width' => 2,
            ],
            'fill' => [
                'opacity' => 0.2,
            ],
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}