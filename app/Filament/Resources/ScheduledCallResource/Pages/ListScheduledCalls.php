<?php

namespace App\Filament\Resources\ScheduledCallResource\Pages;

use App\Filament\Resources\ScheduledCallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScheduledCalls extends ListRecords
{
    protected static string $resource = ScheduledCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
