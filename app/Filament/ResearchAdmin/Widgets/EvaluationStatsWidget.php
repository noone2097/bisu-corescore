<?php

namespace App\Filament\ResearchAdmin\Widgets;

use App\Models\FacultyEvaluation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EvaluationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $evaluationStats = $this->calculateEvaluationStats();

        return [
            Stat::make('Total Evaluations', $evaluationStats['total_evaluations'])
                ->description('Total number of faculty evaluations')
                ->icon('heroicon-o-document-text')
                ->color('success'),
            
            Stat::make('Evaluated Faculty', $evaluationStats['faculty_count'])
                ->description('Number of faculty members evaluated')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Overall Assessment', $evaluationStats['sentiment_analysis']['text'])
                ->description($evaluationStats['sentiment_analysis']['description'])
                ->icon('heroicon-o-heart')
                ->color($evaluationStats['sentiment_analysis']['color']),

            Stat::make($evaluationStats['improvement_area']['name'], number_format($evaluationStats['improvement_area']['average'], 2))
                ->description('Area needing improvement')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('danger'),
            
            Stat::make('Average Overall Score', number_format($evaluationStats['average_score'], 2))
                ->description('Average score across all evaluations')
                ->icon('heroicon-o-star')
                ->color('warning'),

            Stat::make($evaluationStats['best_category']['name'], number_format($evaluationStats['best_category']['average'], 2))
                ->description('Best performing category')
                ->icon('heroicon-o-trophy')
                ->color('success'),
        ];
    }

    protected function calculateEvaluationStats(): array
    {
        // Get total evaluations
        $totalEvaluations = FacultyEvaluation::count();

        // If no evaluations exist, return default values
        if ($totalEvaluations === 0) {
            return [
                'total_evaluations' => 0,
                'average_score' => 0,
                'best_category' => ['name' => 'No Data', 'average' => 0],
                'improvement_area' => ['name' => 'No Data', 'average' => 0],
                'faculty_count' => 0,
                'sentiment_analysis' => [
                    'text' => 'No Data',
                    'color' => 'gray',
                    'description' => 'No faculty evaluations available'
                ],
            ];
        }

        // Calculate overall averages using raw SQL
        $averages = DB::select("
            SELECT
                ROUND(AVG((
                    (a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records) / 5 +
                    (b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date) / 5 +
                    (c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning) / 5 +
                    (d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 5
                ) / 4), 2) as overall_avg,
                ROUND(AVG((a1_demonstrates_sensitivity + a2_integrates_learning_objectives + a3_makes_self_available + a4_comes_to_class_prepared + a5_keeps_accurate_records) / 5), 2) as commitment_avg,
                ROUND(AVG((b1_demonstrates_mastery + b2_draws_information + b3_integrates_subject + b4_explains_relevance + b5_demonstrates_up_to_date) / 5), 2) as knowledge_avg,
                ROUND(AVG((c1_creates_teaching_strategies + c2_enhances_self_esteem + c3_allows_student_creation + c4_allows_independent_thinking + c5_encourages_extra_learning) / 5), 2) as teaching_avg,
                ROUND(AVG((d1_creates_opportunities + d2_assumes_various_roles + d3_designs_learning + d4_structures_learning + d5_uses_instructional_materials) / 5), 2) as management_avg
            FROM faculty_evaluations
        ")[0];

        // Convert stdClass to array for category comparisons
        $categoryAverages = [
            ['name' => 'Commitment', 'average' => $averages->commitment_avg],
            ['name' => 'Knowledge', 'average' => $averages->knowledge_avg],
            ['name' => 'Teaching', 'average' => $averages->teaching_avg],
            ['name' => 'Management', 'average' => $averages->management_avg],
        ];

        // Sort categories by average to find best and worst
        usort($categoryAverages, fn($a, $b) => $b['average'] <=> $a['average']);

        // Count unique faculty members evaluated
        $facultyCount = FacultyEvaluation::select('faculty_courses.faculty_id')
            ->join('faculty_courses', 'faculty_evaluations.faculty_course_id', '=', 'faculty_courses.id')
            ->distinct()
            ->count();

        // Calculate sentiment based on overall score
        $overallScore = $averages->overall_avg;
        $sentiment = match(true) {
            $overallScore >= 4.5 => ['text' => 'Excellent', 'color' => 'success', 'description' => 'Outstanding faculty performance'],
            $overallScore >= 4.0 => ['text' => 'Very Good', 'color' => 'success', 'description' => 'Above expected standards'],
            $overallScore >= 3.5 => ['text' => 'Good', 'color' => 'warning', 'description' => 'Meeting standards'],
            $overallScore >= 3.0 => ['text' => 'Fair', 'color' => 'warning', 'description' => 'Room for development'],
            default => ['text' => 'Needs Improvement', 'color' => 'danger', 'description' => 'Below expected standards'],
        };

        return [
            'total_evaluations' => $totalEvaluations,
            'average_score' => $averages->overall_avg,
            'best_category' => $categoryAverages[0],
            'improvement_area' => end($categoryAverages),
            'faculty_count' => $facultyCount,
            'sentiment_analysis' => $sentiment,
        ];
    }
}