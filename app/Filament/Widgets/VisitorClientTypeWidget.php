<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class VisitorClientTypeWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'visitorClientTypeWidget';

    protected static ?string $heading = 'Visitor Traffic Analysis';

    protected static ?int $sort = 4;

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
                'type' => 'line',
                'height' => 350,
                'dropShadow' => [
                    'enabled' => true,
                    'color' => '#000',
                    'top' => 18,
                    'left' => 7,
                    'blur' => 10,
                    'opacity' => 0.2,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => $data['series'],
            'colors' => [
                '#FFB940',    // Citizen - Warmer Yellow
                '#4A89F3',    // Business - Softer Blue
                '#F16B6B',    // Government - Softer Red
            ],
            'dataLabels' => [
                'enabled' => true,
                'background' => [
                    'enabled' => true,
                    'foreColor' => '#fff',
                    'padding' => 4,
                    'borderRadius' => 2,
                    'borderWidth' => 1,
                    'borderColor' => '#fff',
                    'opacity' => 0.9,
                ],
            ],
            'stroke' => [
                'width' => 3,
                'curve' => 'smooth',
            ],
            'grid' => [
                'borderColor' => '#e7e7e7',
                'row' => [
                    'colors' => ['transparent'],
                    'opacity' => 0,
                ],
            ],
            'markers' => [
                'size' => 1,
                'hover' => [
                    'size' => 5,
                ],
            ],
            'xaxis' => [
                'categories' => $data['dates'],
                'title' => [
                    'text' => 'Date',
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Number of Visitors',
                ],
                'min' => 0,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'center',
                'floating' => false,
                'fontSize' => '14px',
                'fontFamily' => 'inherit',
                'offsetY' => 0,
            ],
        ];
    }

    protected function getData(): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        $dates = [];
        $dateMap = [];
        
        // Generate all dates for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $formatted = $date->format('M d');
            $dbFormat = $date->format('Y-m-d');
            $dates[] = $formatted;
            $dateMap[$dbFormat] = $i;
        }

        // Initialize series data for each client type
        $clientTypes = ['Citizen', 'Business', 'Government'];
        $seriesData = [];
        foreach ($clientTypes as $type) {
            $seriesData[$type] = array_fill(0, 7, 0);
        }

        // Get actual traffic data
        $traffic = Feedback::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            'client_type'
        ])
        ->whereDate('created_at', '>=', $startDate)
        ->whereIn('client_type', $clientTypes)
        ->groupBy('date', 'client_type')
        ->orderBy('date')
        ->get();

        // Fill in actual values
        foreach ($traffic as $record) {
            $dateIndex = $dateMap[$record->date] ?? null;
            if ($dateIndex !== null && isset($seriesData[$record->client_type])) {
                $seriesData[$record->client_type][$dateIndex] = (int) $record->total;
            }
        }

        // Format series for chart
        $series = [];
        foreach ($clientTypes as $type) {
            $series[] = [
                'name' => $type,
                'data' => $seriesData[$type],
            ];
        }

        return [
            'series' => $series,
            'dates' => $dates,
        ];
    }
}
