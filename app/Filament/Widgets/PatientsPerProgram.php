<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Program;

class PatientsPerProgram extends BaseWidget
{
    protected function getCards(): array
    {
        // Fetch all programs with patient count
        $programs = Program::withCount('patients')->get();

        // Create a card for each program
        return $programs->map(fn($program) => Card::make(
            $program->name,
            $program->patients_count
        )->description('Registered Patients'))->toArray();
    }
}
