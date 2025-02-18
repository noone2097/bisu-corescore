<?php

namespace App\Filament\Office\Widgets;

use App\Models\Feedback;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class FeedbackStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $office = Auth::guard('office')->user();
        
        $feedback = Feedback::where('office_id', $office->id);
        $today = Carbon::today();
        
        $totalFeedback = $feedback->count();
        $todayFeedback = $feedback->whereDate('created_at', $today)->count();
        
        $averageRating = $feedback->get()->avg(function ($feedback) {
            return $feedback->average_rating;
        });

        $latestFeedback = $feedback->latest()->first();

        return [
            Stat::make('Total Feedback', $totalFeedback)
                ->description('All time feedback')
                ->color('success')
                ->icon('heroicon-o-document-text'),
            
            Stat::make('Average Rating', number_format($averageRating ?? 0, 2))
                ->description('Overall satisfaction')
                ->color('primary')
                ->icon('heroicon-o-star'),
            
            Stat::make("Today's Feedback", $todayFeedback)
                ->description('Feedback received today')
                ->color('warning')
                ->icon('heroicon-o-calendar'),
            
            Stat::make('Latest Feedback', $latestFeedback ? $latestFeedback->created_at->diffForHumans() : 'No feedback yet')
                ->description('Most recent feedback')
                ->color('info')
                ->icon('heroicon-o-clock'),
        ];
    }
}