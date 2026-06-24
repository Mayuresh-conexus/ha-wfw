<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Mail;
use App\Mail\CallScheduled;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\ZoomService;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;


class CallsRelationManager extends RelationManager
{
    protected static string $relationship = 'calls';
    protected static ?string $recordTitleAttribute = 'room_name';

    public function form(Form $form): Form
    {
        // Patient, volunteer and GP/doctor are inherited from the parent record:
        // shown read-only here and written server-side (see resolveAssignments()).
        $assigned = $this->resolveAssignments($this->getOwnerRecord());

        return $form
            ->schema([
                // Patient — inherited from the record (read-only)
                Forms\Components\Select::make('patientid')
                    ->label('Patient')
                    ->options(Patient::whereKey($assigned['patientid'])->pluck('name', 'id'))
                    ->default($assigned['patientid'])
                    ->disabled()
                    ->dehydrated(false),

                // Volunteer — inherited from the record (read-only)
                Forms\Components\Select::make('volunteer_id')
                    ->label('Volunteer')
                    ->options(User::whereKey($assigned['volunteer_id'])->pluck('name', 'id'))
                    ->default($assigned['volunteer_id'])
                    ->disabled()
                    ->dehydrated(false),

                // GP / Doctor — prefilled from the record's first GP/doctor, editable
                Forms\Components\Select::make('assigned_gp_doctor_id')
                    ->label('GP / Doctor')
                    ->options(fn () => User::role(['doctor', 'gp'])->pluck('name', 'id'))
                    ->default($assigned['assigned_gp_doctor_id'])
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a clinician'),

                Forms\Components\DatePicker::make('schedule_date')
                    ->label('Schedule Date')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->required(),

                Forms\Components\TimePicker::make('schedule_end_time')
                    ->label('End Time')
                    ->seconds(false)
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

    /**
     * Resolve a call's inherited fields from the parent record. Patient and
     * volunteer are read-only on the form; the GP/doctor is prefilled with the
     * record's first GP (else first doctor) but stays editable.
     *
     * @return array{patientid: int|null, volunteer_id: int|null, assigned_gp_doctor_id: int|null}
     */
    protected function resolveAssignments($record): array
    {
        return [
            'patientid' => $record?->patientid,
            'volunteer_id' => $record?->volunteerid,
            'assigned_gp_doctor_id' => collect($record?->gpid ?? [])->first()
                ?? collect($record?->doctorid ?? [])->first(),
        ];
    }

    /**
     * Force patient & volunteer from the parent record before saving (those
     * fields are read-only on the form). The GP/doctor stays as chosen.
     */
    protected function applyInheritedFields(array $data): array
    {
        $owner = $this->getOwnerRecord();

        $data['patientid'] = $owner?->patientid;
        $data['volunteer_id'] = $owner?->volunteerid;

        return $data;
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
                    ->mutateFormDataUsing(fn (array $data): array => $this->applyInheritedFields($data))
                    ->after(function ($record) {
                        $this->afterCallSaved($record);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $this->applyInheritedFields($data))
                    ->after(function ($record) {
                        $this->afterCallSaved($record);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    /**
     * Runs after a call is created/updated. Each side-effect is isolated so a
     * failure (e.g. Zoom not configured, mail down) never 500s the request —
     * the call itself is already saved at this point.
     */
    protected function afterCallSaved($record): void
    {
        $this->tryCreateZoomMeeting($record);
        $this->sendCallNotifications($record);
    }

    protected function tryCreateZoomMeeting($record): void
    {
        $zoom = app(ZoomService::class);

        // Zoom is optional — skip cleanly (no exception) when credentials are absent.
        if (! $zoom->isConfigured()) {
            Notification::make()
                ->title('Call scheduled')
                ->body('No Zoom meeting was created because Zoom API credentials are not configured.')
                ->warning()
                ->send();

            return;
        }

        try {
            $tz = config('app.timezone');

            $start = Carbon::parse($record->schedule_date . ' ' . $record->schedule_start_time, $tz);
            $end = Carbon::parse($record->schedule_date . ' ' . $record->schedule_end_time, $tz);

            $duration = max(1, $start->diffInMinutes($end));

            $meeting = $zoom->createMeeting([
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
            ]);

            $record->update([
                'zoom_meeting_id' => (string) ($meeting['id'] ?? null),
                'zoom_join_url' => $meeting['join_url'] ?? null,
                'zoom_start_url' => $meeting['start_url'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Zoom meeting not created')
                ->body('The call was scheduled, but the Zoom meeting could not be created. ' . $e->getMessage())
                ->warning()
                ->send();
        }
    }

    /**
     * Email the patient, volunteer and assigned GP/doctor. Never throws.
     */
    protected function sendCallNotifications($record): void
    {
        try {
            $emails = [];

            if (! empty($record->patientid)) {
                $patient = Patient::find($record->patientid);
                if ($patient && ! empty($patient->email)) {
                    $emails[] = $patient->email;
                }
            }

            if (! empty($record->volunteer_id)) {
                $volunteer = User::find($record->volunteer_id);
                if ($volunteer && ! empty($volunteer->email)) {
                    $emails[] = $volunteer->email;
                }
            }

            if (! empty($record->assigned_gp_doctor_id)) {
                $doctor = User::find($record->assigned_gp_doctor_id);
                if ($doctor && ! empty($doctor->email)) {
                    $emails[] = $doctor->email;
                }
            }

            $emails = array_values(array_unique($emails));

            if (! empty($emails)) {
                Mail::to($emails)->send(new CallScheduled($record));
            }
        } catch (\Throwable $e) {
            // Mail failures must not break scheduling.
            report($e);
        }
    }
}
