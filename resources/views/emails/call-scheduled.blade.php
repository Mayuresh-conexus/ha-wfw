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

<div
    style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#111827; background-color:#f9fafb; padding:20px; border-radius:8px; max-width: 600px; margin:0 auto;">
    <h2 style="font-size: 24px; color: #333;">Scheduled Call</h2>

    <p style="font-size: 16px; margin: 10px 0;"><strong>Room:</strong> {{ $record->room_name ?? '-' }}</p>
    <p style="font-size: 16px; margin: 10px 0;"><strong>Date / Time:</strong> {{ $start ?? '-' }}
        @if ($end)
            - {{ $end }}
        @endif
    </p>

    @if (!empty($record->zoom_join_url))
        <p style="font-size: 16px; margin: 10px 0;"><strong>Join URL:</strong> <a href="{{ $record->zoom_join_url }}"
                style="color: #1d72b8; text-decoration: underline;">Join Meeting</a></p>
    @endif

    @if (!empty($record->zoom_start_url))
        <p style="font-size: 16px; margin: 10px 0;"><strong>Host URL:</strong> <a href="{{ $record->zoom_start_url }}"
                style="color: #1d72b8; text-decoration: underline;">Start Meeting</a></p>
    @endif

    <hr style="border: 1px solid #e5e7eb; margin: 20px 0;">

    <p style="font-size: 16px; color: #555;">This call was scheduled in the system. Please join at the time above.</p>
</div>
