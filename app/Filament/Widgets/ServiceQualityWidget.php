<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ServiceQualityWidget extends ApexChartWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected static ?string $chartId = 'serviceQualityWidget';

    protected static ?string $heading = 'Top Performing Offices';

    protected function getOptions(): array
    {
        $ratings = ['Responsiveness', 'Reliability', 'Access & Facilities', 'Communication', 'Costs', 'Integrity', 'Assurance', 'Outcome'];
        $dbColumns = ['responsiveness', 'reliability', 'access_facilities', 'communication', 'costs', 'integrity', 'assurance', 'outcome'];
        
        $topOffices = \App\Models\Office::query()
            ->select(
                'offices.id',
                'offices.office_name',
                \DB::raw('AVG(responsiveness) as responsiveness'),
                \DB::raw('AVG(reliability) as reliability'),
                \DB::raw('AVG(access_facilities) as access_facilities'),
                \DB::raw('AVG(communication) as communication'),
                \DB::raw('AVG(costs) as costs'),
                \DB::raw('AVG(integrity) as integrity'),
                \DB::raw('AVG(assurance) as assurance'),
                \DB::raw('AVG(outcome) as outcome')
            )
            ->join('feedback', 'offices.id', '=', 'feedback.office_id')
            ->groupBy('offices.id', 'offices.office_name')
            ->orderByDesc(\DB::raw('(
                AVG(responsiveness) + AVG(reliability) + AVG(access_facilities) +
                AVG(communication) + AVG(costs) + AVG(integrity) +
                AVG(assurance) + AVG(outcome)
            ) / 8.0'))
            ->limit(5)
            ->get();

        $series = $topOffices->map(function($office) use ($dbColumns) {
            return [
                'name' => $office->office_name,
                'data' => collect($dbColumns)->map(function($column) use ($office) {
                    return round($office->$column, 1);
                })->toArray()
            ];
        })->toArray();

        $colors = [
            '#4285F4', // blue
            '#DB4437', // red
            '#f59e0b', // amber
            '#0F9D58', // green
            '#8b5cf6', // purple
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
                            'filename' => 'Top Performing Offices',
                            'columnDelimiter' => ',',
                            'headerCategory' => 'Category',
                            'headerValue' => 'Value',
                        ],
                        'svg' => [
                            'filename' => 'Top Performing Offices',
                        ],
                        'png' => [
                            'filename' => 'Top Performing Offices',
                        ],
                    ],
                ],
            ],
            'series' => $series,
            'xaxis' => [
                'categories' => $ratings,
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
}
