<?php

namespace App\Filament\Widgets;

use App\Models\ScheduledCall;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Upcoming scheduled calls queue — the next teleconsults to be run, with
 * patient, slot, assigned volunteer and GP.
 */
class UpcomingCallsTable extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Scheduled Calls';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ScheduledCall::query()
                    ->with(['patient', 'volunteer', 'doctor'])
                    ->whereDate('schedule_date', '>=', today())
                    ->orderBy('schedule_date')
                    ->orderBy('schedule_start_time')
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Patient')
                    ->weight('medium')
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->placeholder('—'),

                TextColumn::make('schedule_date')
                    ->label('Date')
                    ->date('M j, Y')
                    ->sortable(),

                TextColumn::make('schedule_start_time')
                    ->label('Time')
                    ->formatStateUsing(fn (?string $state): string => $state ? \Illuminate\Support\Carbon::parse($state)->format('g:i A') : '—'),

                TextColumn::make('volunteer.name')
                    ->label('Volunteer')
                    ->color('gray')
                    ->placeholder('Unassigned'),

                TextColumn::make('doctor.name')
                    ->label('GP / Doctor')
                    ->color('gray')
                    ->placeholder('Unassigned'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => RecentRecordsTable::statusColor($state))
                    ->placeholder('Scheduled'),
            ])
            ->emptyStateHeading('No upcoming calls')
            ->emptyStateDescription('Scheduled teleconsults will appear here.')
            ->emptyStateIcon('heroicon-o-phone');
    }
}
