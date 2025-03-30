<?php

namespace App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource\Exports;

use App\Models\Departments;
use App\Models\FacultyEvaluation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class DepartmentEvaluationsExport
{
    public static function configureExport(int $departmentId): ExcelExport
    {
        $department = Departments::findOrFail($departmentId);
        
        return ExcelExport::make('department_evaluations')
            ->fromModel(
                FacultyEvaluation::class,
                function ($query) use ($departmentId) {
                    $query->whereHas('facultyCourse.faculty.department', function ($q) use ($departmentId) {
                        $q->where('id', $departmentId);
                    })
                    ->with(['facultyCourse.faculty.department']);
                }
            )
            ->withColumns([
                Column::make('facultyCourse.faculty.name')
                    ->heading('Faculty Name'),
                Column::make('facultyCourse.faculty.department.name')
                    ->heading('Department'),
                Column::make('commitment_average')
                    ->heading('Commitment')
                    ->format(NumberFormat::FORMAT_NUMBER_00),
                Column::make('knowledge_average')
                    ->heading('Knowledge')
                    ->format(NumberFormat::FORMAT_NUMBER_00),
                Column::make('teaching_average')
                    ->heading('Teaching')
                    ->format(NumberFormat::FORMAT_NUMBER_00),
                Column::make('management_average')
                    ->heading('Management')
                    ->format(NumberFormat::FORMAT_NUMBER_00),
                Column::make('overall_average')
                    ->heading('Overall')
                    ->format(NumberFormat::FORMAT_NUMBER_00),
            ])
            ->withFilename("Department Evaluations - {$department->name} - " . now()->format('Y-m-d'))
            ->fileName("Department Evaluations - {$department->name} - " . now()->format('Y-m-d') . '.xlsx');
    }
}