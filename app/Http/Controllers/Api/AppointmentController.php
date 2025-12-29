<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduledCall;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Get upcoming appointments for the authenticated volunteer
     * Route: GET /api/v1/appointments/upcoming
     */
    public function upcoming()
    {
        $appointments = ScheduledCall::with(['record.patient'])
            ->where('volunteer_id', auth()->id())
            ->where('status', 'scheduled')
            ->select([
                'id',
                'recordid',
                'schedule_date',
                'schedule_start_time',
                'schedule_end_time',
                'room_name',
                'status',
                'created_at'
            ])
            ->orderBy('schedule_date')
            ->orderBy('schedule_start_time')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Upcoming appointments retrieved successfully',
            'data' => $appointments,
            'total' => $appointments->count(),
        ]);
    }

    /**
     * Get completed appointments for the authenticated volunteer
     * Route: GET /api/v1/appointments/completed
     */
    public function completed()
    {
        $appointments = ScheduledCall::with(['record.patient'])
            ->where('volunteer_id', auth()->id())
            ->where('status', 'completed')
            ->select([
                'id',
                'recordid',
                'schedule_date',
                'schedule_start_time',
                'schedule_end_time',
                'room_name',
                'status',
                'created_at'
            ])
            ->orderByDesc('schedule_date')
            ->orderByDesc('schedule_start_time')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Completed appointments retrieved successfully',
            'data' => $appointments,
            'total' => $appointments->count(),
        ]);
    }
}
