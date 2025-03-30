<?php

namespace App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource\Exports;

use App\Models\FacultyEvaluation;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FacultyEvaluationsExport extends ExcelExport
{
    protected ?int $departmentId = null;

    public function withDepartment(?int $departmentId): static
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    public function setUp(): void
    {
        $department = $this->departmentId ? \App\Models\Departments::find($this->departmentId)?->name : 'All Departments';
        
        $this
            ->withFilename("Faculty Evaluations - {$department} - " . now()->format('Y-m-d'))
            ->withColumns([
                Column::make('faculty_name')
                    ->heading('Faculty Name')
                    ->formatStateUsing(fn ($record) => $record->facultyCourse?->faculty?->name ?? 'N/A'),

                Column::make('department')
                    ->heading('Department')
                    ->formatStateUsing(fn ($record) => $record->facultyCourse?->faculty?->department?->name ?? 'N/A'),

                Column::make('commitment_average')
                    ->heading('Commitment Score')
                    ->format(NumberFormat::FORMAT_NUMBER_00)
                    ->formatStateUsing(fn ($record) => $record->commitment_average),

                Column::make('knowledge_average')
                    ->heading('Knowledge Score')
                    ->format(NumberFormat::FORMAT_NUMBER_00)
                    ->formatStateUsing(fn ($record) => $record->knowledge_average),

                Column::make('teaching_average')
                    ->heading('Teaching Score')
                    ->format(NumberFormat::FORMAT_NUMBER_00)
                    ->formatStateUsing(fn ($record) => $record->teaching_average),

                Column::make('management_average')
                    ->heading('Management Score')
                    ->format(NumberFormat::FORMAT_NUMBER_00)
                    ->formatStateUsing(fn ($record) => $record->management_average),

                Column::make('overall_average')
                    ->heading('Overall Score')
                    ->format(NumberFormat::FORMAT_NUMBER_00)
                    ->formatStateUsing(fn ($record) => $record->overall_average)
            ])
            ->queue()
            ->withChunkSize(100);
    }

    public function collection()
    {
        $query = FacultyEvaluation::query()
            ->with([
                'facultyCourse' => fn($q) => $q->withTrashed(),
                'facultyCourse.faculty' => fn($q) => $q->withTrashed(),
                'facultyCourse.faculty.department'
            ]);

        // Apply department filter if set
        if ($this->departmentId) {
            $query->whereHas('facultyCourse.faculty', function ($q) {
                $q->withoutGlobalScopes()
                  ->where('department_id', $this->departmentId);
            });
        }

        return $query->get()
            ->groupBy('facultyCourse.faculty_id')
            ->map(function ($facultyEvals) {
                $firstRecord = $facultyEvals->first();
                
                // Calculate averages
                $avgCommitment = $facultyEvals->avg('commitment_average');
                $avgKnowledge = $facultyEvals->avg('knowledge_average');
                $avgTeaching = $facultyEvals->avg('teaching_average');
                $avgManagement = $facultyEvals->avg('management_average');
                $overallAvg = ($avgCommitment + $avgKnowledge + $avgTeaching + $avgManagement) / 4;

                // Set averages
                $firstRecord->commitment_average = $avgCommitment;
                $firstRecord->knowledge_average = $avgKnowledge;
                $firstRecord->teaching_average = $avgTeaching;
                $firstRecord->management_average = $avgManagement;
                $firstRecord->overall_average = $overallAvg;

                return $firstRecord;
            })
            ->values();
    }
}