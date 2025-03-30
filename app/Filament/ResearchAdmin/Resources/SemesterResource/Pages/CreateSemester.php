<?php

namespace App\Filament\ResearchAdmin\Resources\SemesterResource\Pages;

use App\Filament\ResearchAdmin\Resources\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;

class CreateSemester extends CreateRecord
{
    use HasBackUrl;
    protected static string $resource = SemesterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check for academic year uniqueness
        $academicYearCheck = \App\Models\Semester::academicYearExists(
            $data['academic_year'],
            $data['type']
        );
        
        if ($academicYearCheck['exists']) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body($academicYearCheck['message'])
                ->send();
            $this->halt();
        }

        // Check for date overlaps
        $overlap = \App\Models\Semester::hasDateOverlap(
            $data['start_date'],
            $data['end_date']
        );
        
        if ($overlap['exists']) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body($overlap['message'])
                ->send();
            $this->halt();
        }

        // Check for active semester if setting this one as active
        if ($data['is_active']) {
            $active = \App\Models\Semester::hasActive();
            if ($active['exists']) {
                \Filament\Notifications\Notification::make()
                    ->danger()
                    ->title('Validation Error')
                    ->body($active['message'])
                    ->send();
                $this->halt();
            }
        }

        // Check for same dates
        $startDate = $data['start_date'] instanceof \DateTime ? $data['start_date'] : new \DateTime($data['start_date']);
        $endDate = $data['end_date'] instanceof \DateTime ? $data['end_date'] : new \DateTime($data['end_date']);
        
        if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body('Start date and end date cannot be the same.')
                ->send();
            $this->halt();
        }

        return $data;
    }
}