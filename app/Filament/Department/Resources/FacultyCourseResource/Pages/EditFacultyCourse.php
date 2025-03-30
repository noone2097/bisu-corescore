<?php

namespace App\Filament\Department\Resources\FacultyCourseResource\Pages;

use App\Filament\Department\Resources\FacultyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Course;
use App\Models\YearLevel;
use App\Models\EvaluationPeriod;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use App\Traits\HasBackUrl;
use Filament\Notifications\Notification;

class EditFacultyCourse extends EditRecord
{
    use HasBackUrl;
    protected static string $resource = FacultyCourseResource::class;
    
    public function mount($record): void
    {
        parent::mount($record);
        
        $evaluationPeriod = EvaluationPeriod::where('status', 'active')->first();
        if (!$evaluationPeriod) {
            Notification::make()
                ->warning()
                ->title('Cannot Save Changes')
                ->body('Evaluation period is not active. Please wait for an active evaluation period.')
                ->send();
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['course_ids'] = $this->record->facultyCourses->pluck('course_id')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $evaluationPeriod = EvaluationPeriod::where('status', 'active')->first();
        if ($evaluationPeriod) {
            $existingCourseIds = $this->record->facultyCourses()
                ->withTrashed()
                ->where('evaluation_period_id', $evaluationPeriod->id)
                ->pluck('course_id')
                ->toArray();

            $newCourseIds = array_diff($this->data['course_ids'], $existingCourseIds);

            foreach ($newCourseIds as $courseId) {
                $existingSoftDeleted = $this->record->facultyCourses()
                    ->withTrashed()
                    ->where('evaluation_period_id', $evaluationPeriod->id)
                    ->where('course_id', $courseId)
                    ->first();

                if ($existingSoftDeleted) {
                    $existingSoftDeleted->restore();
                } else {
                    $this->record->facultyCourses()->create([
                        'course_id' => $courseId,
                        'evaluation_period_id' => $evaluationPeriod->id,
                    ]);
                }
            }

            $this->record->facultyCourses()
                ->where('evaluation_period_id', $evaluationPeriod->id)
                ->whereNotIn('course_id', $this->data['course_ids'])
                ->delete();

            $this->record->refresh();
            
            $this->dispatch('filament.coursesUpdated');
        }
    }

    protected function getFormActions(): array
    {
        $evaluationPeriod = EvaluationPeriod::where('status', 'active')->first();

        if (!$evaluationPeriod) {
            return [];
        }

        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\Section::make('Faculty Information')
                ->schema([
                    Components\Grid::make(12)
                        ->schema([
                            Components\Group::make([
                                FileUpload::make('avatar')
                                    ->image()
                                    ->avatar()
                                    ->disk('public')
                                    ->disabled()
                                    ->hiddenLabel()
                                    ->imageEditor(false)
                                    ->deletable(false)
                                    ->imagePreviewHeight('40')
                                    ->default(asset('images/default_pfp.svg')),
                            ])
                            ->columnSpan(['lg' => 5])
                            ->extraAttributes(['class' => 'flex justify-center px-4 py-8']),
                            
                            Components\Group::make([
                                Components\Placeholder::make('name')
                                    ->label('Faculty Name')
                                    ->content(fn () => $this->record->name)
                                    ->extraAttributes(['class' => 'font-medium']),
                                Components\Placeholder::make('email')
                                    ->label('Email')
                                    ->content(fn () => $this->record->email)
                                    ->extraAttributes(['class' => 'font-medium']),
                                Components\Placeholder::make('department')
                                    ->label('Department')
                                    ->content(fn () => $this->record->department->name)
                                    ->extraAttributes(['class' => 'font-medium']),
                            ])
                            ->columnSpan(['lg' => 7])
                            ->extraAttributes(['class' => 'space-y-2 px-4']),
                        ])
                        ->extraAttributes(['class' => 'items-center']),
                ])
                ->collapsible()
                ->compact()
                ->columnSpan(2),

            Components\Section::make('Assigned Courses')
                ->description('Select the courses to assign to this faculty member')
                ->schema([
                    Components\Select::make('course_ids')
                        ->label('Courses')
                        ->multiple()
                        ->options(function () {
                            $evaluationPeriod = EvaluationPeriod::where('status', 'active')->first();

                            $courses = Course::query()
                                ->where('department_id', auth()->user()->department_id)
                                ->with('yearLevel')
                                ->when($evaluationPeriod, function ($query) use ($evaluationPeriod) {
                                    return $query->whereDoesntHave('facultyAssignments', function ($query) use ($evaluationPeriod) {
                                        $query->where('evaluation_period_id', $evaluationPeriod->id)
                                            ->where('faculty_id', '!=', $this->record->id)
                                            ->whereNull('deleted_at');
                                    });
                                })
                                ->orderBy('year_level_id')
                                ->orderBy('code')
                                ->get()
                                ->groupBy('year_level_id');

                            $yearLevels = YearLevel::all();
                            
                            $options = [];
                            foreach ($yearLevels as $yearLevel) {
                                $yearCourses = $courses->get($yearLevel->id, collect());
                                if ($yearCourses->isNotEmpty()) {
                                    $yearLabel = $yearLevel->name . ' Courses';
                                    
                                    $yearOptions = [];
                                    foreach ($yearCourses as $course) {
                                        $yearOptions[$course->id] = "{$course->code} - {$course->name}";
                                    }
                                    
                                    $options[$yearLabel] = $yearOptions;
                                }
                            }
                            
                            return $options;
                        })
                        ->searchable()
                        ->optionsLimit(9999)
                        ->required()
                        ->preload()
                        ->native(false)
                        ->placeholder('Select courses')
                        ->columnSpanFull()
                ])
                ->columnSpan(2),
        ])->columns(4);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
