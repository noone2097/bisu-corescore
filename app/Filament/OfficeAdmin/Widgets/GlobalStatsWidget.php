<?php

namespace App\Filament\OfficeAdmin\Widgets;

use App\Models\Feedback;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class GlobalStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        // Get total offices (users with office role)
        $totalOffices = User::where('role', 'office')->count();
        $activeOffices = User::where('role', 'office')->where('is_active', true)->count();

        // Get system-wide feedback stats
        $totalFeedback = Feedback::count();
        
        // Calculate system-wide average rating
        $averageRating = Feedback::selectRaw('
            ROUND(AVG(
                (responsiveness + reliability + access_facilities + 
                communication + costs + integrity + assurance + outcome) / 8
            ), 2) as avg_rating
        ')->value('avg_rating') ?? 0;

        // Get today's statistics
        $today = Carbon::today();
        $todayFeedback = Feedback::whereDate('created_at', $today)->count();
        
        // Calculate growth (compare with previous period)
        $previousPeriod = Feedback::whereBetween('created_at', [
            $today->copy()->subDays(7),
            $today
        ])->count();
        
        $weekBefore = Feedback::whereBetween('created_at', [
            $today->copy()->subDays(14),
            $today->copy()->subDays(7)
        ])->count();

        $growthRate = $weekBefore > 0 
            ? round((($previousPeriod - $weekBefore) / $weekBefore) * 100, 1)
            : 0;

        // Calculate percentage of high ratings (above 4.0)
        $highRatingsCount = Feedback::selectRaw('COUNT(*) as count')
            ->whereRaw('(responsiveness + reliability + access_facilities +
                      communication + costs + integrity + assurance + outcome) / 8 >= 4.0')
            ->value('count');

        $satisfactionRate = $totalFeedback > 0
            ? round(($highRatingsCount / $totalFeedback) * 100, 1)
            : 0;

        return [
            Stat::make('System Overview', "{$activeOffices} / {$totalOffices}")
                ->description('Active / Total Offices')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),

            Stat::make('Total Feedback', number_format($totalFeedback))
                ->description("Today: " . number_format($todayFeedback))
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([
                    $weekBefore,
                    $previousPeriod
                ])
                ->color($growthRate >= 0 ? 'success' : 'danger'),

            Stat::make('Average Rating', number_format($averageRating, 2))
                ->description('Overall system satisfaction')
                ->descriptionIcon('heroicon-m-star')
                ->color($averageRating >= 4 ? 'success' : ($averageRating >= 3 ? 'warning' : 'danger')),

            Stat::make('High Satisfaction Rate', $satisfactionRate . '%')
                ->description('Ratings above 4.0')
                ->descriptionIcon('heroicon-m-trophy')
                ->color($satisfactionRate >= 75 ? 'success' : ($satisfactionRate >= 50 ? 'warning' : 'danger')),
        ];
    }
}