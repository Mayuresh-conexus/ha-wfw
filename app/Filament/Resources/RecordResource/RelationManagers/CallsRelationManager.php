<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\ZoomService;
use Illuminate\Support\Carbon;


class CallsRelationManager extends RelationManager
{
    protected static string $relationship = 'calls';
    protected static ?string $recordTitleAttribute = 'room_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Patient dropdown
                Forms\Components\Select::make('patientid')
                ->label('Patient')
                ->options(\App\Models\Patient::pluck('name', 'id'))
                ->searchable()
                ->required(),

                // Volunteer dropdown (only users with "volunteer" role)
                Forms\Components\Select::make('volunteer_id')
                    ->label('Volunteer')
                    ->options(function () {
                        return User::role('volunteer')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                // Doctor dropdown (only users with "doctor" or "gp" role)
                Forms\Components\Select::make('assigned_gp_doctor_id')
                    ->label('GP / Doctor')
                    ->options(function () {
                        return User::role(['doctor', 'gp'])->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('schedule_date')
                    ->label('Schedule Date')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_start_time')
                    ->label('Start Time')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_end_time')
                    ->label('End Time')
                    ->required(),

                Forms\Components\TextInput::make('room_name')
                    ->label('Room Name')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
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
                    ->after(function ($record) {
                        $this->createZoomMeeting($record);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        $this->createZoomMeeting($record);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected function createZoomMeeting($record): void
{
    $tz = config('app.timezone');

    $start = Carbon::parse($record->schedule_date . ' ' . $record->schedule_start_time, $tz);
    $end = Carbon::parse($record->schedule_date . ' ' . $record->schedule_end_time, $tz);

    $duration = max(1, $start->diffInMinutes($end));

    $payload = [
        'topic' => 'Scheduled Call: ' . ($record->room_name ?? 'Call'),
        'type' => 2,
        'start_time' => $start->toIso8601String(),
        'duration' => $duration,
        'timezone' => $tz,
        'settings' => [
            'join_before_host' => true,
            'waiting_room' => false,
            'approval_type' => 2,
        ],
    ];

    $zoom = app(ZoomService::class);
    $meeting = $zoom->createMeeting($payload);

    $record->update([
        'zoom_meeting_id' => (string) ($meeting['id'] ?? null),
        'zoom_join_url' => $meeting['join_url'] ?? null,
        'zoom_start_url' => $meeting['start_url'] ?? null,
    ]);
}

}
