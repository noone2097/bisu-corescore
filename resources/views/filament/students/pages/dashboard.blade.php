<x-filament-panels::page>
    <div>
        {{-- Minimalist Evaluation Period Countdown --}}
        @php
            $activePeriod = \App\Models\EvaluationPeriod::where('status', 'active')->first();
        @endphp

        @if($activePeriod)
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="max-w-3xl mx-auto text-center space-y-12">
                    <div>
                        <h1 class="text-xl font-medium text-gray-900 dark:text-white">
                            {{ $activePeriod->name }} Evaluation Period
                        </h1>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $activePeriod->start_date->format('M d, Y') }} - {{ $activePeriod->end_date->format('M d, Y') }}
                        </div>
                        <br>
                    </div>

                    <div>
                        <div class="flex justify-center items-center">
                            <div class="text-center">
                                <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-lg shadow px-5 py-4 w-20 relative">
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 inline-flex items-center justify-center bg-primary-100 dark:bg-primary-900 rounded-full p-2">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium text-gray-900 dark:text-white mt-1" id="days">--</div>
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-3">Days</div>
                            </div>
                            
                            <div class="mx-8 text-2xl font-light text-gray-300 dark:text-gray-600">&nbsp;</div>
                            
                            <div class="text-center">
                                <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-lg shadow px-5 py-4 w-20 relative">
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 inline-flex items-center justify-center bg-primary-100 dark:bg-primary-900 rounded-full p-2">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium text-gray-900 dark:text-white mt-1" id="hours">--</div>
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-3">Hours</div>
                            </div>
                            
                            <div class="mx-8 text-2xl font-light text-gray-300 dark:text-gray-600"> &nbsp; </div>
                            
                            <div class="text-center">
                                <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-lg shadow px-5 py-4 w-20 relative">
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 inline-flex items-center justify-center bg-primary-100 dark:bg-primary-900 rounded-full p-2">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium text-gray-900 dark:text-white mt-1" id="minutes">--</div>
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-3">Min</div>
                            </div>
                            
                            <div class="mx-8 text-2xl font-light text-gray-300 dark:text-gray-600"> &nbsp; </div>
                            
                            <div class="text-center">
                                <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-lg shadow px-5 py-4 w-20 relative">
                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 inline-flex items-center justify-center bg-primary-100 dark:bg-primary-900 rounded-full p-2">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2m4-4a8 8 0 11-16 0 8 8 0 0116 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium text-gray-900 dark:text-white mt-1" id="seconds">--</div>
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-3">Sec</div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div>
                        <a href="{{ route('filament.students.resources.faculty-evaluations.create') }}" 
                            class="inline-flex items-center px-3 py-1.5 bg-primary-600 border border-transparent rounded-md text-xs text-white hover:bg-primary-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Start Evaluation
                        </a>
                    </div>
                </div>

                {{-- JavaScript for countdown timer --}}
                <script>
                    // End date from the database
                    const endDate = new Date('{{ $activePeriod->end_date->format('Y-m-d') }}T23:59:59');
                    
                    // Update the countdown every second
                    const countdownTimer = setInterval(function() {
                        const now = new Date().getTime();
                        const timeRemaining = endDate - now;
                        
                        const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                        
                        document.getElementById('days').innerHTML = days;
                        document.getElementById('hours').innerHTML = hours;
                        document.getElementById('minutes').innerHTML = minutes;
                        document.getElementById('seconds').innerHTML = seconds;
                        
                        if (timeRemaining < 0) {
                            clearInterval(countdownTimer);
                            document.getElementById('days').innerHTML = '0';
                            document.getElementById('hours').innerHTML = '0';
                            document.getElementById('minutes').innerHTML = '0';
                            document.getElementById('seconds').innerHTML = '0';
                        }
                    }, 1000);
                </script>
            </div>

            {{-- Faculty Evaluation Status Table --}}
            <div class="mt-6 p-6 bg-white rounded-lg shadow dark:bg-gray-800 w-full">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Faculty Evaluation Status</h3>
                
                @php
                    $student = Auth::guard('students')->user();
                    $studentType = $student->student_type ?? 'regular';
                    $isIrregular = strtolower($studentType) === 'irregular';
                    
                    // Get faculty evaluations that this student has already submitted
                    $evaluations = App\Models\FacultyEvaluation::query()
                        ->where('student_id', $student->id)
                        ->whereHas('facultyCourse', function ($query) use ($activePeriod) {
                            $query->where('evaluation_period_id', $activePeriod->id);
                        })
                        ->get();
                        
                    $evaluatedFacultyIds = $evaluations->pluck('faculty_course_id')->toArray();

                    // Get available faculty courses for this evaluation period
                    $facultyCourses = App\Models\FacultyCourse::query()
                        ->join('users', 'faculty_courses.faculty_id', '=', 'users.id')
                        ->join('courses', 'faculty_courses.course_id', '=', 'courses.id')
                        ->where('users.department_id', $student->department_id)
                        ->where('users.is_active', true)
                        ->when($student->student_type === 'regular', function($query) use ($student) {
                            $query->where('courses.year_level_id', $student->year_level_id);
                        })
                        ->where('faculty_courses.evaluation_period_id', $activePeriod->id)
                        ->select('faculty_courses.*')
                        ->with([
                            'faculty',
                            'course',
                        ])
                        ->get();
                    
                    // For irregular students, only show evaluated faculty
                    if ($isIrregular) {
                        $facultyCourses = $facultyCourses->filter(function($facultyCourse) use ($evaluatedFacultyIds) {
                            return in_array($facultyCourse->id, $evaluatedFacultyIds);
                        });
                    }
                @endphp

                <div class="max-w-5xl mx-auto overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mx-auto">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="w-12 px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avatar</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty Name</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Overall Rating</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                            <style>
                                .table-row-hover:hover {
                                    background-color: rgba(243, 244, 246, 1); /* light mode: gray-50 */
                                    cursor: pointer;
                                }
                                .dark .table-row-hover:hover {
                                    background-color: rgba(55, 65, 81, 0.5); /* dark mode: gray-700 with 50% opacity */
                                    cursor: pointer;
                                }
                            </style>
                            @forelse($facultyCourses as $facultyCourse)
                                @php
                                    $isEvaluated = in_array($facultyCourse->id, $evaluatedFacultyIds);
                                    $evaluationUrl = $isEvaluated ? route('filament.students.resources.faculty-evaluations.view', ['record' => $evaluations->where('faculty_course_id', $facultyCourse->id)->first()->id]) : '#';
                                @endphp
                                <tr class="table-row-hover transition-colors duration-150" @if($isEvaluated) onclick="window.location='{{ $evaluationUrl }}'" @endif>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex-shrink-0 h-10 w-10 mx-auto">
                                            <img class="h-10 w-10 rounded-full" src="{{ $facultyCourse->faculty->avatar ? Storage::url($facultyCourse->faculty->avatar) : asset('images/default_pfp.svg') }}" alt="">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $facultyCourse->faculty->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($isEvaluated)
                                            @php
                                                $evaluation = $evaluations->where('faculty_course_id', $facultyCourse->id)->first();
                                                $overallRating = $evaluation->overall_average;
                                                $fullStars = floor($overallRating);
                                                $hasHalfStar = ($overallRating - $fullStars) >= 0.5;
                                                $starColor = match(true) {
                                                    $overallRating >= 4.5 => '#10b981', // green-500
                                                    $overallRating >= 3.5 => '#3b82f6', // blue-500
                                                    $overallRating >= 2.5 => '#f59e0b', // amber-500
                                                    $overallRating >= 1.5 => '#f97316', // orange-500
                                                    default => '#ef4444', // red-500
                                                };
                                            @endphp
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $fullStars)
                                                            {{-- Full star --}}
                                                            <svg class="w-4 h-4" style="color: #fbbf24;" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @elseif($i == $fullStars + 1 && fmod($overallRating, 1) >= 0.5)
                                                            {{-- Half star --}}
                                                            <div class="relative">
                                                                <svg class="w-4 h-4" style="color: #6b7280;" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                                <div class="absolute inset-0 overflow-hidden w-1/2">
                                                                    <svg class="w-4 h-4" style="color: #fbbf24;" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{-- Empty star --}}
                                                            <svg class="w-4 h-4" style="color: #6b7280;" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-500 text-center sm:text-left">({{ number_format($overallRating, 2) }})</span>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <span class="text-xs text-gray-500">Not yet evaluated</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                        For irregular students, this section will be displayed later. Click the button above to start evaluation .
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800 text-center">
                <p class="text-gray-600 dark:text-gray-400">
                    No active evaluation period
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>