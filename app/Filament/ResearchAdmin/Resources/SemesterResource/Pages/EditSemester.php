<?php

namespace App\Filament\ResearchAdmin\Resources\SemesterResource\Pages;

use App\Filament\ResearchAdmin\Resources\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;

class EditSemester extends EditRecord
{
    use HasBackUrl;
    protected static string $resource = SemesterResource::class;

    protected function mutateFormDataBeforeUpdate(array $data): array
    {
        // Check for date overlaps
        $overlap = \App\Models\Semester::hasDateOverlap(
            $data['start_date'],
            $data['end_date'],
            $this->record->id
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
            $active = \App\Models\Semester::hasActive($this->record->id);
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
        if ($data['start_date']->format('Y-m-d') === $data['end_date']->format('Y-m-d')) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Validation Error')
                ->body('Start date and end date cannot be the same.')
                ->send();
            $this->halt();
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}