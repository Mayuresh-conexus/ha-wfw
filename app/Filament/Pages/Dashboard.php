<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Custom operations console. Instead of Filament's flat widget grid, the view
 * composes the widgets into labelled zones (Overview → Trends → System Health)
 * for an enterprise control-plane layout.
 */
class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Console';

    protected static ?string $title = 'Operations Console';

    protected static string $view = 'filament.pages.dashboard';

    public function getSubheading(): ?string
    {
        return 'Real-time overview of patients, clinical activity and system health.';
    }
}
