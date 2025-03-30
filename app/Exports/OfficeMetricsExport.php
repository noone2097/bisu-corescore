<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\Feedback;

class OfficeMetricsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected Collection $data;
    protected User $exportedBy;

    public function __construct(User $exportedBy)
    {
        $this->exportedBy = $exportedBy;
        $this->data = User::where('role', 'office')->get()->map(function (User $office) {
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
                'avg_rating' => number_format($avgRating, 2),
                'cc_rating' => number_format($ccRating, 2),
                'feedback_count' => $office->feedback()->count(),
                'responsiveness' => number_format($office->feedback()->avg('responsiveness') ?? 0, 2),
                'reliability' => number_format($office->feedback()->avg('reliability') ?? 0, 2),
                'access_facilities' => number_format($office->feedback()->avg('access_facilities') ?? 0, 2),
                'communication' => number_format($office->feedback()->avg('communication') ?? 0, 2),
                'costs' => number_format($office->feedback()->avg('costs') ?? 0, 2),
                'integrity' => number_format($office->feedback()->avg('integrity') ?? 0, 2),
                'assurance' => number_format($office->feedback()->avg('assurance') ?? 0, 2),
                'outcome' => number_format($office->feedback()->avg('outcome') ?? 0, 2)
            ];
        });
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Office',
            'Overall Rating',
            'Citizens Charter Rating',
            'Total Feedback Count',
            'Responsiveness',
            'Reliability',
            'Access & Facilities',
            'Communication',
            'Costs',
            'Integrity',
            'Assurance',
            'Outcome'
        ];
    }

    public function map($row): array
    {
        return [
            $row['office_name'],
            $row['avg_rating'] . '/5.00',
            $row['cc_rating'] . '/5.00',
            $row['feedback_count'],
            $row['responsiveness'] . '/5.00',
            $row['reliability'] . '/5.00',
            $row['access_facilities'] . '/5.00',
            $row['communication'] . '/5.00',
            $row['costs'] . '/5.00',
            $row['integrity'] . '/5.00',
            $row['assurance'] . '/5.00',
            $row['outcome'] . '/5.00'
        ];
    }

    /**
     * Register events for the export.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->moveToFeedbackBin();
            },
        ];
    }

    /**
     * Move all feedback to the bin after export.
     */
    protected function moveToFeedbackBin(): void
    {
        $now = now();
        
        // Get all feedback IDs from the offices we exported
        $feedbackIds = User::where('role', 'office')
            ->with('feedback')
            ->get()
            ->pluck('feedback')
            ->flatten()
            ->pluck('id')
            ->toArray();

        // Update and soft delete all related feedback
        Feedback::whereIn('id', $feedbackIds)
            ->update([
                'exported_by' => $this->exportedBy->id,
                'exported_at' => $now,
            ]);

        Feedback::whereIn('id', $feedbackIds)->delete();
    }
}