<?php

namespace App\Filament\Resources\BodySectionResource\Pages;

use App\Filament\Resources\BodySectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBodySections extends ListRecords
{
    protected static string $resource = BodySectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
