<?php

namespace App\Filament\Office\Widgets;

use App\Models\Feedback;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeedbackStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $feedback = Feedback::where('office_id', auth()->id());
        $today = Carbon::today();
        $currentMonth = Carbon::now();
        
        \Log::info('Calculating monthly stats for office: ' . auth()->id());
        
        // Get this year's average rating
        $yearlyFeedbacks = Feedback::where('office_id', auth()->id())
            ->whereYear('created_at', $currentMonth->year)
            ->get();
            
        $yearlyAvg = $yearlyFeedbacks->avg(function ($feedback) {
            return ($feedback->responsiveness +
                    $feedback->reliability +
                    $feedback->access_facilities +
                    $feedback->communication +
                    $feedback->costs +
                    $feedback->integrity +
                    $feedback->assurance +
                    $feedback->outcome) / 8;
        });
            
        \Log::info('Yearly average rating: ' . $yearlyAvg);
        
        // Get current month's average rating
        $currentMonthFeedbacks = Feedback::where('office_id', auth()->id())
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->get();
            
        $currentMonthAvg = $currentMonthFeedbacks->avg(function ($feedback) {
            return ($feedback->responsiveness +
                    $feedback->reliability +
                    $feedback->access_facilities +
                    $feedback->communication +
                    $feedback->costs +
                    $feedback->integrity +
                    $feedback->assurance +
                    $feedback->outcome) / 8;
        });
            
        \Log::info('Current month average rating: ' . $currentMonthAvg);
        
        // Get previous month's average rating
        $previousMonth = $currentMonth->copy()->subMonth();
        $previousMonthFeedbacks = Feedback::where('office_id', auth()->id())
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->get();
            
        $previousMonthAvg = $previousMonthFeedbacks->avg(function ($feedback) {
            return ($feedback->responsiveness +
                    $feedback->reliability +
                    $feedback->access_facilities +
                    $feedback->communication +
                    $feedback->costs +
                    $feedback->integrity +
                    $feedback->assurance +
                    $feedback->outcome) / 8;
        });
            
        \Log::info('Previous month average rating: ' . $previousMonthAvg);

        // Get current month's total feedback count
        $currentMonthTotal = Feedback::where('office_id', auth()->id())
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
            
        \Log::info('Current month total feedback: ' . $currentMonthTotal);

        return [
            Stat::make($currentMonth->format('Y') . ' Average', number_format($yearlyAvg ?? 0, 2))
                ->description('Year to date satisfaction')
                ->color('success')
                ->icon('heroicon-o-chart-bar'),
            
            Stat::make($currentMonth->format('F') . ' Rating', number_format($currentMonthAvg ?? 0, 2))
                ->description('Current month satisfaction')
                ->color('primary')
                ->icon('heroicon-o-star'),
            
            Stat::make($previousMonth->format('F') . ' Rating', number_format($previousMonthAvg ?? 0, 2))
                ->description('Previous month satisfaction')
                ->color('warning')
                ->icon('heroicon-o-star'),
            
            Stat::make($currentMonth->format('F') . ' Feedback', $currentMonthTotal)
                ->description('Current month total feedback')
                ->color('info')
                ->icon('heroicon-o-document-text'),
        ];
    }
}