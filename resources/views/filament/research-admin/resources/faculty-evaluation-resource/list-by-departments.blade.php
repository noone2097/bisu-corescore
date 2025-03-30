<style>
    @media print {
        @page {
            margin: 2cm;
            size: A4 landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            padding: 1rem;
        }
        .filament-modal-header,
        .filament-modal-footer,
        .fi-modal-close-btn {
            display: none !important;
        }
        .filament-modal-content {
            padding: 0 !important;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1.5rem 0;
        }
        th, td {
            padding: 12px 16px;
            border: 1px solid #000;
            text-align: left;
        }
        th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        h2.text-2xl {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 1rem;
        }
    }
</style>

@php
    use App\Models\Departments;
    $departments = collect();
    
    if (isset($title)) {
        // If specific department is selected, only show that department
        if (str_contains($title, 'All Departments')) {
            $departments = Departments::all();
        } else {
            $departmentName = str_replace('Faculty Evaluation Report - ', '', $title);
            $departments = Departments::where('name', $departmentName)->get();
        }
    } else {
        $departments = Departments::all();
    }
@endphp

<div>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-center">{{ $title ?? 'Faculty Evaluation Summary Report' }}</h2>
    </div>

    @foreach($departments as $department)
        @php
            $departmentEvaluations = $records->filter(function($record) use ($department) {
                return $record->facultyCourse?->faculty?->department_id === $department->id;
            });
        @endphp

        @if($departmentEvaluations->isNotEmpty())
            <div class="mb-8 bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-bold">{{ $department->name }}</h2>
                </div>

                <div class="p-4 department-table">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Faculty Name</th>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Commitment</th>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Knowledge</th>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Teaching</th>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Management</th>
                                <th class="px-4 py-2 text-left bg-gray-50 border">Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentEvaluations as $record)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $record->facultyCourse?->faculty?->name ?? 'Unknown Faculty' }}</td>
                                    <td class="px-4 py-2 border">{{ $record->courseDetails['course']['name'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($record->commitment_average, 2) }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($record->knowledge_average, 2) }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($record->teaching_average, 2) }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($record->management_average, 2) }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($record->overall_average, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-bold">
                                <td class="px-4 py-2 border" colspan="2">Department Average</td>
                                <td class="px-4 py-2 border">{{ number_format($departmentEvaluations->avg('commitment_average'), 2) }}</td>
                                <td class="px-4 py-2 border">{{ number_format($departmentEvaluations->avg('knowledge_average'), 2) }}</td>
                                <td class="px-4 py-2 border">{{ number_format($departmentEvaluations->avg('teaching_average'), 2) }}</td>
                                <td class="px-4 py-2 border">{{ number_format($departmentEvaluations->avg('management_average'), 2) }}</td>
                                <td class="px-4 py-2 border">{{ number_format($departmentEvaluations->avg('overall_average'), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    @if($departments->isEmpty() || $records->isEmpty())
        <div class="p-4 text-center text-gray-500">
            No evaluation records found.
        </div>
    @endif
</div>