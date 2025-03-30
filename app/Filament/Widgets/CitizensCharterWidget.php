<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class CitizensCharterWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'citizensCharterWidget';

    protected static ?string $heading = 'Citizens Charter Analysis';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

    protected function getOptions(): array
    {
        $data = $this->getData();

        return [
            'chart' => [
                'height' => 350,
                'type' => 'line',
                'stacked' => false,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'stroke' => [
                'width' => [0, 2, 4],
                'curve' => 'smooth'
            ],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '50%',
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ]
            ],
            'colors' => ['#4A89F3', '#FFB940', '#F16B6B'],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '9px',
                    'fontFamily' => 'inherit',
                    'fontWeight' => 'normal',
                ],
                'background' => [
                    'enabled' => true,
                    'foreColor' => '#fff',
                    'padding' => 2,
                    'borderRadius' => 2,
                    'borderWidth' => 1,
                    'borderColor' => '#fff',
                    'opacity' => 0.9,
                ],
            ],
            'series' => [
                [
                    'name' => 'CC1: Awareness',
                    'type' => 'column',
                    'data' => array_merge($data['cc1'], array_fill(0, 9, null))
                ],
                [
                    'name' => 'CC2: Visibility',
                    'type' => 'area',
                    'data' => array_merge(array_fill(0, 4, null), $data['cc2'], array_fill(0, 4, null))
                ],
                [
                    'name' => 'CC3: Usefulness',
                    'type' => 'line',
                    'data' => array_merge(array_fill(0, 9, null), $data['cc3'])
                ]
            ],
            'fill' => [
                'opacity' => [0.85, 0.25, 1],
                'gradient' => [
                    'inverseColors' => false,
                    'shade' => 'light',
                    'type' => "vertical",
                    'opacityFrom' => 0.85,
                    'opacityTo' => 0.55,
                ]
            ],
            'labels' => [
                // CC1 labels (4)
                'Know & Saw',
                'Know Only',
                'Learned Here',
                'Don\'t Know',
                // CC2 labels (5)
                'Easy to See',
                'Somewhat Easy',
                'Difficult',
                'Not Visible',
                'N/A',
                // CC3 labels (4)
                'Very Helpful',
                'Helped',
                'Not Helpful',
                'N/A'
            ],
            'xaxis' => [
                'type' => 'category',
                'labels' => [
                    'rotate' => -25,
                    'rotateAlways' => true,
                    'style' => [
                        'fontSize' => '9px',
                        'fontFamily' => 'inherit',
                    ],
                ],
                'group' => [
                    'style' => [
                        'fontSize' => '9px',
                    ],
                    'groups' => [
                        ['from' => 0, 'to' => 3, 'title' => 'Awareness'],
                        ['from' => 4, 'to' => 8, 'title' => 'Visibility'],
                        ['from' => 9, 'to' => 12, 'title' => 'Usefulness'],
                    ]
                ]
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Response Count',
                ],
                'min' => 0,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'center',
                'floating' => false,
                'fontSize' => '10px',
                'fontFamily' => 'inherit',
                'offsetY' => 0,
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
            ],
        ];
    }

    protected function getData(): array
    {
        // Get counts for CC1 (1-4)
        $cc1Data = Feedback::select('cc1', DB::raw('count(*) as count'))
            ->whereNotNull('cc1')
            ->groupBy('cc1')
            ->orderBy('cc1')
            ->pluck('count', 'cc1')
            ->toArray();

        // Get counts for CC2 (1-5)
        $cc2Data = Feedback::select('cc2', DB::raw('count(*) as count'))
            ->whereNotNull('cc2')
            ->groupBy('cc2')
            ->orderBy('cc2')
            ->pluck('count', 'cc2')
            ->toArray();

        // Get counts for CC3 (1-4)
        $cc3Data = Feedback::select('cc3', DB::raw('count(*) as count'))
            ->whereNotNull('cc3')
            ->groupBy('cc3')
            ->orderBy('cc3')
            ->pluck('count', 'cc3')
            ->toArray();

        // Fill in missing values with 0
        $cc1 = array_map(function($i) use ($cc1Data) {
            return $cc1Data[$i] ?? 0;
        }, range(1, 4));

        $cc2 = array_map(function($i) use ($cc2Data) {
            return $cc2Data[$i] ?? 0;
        }, range(1, 5));

        $cc3 = array_map(function($i) use ($cc3Data) {
            return $cc3Data[$i] ?? 0;
        }, range(1, 4));

        return [
            'cc1' => $cc1,
            'cc2' => $cc2,
            'cc3' => $cc3,
        ];
    }
}
