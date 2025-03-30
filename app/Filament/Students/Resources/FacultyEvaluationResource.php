<?php

namespace App\Filament\Students\Resources;

use App\Filament\Students\Resources\FacultyEvaluationResource\Pages;
use App\Filament\Forms\Components\StarRating;
use App\Models\FacultyEvaluation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Contracts\View\View;
use App\Models\FacultyCourse;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class FacultyEvaluationResource extends Resource
{
    protected static ?string $model = FacultyEvaluation::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Faculty Evaluation';

    protected static ?string $modelLabel = 'Faculty Evaluation';

    protected static ?string $title = '';

    public static array $commitmentCriteria = [
        '1. Demonstrates sensitivity to students to attend and absorb content information.',
        '2. Integrates sensitively his/her learning objectives with those of the students in a collaborative process.',
        '3. Make self available to students beyond official time.',
        '4. Regularly comes to class on time, well-groomed and well-prepared to complete assigned responsibilities.',
        '5. Keeps accurate records of students performance and prompt submission of the same.',
    ];

    public static array $knowledgeCriteria = [
        '1. Demonstrates mastery of the subject matter (explain the subject matter without relying solely on the prescribed textbook).',
        '2. Draws and share information on the state of the art of theory and practice in his/her discipline.',
        '3. Integrates subject to practical circumstances and learning intents/purpose of students.',
        '4. Explains the relevance of present topics to the previous lessons, and relates the subject matter to relevant current issues.',
        '5. Demonstrates up-to-date knowledge and/or awareness on current trends and issues of the subject.',
    ];

    public static array $teachingCriteria = [
        '1. Creates teaching strategies that allow students to practice using concepts they need to understand.',
        '2. Enhances student self-esteem and/or gives due recognition to students performance or potential.',
        '3. Allows students to create their own course with objectives and defined student-professor rules.',
        '4. Allows students to think independently and make their own decisions.',
        '5. Encourages students to learn beyond what is required and helps apply the concepts learned.',
    ];

    public static array $managementCriteria = [
        '1. Creates opportunities for intensive and/or extensive contribution of students in class activities.',
        '2. Acts as facilitator, resource person, coach, and mentor in student learning.',
        '3. Designs and implements learning conditions that promote healthy exchange.',
        '4. Structures learning context to enhance attainment of collective learning objectives.',
        '5. Uses instructional materials effectively to reinforce learning processes.',
    ];

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\ImageColumn::make('courseDetails.faculty.avatar')
                            ->label('Avatar')
                            ->circular()
                            ->size(64)
                            ->defaultImageUrl(asset('images/default_pfp.svg'))
                            ->extraAttributes(['class' => 'mx-auto']),
                            
                        Tables\Columns\TextColumn::make('facultyCourse.faculty.name')
                            ->label('Faculty Name')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->alignment('center')
                            ->extraAttributes(['class' => 'mt-2']),
                            
                        Tables\Columns\TextColumn::make('facultyCourse')
                            ->label('Course')
                            ->formatStateUsing(fn ($record) =>
                                "{$record->facultyCourse->course->code} - {$record->facultyCourse->course->name}"
                            )
                            ->size('sm')
                            ->color('gray')
                            ->alignment('center')
                            ->visible(fn () => Auth::guard('students')->user()->student_type === 'irregular'),
                            
                        Tables\Columns\TextColumn::make('rating_period')
                            ->label('Period')
                            ->formatStateUsing(fn ($record) =>
                                $record->rating_period_start->format('M d, Y') . ' - ' .
                                $record->rating_period_end->format('M d, Y')
                            )
                            ->size('sm')
                            ->color('gray')
                            ->alignment('center'),
                    ])->space(2),

                    Tables\Columns\Layout\Grid::make(['default' => 2])
                        ->schema([
                            // First Row
                            Tables\Columns\TextColumn::make('commitment_average')
                                ->label('Commitment')
                                ->alignment('center')
                                ->formatStateUsing(fn ($state) => "
                                    <div class='text-center'>
                                        <div class='text-sm font-medium text-gray-600 dark:text-gray-400 mb-1'>Commitment</div>
                                        <div style='color: " . match(true) {
                                            $state >= 5 => '#10b981',
                                            $state >= 4 => '#3b82f6',
                                            $state >= 3 => '#f59e0b',
                                            $state >= 2 => '#f97316',
                                            default => '#ef4444',
                                        } . ";'>" . str_repeat('★', (int)$state) . str_repeat('☆', 5 - (int)$state) . "</div>
                                    </div>
                                ")->html(),

                            Tables\Columns\TextColumn::make('knowledge_average')
                                ->label('Knowledge')
                                ->alignment('center')
                                ->formatStateUsing(fn ($state) => "
                                    <div class='text-center'>
                                        <div class='text-sm font-medium text-gray-600 dark:text-gray-400 mb-1'>Knowledge</div>
                                        <div style='color: " . match(true) {
                                            $state >= 5 => '#10b981',
                                            $state >= 4 => '#3b82f6',
                                            $state >= 3 => '#f59e0b',
                                            $state >= 2 => '#f97316',
                                            default => '#ef4444',
                                        } . ";'>" . str_repeat('★', (int)$state) . str_repeat('☆', 5 - (int)$state) . "</div>
                                    </div>
                                ")->html(),

                            // Second Row
                            Tables\Columns\TextColumn::make('teaching_average')
                                ->label('Teaching')
                                ->alignment('center')
                                ->formatStateUsing(fn ($state) => "
                                    <div class='text-center'>
                                        <div class='text-sm font-medium text-gray-600 dark:text-gray-400 mb-1'>Teaching</div>
                                        <div style='color: " . match(true) {
                                            $state >= 5 => '#10b981',
                                            $state >= 4 => '#3b82f6',
                                            $state >= 3 => '#f59e0b',
                                            $state >= 2 => '#f97316',
                                            default => '#ef4444',
                                        } . ";'>" . str_repeat('★', (int)$state) . str_repeat('☆', 5 - (int)$state) . "</div>
                                    </div>
                                ")->html(),

                            Tables\Columns\TextColumn::make('management_average')
                                ->label('Management')
                                ->alignment('center')
                                ->formatStateUsing(fn ($state) => "
                                    <div class='text-center'>
                                        <div class='text-sm font-medium text-gray-600 dark:text-gray-400 mb-1'>Management</div>
                                        <div style='color: " . match(true) {
                                            $state >= 5 => '#10b981',
                                            $state >= 4 => '#3b82f6',
                                            $state >= 3 => '#f59e0b',
                                            $state >= 2 => '#f97316',
                                            default => '#ef4444',
                                        } . ";'>" . str_repeat('★', (int)$state) . str_repeat('☆', 5 - (int)$state) . "</div>
                                    </div>
                                ")->html(),
                        ]),

                    // Overall Rating
                    Tables\Columns\TextColumn::make('overall_average')
                        ->label('')
                        ->alignment('center')
                        ->formatStateUsing(fn ($state) => "
                            <div class='text-center mt-4'>
                                <div class='mx-auto w-32 border-t border-gray-200 dark:border-gray-700 mb-4'></div>
                                <div class='text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1'>Overall Rating</div>
                                <div style='color: " . match(true) {
                                    $state >= 5 => '#10b981',
                                    $state >= 4 => '#3b82f6',
                                    $state >= 3 => '#f59e0b',
                                    $state >= 2 => '#f97316',
                                    default => '#ef4444',
                                } . "; font-size: 1.5rem;'>" . str_repeat('★', (int)$state) . str_repeat('☆', 5 - (int)$state) . "</div>
                            </div>
                        ")->html(),
                ])->space(4),
            ])
            ->recordClasses('bg-white dark:bg-gray-900 shadow-sm rounded-lg p-4 ring-1 ring-gray-950/5 dark:ring-gray-800 hover:shadow-md transition-shadow duration-200')
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->icon('heroicon-m-eye')
                    ->tooltip('View Details')
                    ->color('gray')
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([])
            ->nextAction(
                fn (Action $action) => $action->label('Next step')
                ->icon('fluentui-next-20-o'),
            )
            ->previousAction(
                fn (Action $action) => $action->label('Previous step')
                ->icon('fluentui-previous-20-o'),
            )
                ->hidden(fn () => !auth()->guard('students')->check())
                ->submitAction(
                    \Filament\Forms\Components\Actions\Action::make('submit')
                        ->label('Submit Evaluation')
                        ->requiresConfirmation()
                        ->modalHeading('Evaluation Summary')
                        ->modalDescription(function ($action) {
                            $data = $action->getLivewire()->form->getState();
                            $summary = "Please review your ratings before submitting:\n\n";
                            $summary .= "Faculty: " . FacultyCourse::find($data['faculty_course_id'])->faculty->name . "\n\n";
                            
                            // Add ratings with stars
                            foreach (['A', 'B', 'C', 'D'] as $section) {
                                $prefix = strtolower($section);
                                $summary .= "$section. " . match($section) {
                                    'A' => 'Commitment',
                                    'B' => 'Knowledge of Subject',
                                    'C' => 'Teaching for Independent Learning',
                                    'D' => 'Management of Learning',
                                } . "\n";
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    $field = match($prefix) {
                                        'a' => "a{$i}_" . static::getFieldSuffix('commitment', $i - 1),
                                        'b' => "b{$i}_" . static::getFieldSuffix('knowledge', $i - 1),
                                        'c' => "c{$i}_" . static::getFieldSuffix('teaching', $i - 1),
                                        'd' => "d{$i}_" . static::getFieldSuffix('management', $i - 1),
                                    };
                                    $stars = str_repeat('★', $data[$field]);
                                    $criteria = match($prefix) {
                                        'a' => static::$commitmentCriteria,
                                        'b' => static::$knowledgeCriteria,
                                        'c' => static::$teachingCriteria,
                                        'd' => static::$managementCriteria,
                                    };
                                    $summary .= "$i. " . substr($criteria[$i - 1], 3) . ": $stars\n";
                                }
                                $summary .= "\n";
                            }
                            
                            $summary .= "Comments:\n" . ($data['comments'] ?? 'No comments provided');
                            
                            return $summary;
                        })
                        ->action('create')
                        ->modalSubmitActionLabel('Submit Evaluation')
                        ->modalCancelActionLabel('Back to Form')
                )
                ->schema([
                Wizard\Step::make('Faculty Details')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Group::make([
                                    \Filament\Forms\Components\Placeholder::make('rating_guide')
                                        ->content(fn () => new HtmlString('
                                            <div class="space-y-4">
                                                <div class="text-sm text-gray-600 dark:text-gray-400 italic mb-4">
                                                    Please read the rating guide carefully before submitting your evaluation.
                                                </div>
                                                
                                                <div class="font-medium mb-4">Rating Scale:</div>
                                                
                                                <div class="space-y-4">
                                                    <!-- Outstanding -->
                                                    <div class="space-y-1">
                                                        <div style="color: #10b981; font-size: 2rem;">★★★★★</div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            Outstanding - The faculty always exceeds job requirements
                                                        </div>
                                                    </div>
                                    
                                                    <!-- Very Satisfactory -->
                                                    <div class="space-y-1">
                                                        <div style="color: #3b82f6; font-size: 2rem;">★★★★<span style="color: #e5e7eb;">☆</span></div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            Very Satisfactory - Performance meets and often exceeds job requirements
                                                        </div>
                                                    </div>
                                    
                                                    <!-- Satisfactory -->
                                                    <div class="space-y-1">
                                                        <div style="color: #f59e0b; font-size: 2rem;">★★★<span style="color: #e5e7eb;">☆☆</span></div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            Satisfactory - Performance meets job requirements
                                                        </div>
                                                    </div>
                                    
                                                    <!-- Fair -->
                                                    <div class="space-y-1">
                                                        <div style="color: #f97316; font-size: 2rem;">★★<span style="color: #e5e7eb;">☆☆☆</span></div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            Fair - Performance needs some development
                                                        </div>
                                                    </div>
                                    
                                                    <!-- Poor -->
                                                    <div class="space-y-1">
                                                        <div style="color: #ef4444; font-size: 2rem;">★<span style="color: #e5e7eb;">☆☆☆☆</span></div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            Poor - Fails to meet job requirements
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        '))
                                        ->columnSpan(1),
                                ])->columnSpan(1),

                                Group::make([
                                    Select::make('faculty_course_id')
                                        ->label('Select Faculty to Evaluate')
                                        ->options(function() {
                                            $student = Auth::guard('students')->user();
                                            
                                            // Get faculty IDs that this student has already evaluated
                                            $evaluatedFacultyIds = FacultyEvaluation::query()
                                                ->where('student_id', $student->id)
                                                ->whereHas('facultyCourse', function ($query) {
                                                    $query->whereHas('evaluationPeriod', function ($q) {
                                                        $q->whereHas('semester', function ($s) {
                                                            $s->where('status', 'active');
                                                        });
                                                    });
                                                })
                                                ->pluck('faculty_course_id')
                                                ->toArray();

                                            // Get available faculty courses
                                            $query = FacultyCourse::query()
                                                ->join('users', 'faculty_courses.faculty_id', '=', 'users.id')
                                                ->join('courses', 'faculty_courses.course_id', '=', 'courses.id')
                                                ->join('evaluation_periods', 'faculty_courses.evaluation_period_id', '=', 'evaluation_periods.id')
                                                ->where('users.department_id', $student->department_id)
                                                ->where('users.is_active', true)
                                                ->when($student->student_type === 'regular', function($query) use ($student) {
                                                    $query->where('courses.year_level_id', $student->year_level_id);
                                                })
                                                ->where('evaluation_periods.status', 'active')
                                                ->whereNotIn('faculty_courses.id', $evaluatedFacultyIds)
                                                ->select('faculty_courses.*')
                                                ->with([
                                                    'faculty',
                                                    'course.yearLevel',
                                                    'evaluationPeriod.semester'
                                                ]);

                                            $facultyCourses = $query->get();

                                            // Ensure we have faculty courses before proceeding
                                            if ($facultyCourses->isEmpty()) {
                                                return [];
                                            }

                                            $options = [];

                                            // Group faculty courses by faculty for regular students
                                            if ($student->student_type === 'regular') {
                                                foreach ($facultyCourses as $facultyCourse) {
                                                    if ($facultyCourse->faculty && $facultyCourse->id) {
                                                        $options[$facultyCourse->id] = $facultyCourse->faculty->name;
                                                    }
                                                }
                                            } else {
                                                // Show detailed course information for irregular students
                                                foreach ($facultyCourses as $facultyCourse) {
                                                    if ($facultyCourse->faculty && $facultyCourse->course && $facultyCourse->id) {
                                                        $faculty = $facultyCourse->faculty;
                                                        $course = $facultyCourse->course;
                                                        $yearLevel = $course->yearLevel?->name ?? 'Unknown Year';
                                                        $courseCode = $course->code ?? 'Unknown Course';
                                                        $courseName = $course->name ?? '';
                                                        
                                                        $options[$facultyCourse->id] = "{$faculty->name} - {$courseCode} {$courseName} ({$yearLevel})";
                                                    }
                                                }
                                            }

                                            return $options;
                                        })
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Choose a faculty member to evaluate')
                                        ->helperText(function() {
                                            $student = Auth::guard('students')->user();
                                            if ($student->student_type === 'regular') {
                                                return 'Only showing faculty members teaching courses in your year level that you haven\'t evaluated yet';
                                            }
                                            return 'Showing all faculty members in your department with their courses that you haven\'t evaluated yet';
                                        })
                                ])->columnSpan(1),
                            ])->columns(2),
                    ]),

                Wizard\Step::make('A. Commitment')
                    ->icon('heroicon-o-heart')
                    ->schema(static::makeEvaluationRadios('commitment', static::$commitmentCriteria)),

                Wizard\Step::make('B. Knowledge of Subject')
                    ->icon('heroicon-o-book-open')
                    ->schema(static::makeEvaluationRadios('knowledge', static::$knowledgeCriteria)),

                Wizard\Step::make('C. Teaching for Independent Learning')
                    ->icon('heroicon-o-light-bulb')
                    ->schema(static::makeEvaluationRadios('teaching', static::$teachingCriteria)),

                Wizard\Step::make('D. Management of Learning')
                    ->icon('heroicon-o-cog')
                    ->schema(static::makeEvaluationRadios('management', static::$managementCriteria)),

                Wizard\Step::make('Comments')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Textarea::make('comments')
                                    ->label('Additional Comments')
                                    ->placeholder('Enter any additional comments or feedback')
                                    ->required()
                                    ->minLength(10)
                                    ->rows(5)
                                    ->columnSpanFull(),
                                    
                                    SignaturePad::make('signature')
                                    ->label(__('Sign here'))
                                    ->dotSize(2.0)
                                    ->lineMinWidth(0.5)
                                    ->lineMaxWidth(2.5)
                                    ->throttle(16)
                                    ->minDistance(5)
                                    ->velocityFilterWeight(0.7)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    protected static function makeEvaluationRadios(string $prefix, array $criteria): array
    {
        $prefixMap = [
            'commitment' => 'a',
            'knowledge' => 'b',
            'teaching' => 'c',
            'management' => 'd',
        ];

        return [
            Grid::make()
                ->schema(
                    collect($criteria)
                        ->map(fn ($label, $index) =>
                            Group::make([
                                \Filament\Forms\Components\Placeholder::make($prefixMap[$prefix] . ($index + 1) . '_question')
                                    ->content($label)
                                    ->extraAttributes(['class' => 'text-sm text-center font-medium mb-4'])
                                    ->columnSpanFull(),
                                    
                                StarRating::make($prefixMap[$prefix] . ($index + 1) . '_' . static::getFieldSuffix($prefix, $index))
                                    ->name($prefixMap[$prefix] . ($index + 1) . '_' . static::getFieldSuffix($prefix, $index))
                                    ->label('')
                                    ->min(1)
                                    ->max(5)
                                    ->required()
                                    ->columnSpanFull()
                            ])
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6'])
                            ->columnSpanFull()
                        )
                        ->toArray()
                )->columns(1),
        ];
    }

    protected static function getFieldSuffix(string $prefix, int $index): string
    {
        $suffixMap = [
            'commitment' => [
                0 => 'demonstrates_sensitivity',
                1 => 'integrates_learning_objectives',
                2 => 'makes_self_available',
                3 => 'comes_to_class_prepared',
                4 => 'keeps_accurate_records',
            ],
            'knowledge' => [
                0 => 'demonstrates_mastery',
                1 => 'draws_information',
                2 => 'integrates_subject',
                3 => 'explains_relevance',
                4 => 'demonstrates_up_to_date',
            ],
            'teaching' => [
                0 => 'creates_teaching_strategies',
                1 => 'enhances_self_esteem',
                2 => 'allows_student_creation',
                3 => 'allows_independent_thinking',
                4 => 'encourages_extra_learning',
            ],
            'management' => [
                0 => 'creates_opportunities',
                1 => 'assumes_various_roles',
                2 => 'designs_learning',
                3 => 'structures_learning',
                4 => 'uses_instructional_materials',
            ],
        ];

        return $suffixMap[$prefix][$index];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('student_id', Auth::guard('students')->id())
            ->whereHas('facultyCourse.evaluationPeriod', function ($query) {
                $query->where('status', 'active');
            })
            ->with([
                'facultyCourse' => fn($query) => $query->withTrashed(),
                'facultyCourse.faculty:id,name,avatar',
                'facultyCourse.course',
                'facultyCourse.evaluationPeriod.semester'
            ])
            ->orderBy('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacultyEvaluations::route('/'),
            'create' => Pages\CreateFacultyEvaluation::route('/create'),
            'view' => Pages\ViewFacultyEvaluation::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('student_id', Auth::guard('students')->id())->count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
