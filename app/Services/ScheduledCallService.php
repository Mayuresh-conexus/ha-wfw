<?php

namespace App\Services;

use App\Mail\CallScheduled;
use App\Models\Patient;
use App\Models\Record;
use App\Models\ScheduledCall;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

/**
 * Shared business logic for scheduling calls on a record — used by both
 * CallsRelationManager (the Calls tab) and the "Schedule Call" header action
 * on the record edit page, so the form fields and save side-effects are
 * defined once and behave identically from either entry point.
 */
class ScheduledCallService
{
    /**
     * Form fields for creating/editing a call. Patient & Volunteer are
     * inherited from the record (read-only); GP/doctor is prefilled but
     * editable; the rest is plain scheduling detail.
     *
     * Wrapped in a 2-column grid (each field ~50% width) so it's compact in
     * a modal — used identically by the Calls tab and the header action.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public function formSchema(?Record $record): array
    {
        $assigned = $this->resolveAssignments($record);

        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('patientid')
                    ->label('Patient')
                    ->options(Patient::whereKey($assigned['patientid'])->pluck('name', 'id'))
                    ->default($assigned['patientid'])
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\Select::make('volunteer_id')
                    ->label('Volunteer')
                    ->options(User::whereKey($assigned['volunteer_id'])->pluck('name', 'id'))
                    ->default($assigned['volunteer_id'])
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\Select::make('assigned_gp_doctor_id')
                    ->label('GP / Doctor')
                    ->options(fn () => User::role(['doctor', 'gp'])->pluck('name', 'id'))
                    ->default($assigned['assigned_gp_doctor_id'])
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a clinician'),

                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),

                Forms\Components\DatePicker::make('schedule_date')
                    ->label('Schedule Date')
                    ->required(),

                Forms\Components\TextInput::make('room_name')
                    ->label('Room Name')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->required(),

                Forms\Components\TimePicker::make('schedule_end_time')
                    ->label('End Time')
                    ->seconds(false)
                    ->required(),
            ]),
        ];
    }

    /**
     * Resolve a call's inherited fields from the parent record. Patient and
     * volunteer are read-only on the form; the GP/doctor is prefilled with the
     * record's first GP (else first doctor) but stays editable.
     *
     * @return array{patientid: int|null, volunteer_id: int|null, assigned_gp_doctor_id: int|null}
     */
    public function resolveAssignments(?Record $record): array
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
    public function applyInheritedFields(array $data, ?Record $record): array
    {
        $data['patientid'] = $record?->patientid;
        $data['volunteer_id'] = $record?->volunteerid;

        return $data;
    }

    /**
     * Runs after a call is created/updated. Each side-effect is isolated so a
     * failure (e.g. Zoom not configured, mail down) never breaks the request —
     * the call itself is already saved at this point.
     */
    public function afterCallSaved(ScheduledCall $call): void
    {
        $this->tryCreateZoomMeeting($call);
        $this->sendCallNotifications($call);
        $this->syncPatientStatus($call);
    }

    public function tryCreateZoomMeeting(ScheduledCall $call): void
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

            $start = Carbon::parse($call->schedule_date . ' ' . $call->schedule_start_time, $tz);
            $end = Carbon::parse($call->schedule_date . ' ' . $call->schedule_end_time, $tz);

            $duration = max(1, $start->diffInMinutes($end));

            $meeting = $zoom->createMeeting([
                'topic' => 'Scheduled Call: ' . ($call->room_name ?? 'Call'),
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

            $call->update([
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
    public function sendCallNotifications(ScheduledCall $call): void
    {
        try {
            $emails = [];

            if (! empty($call->patientid)) {
                $patient = Patient::find($call->patientid);
                if ($patient && ! empty($patient->email)) {
                    $emails[] = $patient->email;
                }
            }

            if (! empty($call->volunteer_id)) {
                $volunteer = User::find($call->volunteer_id);
                if ($volunteer && ! empty($volunteer->email)) {
                    $emails[] = $volunteer->email;
                }
            }

            if (! empty($call->assigned_gp_doctor_id)) {
                $doctor = User::find($call->assigned_gp_doctor_id);
                if ($doctor && ! empty($doctor->email)) {
                    $emails[] = $doctor->email;
                }
            }

            $emails = array_values(array_unique($emails));

            if (! empty($emails)) {
                Mail::to($emails)->send(new CallScheduled($call));
            }
        } catch (\Throwable $e) {
            // Mail failures must not break scheduling.
            report($e);
        }
    }

    /**
     * Auto-advance the record's clinical status to "Video call Scheduled" when
     * a call is (re)scheduled — but only when the current status isn't already
     * meaningful (e.g. never silently overwrite "Critical").
     */
    public function syncPatientStatus(ScheduledCall $call): void
    {
        if ($call->status !== 'scheduled') {
            return;
        }

        $record = $call->record;

        if ($record && in_array($record->patient_status, [null, '', 'Normal'], true)) {
            $record->update(['patient_status' => 'Video call Scheduled']);
        }
    }
}
