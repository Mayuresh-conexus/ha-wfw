<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\RecordResource;
use App\Models\Record;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Live feed of the most recent clinical records — patient, type, status and
 * owning volunteer, each row deep-linking to the record.
 */
class RecentRecordsTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Clinical Records';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Record::query()->with(['patient', 'volunteer'])->latest()
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->recordUrl(fn (Record $record): ?string => RecordResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Patient')
                    ->weight('medium')
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->placeholder('—'),

                TextColumn::make('record_type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => self::statusColor($state))
                    ->placeholder('—'),

                TextColumn::make('volunteer.name')
                    ->label('Volunteer')
                    ->color('gray')
                    ->placeholder('Unassigned'),

                TextColumn::make('created_at')
                    ->label('Logged')
                    ->since()
                    ->sortable()
                    ->tooltip(fn (Record $record): string => $record->created_at?->toDayDateTimeString() ?? ''),
            ])
            ->emptyStateHeading('No clinical records yet')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function statusColor(?string $state): string
    {
        return match (strtolower((string) $state)) {
            'completed', 'done', 'closed', 'resolved', 'reviewed', 'approved' => 'success',
            'in_progress', 'in progress', 'processing', 'submitted', 'open' => 'info',
            'pending', 'new', 'draft', 'awaiting' => 'warning',
            'cancelled', 'canceled', 'rejected', 'failed' => 'danger',
            default => 'gray',
        };
    }
}
