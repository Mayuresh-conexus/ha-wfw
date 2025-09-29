<?php

namespace App\Filament\Resources\HealthReasonResource\Pages;

use App\Filament\Resources\HealthReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHealthReasons extends ListRecords
{
    protected static string $resource = HealthReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
