<?php

namespace App\Filament\Resources\HealthReasonResource\Pages;

use App\Filament\Resources\HealthReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHealthReason extends EditRecord
{
    protected static string $resource = HealthReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
