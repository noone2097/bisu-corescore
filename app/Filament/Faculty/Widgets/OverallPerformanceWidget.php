<?php

namespace App\Filament\Faculty\Widgets;

use App\Models\FacultyEvaluation;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class OverallPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $faculty = Auth::user();
        
        $evaluations = FacultyEvaluation::query()
            ->join('faculty_courses as fc', 'faculty_evaluations.faculty_course_id', '=', 'fc.id')
            ->join('evaluation_periods as ep', 'fc.evaluation_period_id', '=', 'ep.id')
            ->where('fc.faculty_id', $faculty->id)
            ->where('ep.status', 'Active')
            ->get();
            
        $totalEvaluations = $evaluations->count();
        
        if ($totalEvaluations === 0) {
            return [
                Stat::make('Overall Rating', 'No evaluations yet')
                    ->description('No student evaluations received')
                    ->icon('heroicon-o-star'),
                    
                Stat::make('Total Evaluations', '0')
                    ->description('No evaluations yet')
                    ->icon('heroicon-o-document-text'),
                    
                Stat::make('Performance Level', 'N/A')
                    ->description('Insufficient data')
                    ->icon('heroicon-o-trophy'),
            ];
        }

        $overallRating = $evaluations->avg(function ($evaluation) {
            return ($evaluation->commitment_average + 
                    $evaluation->knowledge_average + 
                    $evaluation->teaching_average + 
                    $evaluation->management_average) / 4;
        });

        $formattedRating = number_format($overallRating, 2);
        $stars = str_repeat('★', (int)$overallRating) . str_repeat('☆', 5 - (int)$overallRating);
        
        // Determine performance level and color
        $performanceLevel = match(true) {
            $overallRating >= 4.5 => ['level' => 'Outstanding', 'color' => Color::Emerald],
            $overallRating >= 4.0 => ['level' => 'Very Satisfactory', 'color' => Color::Blue],
            $overallRating >= 3.5 => ['level' => 'Satisfactory', 'color' => Color::Amber],
            $overallRating >= 2.0 => ['level' => 'Fair', 'color' => Color::Orange],
            default => ['level' => 'Poor', 'color' => Color::Red],
        };

        return [
            Stat::make('Overall Rating', $formattedRating)
                ->description($stars)
                ->color($performanceLevel['color'])
                ->icon('heroicon-o-star'),
                
            Stat::make('Total Evaluations', (string)$totalEvaluations)
                ->description('Student evaluations received')
                ->color($performanceLevel['color'])
                ->icon('heroicon-o-document-text'),
                
            Stat::make('Performance Level', $performanceLevel['level'])
                ->description('Based on overall rating')
                ->color($performanceLevel['color'])
                ->icon('heroicon-o-trophy'),
        ];
    }
}