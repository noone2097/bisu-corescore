<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OfficeMetricsController extends Controller
{
    public function print()
    {
        $data = User::where('role', 'office')->get()->map(function (User $office) {
            $avgRating = $office->feedback()
                ->selectRaw('AVG((responsiveness + reliability + access_facilities + communication + costs + integrity + assurance + outcome) / 8) as avg_rating')
                ->first()
                ->avg_rating ?? 0;
                
            $ccRating = $office->feedback()
                ->selectRaw('AVG((cc1 + cc2 + cc3) / 3) as cc_rating')
                ->first()
                ->cc_rating ?? 0;

            return [
                'office_name' => $office->name,
                'avg_rating' => number_format($avgRating, 2) . '/5.00',
                'cc_rating' => number_format($ccRating, 2) . '/5.00',
                'feedback_count' => $office->feedback()->count(),
                'responsiveness' => number_format($office->feedback()->avg('responsiveness') ?? 0, 2) . '/5.00',
                'reliability' => number_format($office->feedback()->avg('reliability') ?? 0, 2) . '/5.00',
                'access_facilities' => number_format($office->feedback()->avg('access_facilities') ?? 0, 2) . '/5.00',
                'communication' => number_format($office->feedback()->avg('communication') ?? 0, 2) . '/5.00',
                'costs' => number_format($office->feedback()->avg('costs') ?? 0, 2) . '/5.00',
                'integrity' => number_format($office->feedback()->avg('integrity') ?? 0, 2) . '/5.00',
                'assurance' => number_format($office->feedback()->avg('assurance') ?? 0, 2) . '/5.00',
                'outcome' => number_format($office->feedback()->avg('outcome') ?? 0, 2) . '/5.00'
            ];
        });

        return view('filament.office-admin.reports.office-metrics', [
            'records' => $data,
            'generated_at' => now()->format('F j, Y h:i A')
        ]);
    }
}
