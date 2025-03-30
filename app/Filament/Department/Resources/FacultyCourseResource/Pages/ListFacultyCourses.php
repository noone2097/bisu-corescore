<?php

namespace App\Filament\Department\Resources\FacultyCourseResource\Pages;

use App\Filament\Department\Resources\FacultyCourseResource;
use App\Models\FacultyCourse;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListFacultyCourses extends ListRecords
{
    protected static string $resource = FacultyCourseResource::class;

    protected $listeners = ['filament.coursesUpdated' => '$refresh'];

    public function removeAssignment($facultyId, $facultyCourseId): void
    {
        $facultyCourse = FacultyCourse::where('id', $facultyCourseId)
            ->where('faculty_id', $facultyId)
            ->first();
        
        if (!$facultyCourse) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->description('Course assignment not found.')
                ->send();
            return;
        }

        try {
            $facultyCourse->delete(); // This will soft delete

            Notification::make()
                ->success()
                ->title('Course Unassigned')
                ->description('The course has been unassigned successfully.')
                ->send();

            $this->resetPage();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->description('Failed to unassign course. Please try again.')
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
