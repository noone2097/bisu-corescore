<?php

namespace App\Filament\Office\Widgets;

use App\Models\Feedback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceQualityChart extends ApexChartWidget
{
    protected static ?string $chartId = 'serviceQualityChart';
    protected static ?string $heading = 'Service Quality Metrics';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '400px';

    protected int | string | array $columnSpan = 'full';

    private function getAverageRatings($feedback)
    {
        if ($feedback->isEmpty()) {
            return array_fill(0, 8, 0);
        }

        return [
            round($feedback->avg('responsiveness'), 2),
            round($feedback->avg('reliability'), 2),
            round($feedback->avg('access_facilities'), 2),
            round($feedback->avg('communication'), 2),
            round($feedback->avg('costs'), 2),
            round($feedback->avg('integrity'), 2),
            round($feedback->avg('assurance'), 2),
            round($feedback->avg('outcome'), 2),
        ];
    }

    protected function getOptions(): array
    {
        $currentMonth = Carbon::now();
        $previousMonth = $currentMonth->copy()->subMonth();

        // Get current month's feedback
        $currentMonthFeedback = Feedback::where('office_id', Auth::id())
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->get();

        // Get previous month's feedback
        $previousMonthFeedback = Feedback::where('office_id', Auth::id())
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->get();

        $dimensions = [
            'Responsiveness',
            'Reliability',
            'Access & Facilities',
            'Communication',
            'Costs',
            'Integrity',
            'Assurance',
            'Outcome'
        ];

        $currentMonthRatings = $this->getAverageRatings($currentMonthFeedback);
        $previousMonthRatings = $this->getAverageRatings($previousMonthFeedback);

        return [
            'chart' => [
                'type' => 'radar',
                'height' => 400,
                'toolbar' => [
                    'show' => false,
                ],
                'offsetY' => 0,
            ],
            'grid' => [
                'padding' => [
                    'top' => -10,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20
                ],
            ],
            'series' => [
                [
                    'name' => $currentMonth->format('F Y'),
                    'data' => $currentMonthRatings,
                ],
                [
                    'name' => $previousMonth->format('F Y'),
                    'data' => $previousMonthRatings,
                ],
            ],
            'xaxis' => [
                'categories' => $dimensions,
                'labels' => [
                    'style' => [
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'show' => true,
                'min' => 0,
                'max' => 5,
                'tickAmount' => 5,
            ],
            'colors' => ['#10B981', '#6B7280'], // Green for current month, Gray for previous
            'stroke' => [
                'width' => 2,
            ],
            'fill' => [
                'opacity' => 0.5,
            ],
            'markers' => [
                'size' => 4,
                'hover' => [
                    'size' => 6,
                ],
            ],
            'legend' => [
                'position' => 'bottom',
                'horizontalAlign' => 'center',
                'offsetY' => 10,
                'markers' => [
                    'offsetX' => 0,
                ],
            ],
            'tooltip' => [
                'y' => [
                    'formatter' => "function (value) { return value.toFixed(2) + ' / 5' }",
                ],
            ],
            'plotOptions' => [
                'radar' => [
                    'size' => 140,
                    'offsetX' => 0,
                    'offsetY' => 0,
                    'polygons' => [
                        'strokeColors' => '#e9e9e9',
                        'fill' => [
                            'colors' => ['#f8f8f8', '#fff']
                        ],
                        'connectorColors' => '#e9e9e9',
                    ],
                ],
            ],
        ];
    }
}