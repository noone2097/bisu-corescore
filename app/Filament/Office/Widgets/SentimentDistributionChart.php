<?php

namespace App\Filament\Office\Widgets;

use App\Models\Feedback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\Auth;

class SentimentDistributionChart extends ApexChartWidget
{
    protected static ?string $chartId = 'sentimentDistributionChart';
    protected static ?string $heading = 'Feedback Sentiment Distribution';
    protected static ?int $sort = 2;

    protected function getOptions(): array
    {
        $feedback = Feedback::where('office_id', Auth::id())->get();
        
        // Calculate sentiment based on average ratings
        $sentimentCounts = [
            'positive' => 0,
            'neutral' => 0,
            'negative' => 0
        ];

        foreach ($feedback as $entry) {
            $avgRating = ($entry->responsiveness +
                         $entry->reliability +
                         $entry->access_facilities +
                         $entry->communication +
                         $entry->costs +
                         $entry->integrity +
                         $entry->assurance +
                         $entry->outcome) / 8;
            
            // Categorize sentiment
            if ($avgRating >= 4) {
                $sentimentCounts['positive']++;
            } elseif ($avgRating >= 3) {
                $sentimentCounts['neutral']++;
            } else {
                $sentimentCounts['negative']++;
            }
        }

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => array_values($sentimentCounts),
            'labels' => ['Positive', 'Neutral', 'Negative'],
            'colors' => ['#10B981', '#F59E0B', '#EF4444'],
            'legend' => [
                'position' => 'bottom'
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'showAlways' => true,
                                'label' => 'Total Feedback'
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}