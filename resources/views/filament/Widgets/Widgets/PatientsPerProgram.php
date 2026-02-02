<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Program;

class PatientsPerProgram extends Widget
{
    protected static string $view = 'filament.widgets.patients-per-program';

    protected function getViewData(): array
    {
        return [
            'programs' => Program::withCount('patients')->get(),
        ];
    }
}
