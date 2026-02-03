<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Project;
use App\Models\Record;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Patients', Patient::count()),

            Stat::make('Active Projects',
                Project::where('is_active', true)->count()
            ),

            Stat::make(
                'Records This Month',
                Record::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ),
        ];
    }
}
