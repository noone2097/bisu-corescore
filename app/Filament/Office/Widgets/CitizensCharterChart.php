<?php

namespace App\Filament\Office\Widgets;

use App\Models\Feedback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\Auth;

class CitizensCharterChart extends ApexChartWidget
{
    protected static ?string $chartId = 'citizensCharterChart';
    protected static ?string $heading = 'Citizens Charter Ratings (Lower is Better)';
    protected static ?string $description = 'Rating Scale: 1 (Best) to 4 (Needs Improvement). Hover over labels for details.';
    protected static ?int $sort = 6;

    protected function getOptions(): array
    {
        $feedback = Feedback::where('office_id', Auth::id())->get();
        
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 285,
            ],
            'title' => [
                'text' => 'Rating Scale: 1 (Best) to 4 (Needs Improvement)',
                'align' => 'center',
                'style' => [
                    'fontSize' => '12px',
                    'fontWeight' => 'normal',
                    'color' => '#666666'
                ]
            ],
            'series' => [
                [
                    'name' => 'Rating',
                    'data' => [
                        round($feedback->avg('cc1'), 2),
                        round($feedback->whereNotIn('cc2', [5])->avg('cc2'), 2),
                        round($feedback->whereNotIn('cc3', [4])->avg('cc3'), 2),
                    ],
                ],
            ],
            'xaxis' => [
                'categories' => [
                    'CC1: Awareness',
                    'CC2: Visibility',
                    'CC3: Helpfulness',
                ],
                'labels' => [
                    'style' => [
                        'fontSize' => '11px',
                    ],
                ],
                'tooltip' => [
                    'formatter' => 'function (value) {
                        const tooltips = {
                            "CC1: Awareness (1=Know & Saw, 4=Don\'t Know)": "1: Know & Saw - Best<br>2: Know Only<br>3: Learned Here<br>4: Don\'t Know",
                            "CC2: Visibility (1=Easy to See, 4=Not Visible)": "1: Easy to See - Best<br>2: Somewhat Easy<br>3: Difficult<br>4: Not Visible<br>5: N/A",
                            "CC3: Helpfulness (1=Very Helpful, 4=N/A)": "1: Very Helpful - Best<br>2: Helped<br>3: Not Helpful<br>4: N/A"
                        };
                        return tooltips[value] || value;
                    }'
                ]
            ],
            'yaxis' => [
                'min' => 0,
                'max' => 4,
            ],
            'plotOptions' => [
                'bar' => [
                    'distributed' => true,
                ],
            ],
            'colors' => ['#4285F4', '#FBBC05', '#EA4335'],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '12px',
                ],
            ],
        ];
    }
}