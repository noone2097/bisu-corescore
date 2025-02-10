<?php

namespace App\Filament\Office\Widgets;

use App\Models\Evaluation;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class EvaluationStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $office = Auth::guard('office')->user();
        
        $evaluations = Evaluation::where('office_id', $office->id);
        $today = Carbon::today();
        
        $totalEvaluations = $evaluations->count();
        $todayEvaluations = $evaluations->whereDate('created_at', $today)->count();
        
        $averageRating = $evaluations->get()->avg(function ($evaluation) {
            return $evaluation->average_rating;
        });

        $latestEvaluation = $evaluations->latest()->first();

        return [
            Stat::make('Total Evaluations', $totalEvaluations)
                ->description('All time evaluations')
                ->color('success')
                ->icon('heroicon-o-document-text'),
            
            Stat::make('Average Rating', number_format($averageRating ?? 0, 2))
                ->description('Overall satisfaction')
                ->color('primary')
                ->icon('heroicon-o-star'),
            
            Stat::make("Today's Evaluations", $todayEvaluations)
                ->description('Evaluations received today')
                ->color('warning')
                ->icon('heroicon-o-calendar'),
            
            Stat::make('Latest Evaluation', $latestEvaluation ? $latestEvaluation->created_at->diffForHumans() : 'No evaluations yet')
                ->description('Most recent feedback')
                ->color('info')
                ->icon('heroicon-o-clock'),
        ];
    }
}