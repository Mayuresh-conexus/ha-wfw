<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Filament\Resources\RecordResource;
use App\Models\Record;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Patient record updated successfully.';
    }

    protected function getHeaderActions(): array
    {
        $patientId = $this->getRecord()->getKey();
        $recordCount = Record::where('patientid', $patientId)->count();

        return [
            // Jump straight to this patient's clinical record(s) while reviewing
            // their details: one record opens directly, many open a filtered list.
            Actions\Action::make('viewRecords')
                ->label($recordCount === 1 ? 'View Record' : 'View Records')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->visible($recordCount > 0)
                ->url(function () use ($patientId, $recordCount): string {
                    if ($recordCount === 1) {
                        $recordId = Record::where('patientid', $patientId)->value('id');

                        return RecordResource::getUrl('edit', ['record' => $recordId]);
                    }

                    return RecordResource::getUrl('index', [
                        'tableFilters' => ['patientid' => ['value' => $patientId]],
                    ]);
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
