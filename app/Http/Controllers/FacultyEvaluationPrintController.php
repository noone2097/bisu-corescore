<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\FacultyEvaluation;
use Illuminate\Http\Request;

class FacultyEvaluationPrintController extends Controller
{
    public function print(Departments $department)
    {
        $evaluations = FacultyEvaluation::query()
            ->whereHas('facultyCourse.faculty.department', function ($q) use ($department) {
                $q->where('id', $department->id);
            })
            ->with(['facultyCourse.faculty.department'])
            ->get();

        return view('filament.research-admin.resources.faculty-evaluation-resource.print', [
            'records' => $evaluations,
            'title' => "Faculty Evaluation Report - {$department->name}"
        ]);
    }
}
