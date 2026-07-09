<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use App\Services\ScheduledCallService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CallsRelationManager extends RelationManager
{
    protected static string $relationship = 'calls';
    protected static ?string $recordTitleAttribute = 'room_name';

    public function form(Form $form): Form
    {
        return $form->schema(
            app(ScheduledCallService::class)->formSchema($this->getOwnerRecord())
        );
    }

    public function table(Table $table): Table
    {
        $service = app(ScheduledCallService::class);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
    ->label('Patient')
    ->sortable()
    ->searchable(),

                Tables\Columns\TextColumn::make('volunteer.name')->label('Volunteer')->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')->label('GP / Doctor')->sortable(),
                Tables\Columns\TextColumn::make('schedule_date')->date()->label('Date')->sortable(),
                Tables\Columns\TextColumn::make('schedule_start_time')->label('Start'),
                Tables\Columns\TextColumn::make('schedule_end_time')->label('End'),
                Tables\Columns\TextColumn::make('zoom_join_url')
                    ->label('Zoom')
                    ->formatStateUsing(fn ($state) => $state ? 'Join' : '-')
                    ->url(fn ($record) => $record->zoom_join_url, true)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('zoom_start_url')
                    ->label('Host')
                    ->formatStateUsing(fn ($state) => $state ? 'Start' : '-')
                    ->url(fn ($record) => $record->zoom_start_url, true)
                    ->openUrlInNewTab()
                    ->visible(fn () => auth()->user()?->hasRole('admin')),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $service->applyInheritedFields($data, $this->getOwnerRecord()))
                    ->after(fn ($record) => $service->afterCallSaved($record)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $service->applyInheritedFields($data, $this->getOwnerRecord()))
                    ->after(fn ($record) => $service->afterCallSaved($record)),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
