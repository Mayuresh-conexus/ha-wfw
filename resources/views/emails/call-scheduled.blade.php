@php
    $start =
        isset($record->schedule_date) && isset($record->schedule_start_time)
            ? \Carbon\Carbon::parse($record->schedule_date . ' ' . $record->schedule_start_time)->toDayDateTimeString()
            : null;
    $end =
        isset($record->schedule_date) && isset($record->schedule_end_time)
            ? \Carbon\Carbon::parse($record->schedule_date . ' ' . $record->schedule_end_time)->toDayDateTimeString()
            : null;
@endphp

<div style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#111827;">
    <h2>Scheduled Call</h2>

    <p><strong>Room:</strong> {{ $record->room_name ?? '-' }}</p>
    <p><strong>Date / Time:</strong> {{ $start ?? '-' }} @if ($end)
            - {{ $end }}
        @endif
    </p>

    @if (!empty($record->zoom_join_url))
        <p><strong>Join URL:</strong> <a href="{{ $record->zoom_join_url }}">Join Meeting</a></p>
    @endif

    @if (!empty($record->zoom_start_url))
        <p><strong>Host URL:</strong> <a href="{{ $record->zoom_start_url }}">Start Meeting</a></p>
    @endif

    <hr />
    <p>This call was scheduled in the system. Please join at the time above.</p>
</div>
