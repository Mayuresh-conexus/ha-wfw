<?php

namespace App\Filament\Resources\RecordResource\Pages;

use App\Filament\Resources\RecordResource;
use App\Services\ScheduledCallService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;

class EditRecord extends BaseEditRecord
{
    protected static string $resource = RecordResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Medical record updated successfully.';
    }

    /**
     * `patient_medicationupload` is a cross-model field (see RecordResource::form()):
     * it displays/edits the record's patient's `medicationupload` column, not a
     * column on `records`. Save it to the patient here, then strip it so it's
     * never passed to `$record->fill()`.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('patient_medicationupload', $data)) {
            $this->record->patient?->update([
                'medicationupload' => $data['patient_medicationupload'] ?? [],
            ]);

            unset($data['patient_medicationupload']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        $service = app(ScheduledCallService::class);

        return [
            // Same scheduling flow as the Calls tab below, surfaced at the top
            // of the page so a reviewer doesn't have to scroll to schedule a call.
            Actions\Action::make('scheduleCall')
                ->label('Schedule Call')
                ->icon('heroicon-o-phone')
                ->color('primary')
                ->form($service->formSchema($this->record))
                ->action(function (array $data) use ($service) {
                    $data = $service->applyInheritedFields($data, $this->record);
                    $call = $this->record->calls()->create($data);
                    $service->afterCallSaved($call);
                }),

            // Visible once a call for this record has been completed, so the
            // reviewer can explicitly close out the record's clinical status.
            Actions\Action::make('markTreatmentComplete')
                ->label('Mark Treatment Complete')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('This will set the patient status on this record to "Treatment Complete".')
                ->visible(fn () => $this->record->calls()->where('status', 'completed')->exists()
                    && $this->record->patient_status !== 'Treatment Complete')
                ->action(fn () => $this->record->update(['patient_status' => 'Treatment Complete'])),

            Actions\DeleteAction::make(),
        ];
    }
}
