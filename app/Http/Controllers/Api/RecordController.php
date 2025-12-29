<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Record;
use App\Models\ScheduledCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecordController extends Controller
{
    /**
     * Create a new record
     * POST /api/v1/records
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patientid'        => 'required|integer|exists:patients,id',
            'projectid'        => 'required|integer|exists:projects,id',
            'programid'        => 'required|integer|exists:programs,id',
            'symptom_ids'      => 'required|array|min:1',
            'symptom_ids.*'    => 'integer|exists:symptoms,id',
            'question_summary' => 'required|string', // We'll decode manually
            'record_type'      => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
            'attachments.*'    => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status'           => 'nullable|in:draft,submitted,reviewed',
            'doctorid'         => 'nullable|array',
            'doctorid.*'       => 'integer|exists:users,id',
            'gpid'             => 'nullable|array',
            'gpid.*'           => 'integer|exists:users,id',
        ]);

        // Decode question_summary (since we can't cast LONGTEXT automatically to array easily)
        $questionSummary = json_decode($validated['question_summary'], true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionSummary)) {
            return response()->json([
                'success' => false,
                'message' => 'question_summary must be a valid JSON array.',
            ], 422);
        }

        // Prepare data
        $data = $validated;
        $data['question_summary'] = $questionSummary;
        $data['volunteerid'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('records/attachments', 'public');
            }
            $data['attachments'] = $paths;
        } else {
            $data['attachments'] = [];
        }

        $record = Record::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'data' => $record->load(['patient', 'project', 'program', 'volunteer']),
        ], 201);
    }

    /**
     * Update an existing record
     * PUT /api/v1/records/{record}
     */
    public function update(Request $request, Record $record)
    {
        if ($record->volunteerid !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You can only update your own records.',
            ], 403);
        }

        $validated = $request->validate([
            'symptom_ids'      => 'sometimes|array',
            'symptom_ids.*'    => 'integer|exists:symptoms,id',
            'question_summary' => 'sometimes|string',
            'record_type'      => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
            'attachments.*'    => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status'           => 'nullable|in:draft,submitted,reviewed',
            'doctorid'         => 'nullable|array',
            'doctorid.*'       => 'integer|exists:users,id',
            'gpid'             => 'nullable|array',
            'gpid.*'           => 'integer|exists:users,id',
        ]);

        $data = [];

        // Only update fields that were sent
        if ($request->has('symptom_ids')) {
            $data['symptom_ids'] = $validated['symptom_ids']; // already array from form-data []
        }

        if ($request->has('doctorid')) {
            $data['doctorid'] = $validated['doctorid'] ?? [];
        }

        if ($request->has('gpid')) {
            $data['gpid'] = $validated['gpid'] ?? [];
        }

        if ($request->filled('record_type')) {
            $data['record_type'] = $validated['record_type'];
        }

        if ($request->filled('notes')) {
            $data['notes'] = $validated['notes'];
        }

        if ($request->filled('status')) {
            $data['status'] = $validated['status'];
        }

        // Handle question_summary
        if ($request->has('question_summary')) {
            $questionSummary = json_decode($request->question_summary, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionSummary)) {
                return response()->json([
                    'success' => false,
                    'message' => 'question_summary must be a valid JSON array.',
                ], 422);
            }
            $data['question_summary'] = $questionSummary;
        }

        // Handle attachments - append new ones
        if ($request->hasFile('attachments')) {
            $existing = $record->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $existing[] = $file->store('records/attachments', 'public');
            }
            $data['attachments'] = $existing;
        }

        // Only update if there's data to save
        if (!empty($data)) {
            $record->update($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'data' => $record->fresh()->load(['patient', 'project', 'program', 'volunteer']),
        ]);
    }
    /**
     * Get all records for a patient (only volunteer's own records)
     * GET /api/v1/records/by-patient/{patientId}
     */
    private function getAttachmentUrls($paths)
    {
        if (!$paths || empty($paths)) {
            return [];
        }

        // Get APP_URL from .env with fallback
        $appUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');

        // Paths are already stored as ['records/attachments/file.jpg', ...]
        return array_map(function ($path) use ($appUrl) {
            return $appUrl . '/storage/' . $path;
        }, $paths);
    }
    public function byPatient($patientId)
    {
        Patient::findOrFail($patientId);

        $records = Record::where('patientid', $patientId)
            ->where('volunteerid', auth()->id())
            ->with(['patient', 'project', 'program', 'volunteer'])
            ->latest()
            ->get();

        // Transform attachments to full absolute URLs
        $records->transform(function ($record) {
            $record->attachments = $this->getAttachmentUrls($record->attachments);
            return $record;
        });

        return response()->json([
            'success' => true,
            'message' => 'Records retrieved successfully',
            'data' => $records,
            'total' => $records->count(),
        ]);
    }

    /**
     * Get all doctors (role = doctor)
     * GET /api/v1/records/doctors
     */
    public function doctors()
    {
        $doctors = User::role('doctor')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Doctors retrieved successfully',
            'data' => $doctors,
        ]);
    }

    /**
     * Get all GPs (role = gp)
     * GET /api/v1/records/gps
     */
    public function gps()
    {
        $gps = User::role('gp')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'GPs retrieved successfully',
            'data' => $gps,
        ]);
    }

    /**
     * Get appointments (scheduled calls) by record ID
     * GET /api/v1/records/{recordId}/appointments
     */
    public function appointments($recordId)
    {
        // Validate record exists and belongs to authenticated volunteer
        $record = Record::where('id', $recordId)
            ->where('volunteerid', auth()->id())
            ->firstOrFail();

        $appointments = ScheduledCall::where('recordid', $recordId)
            ->with([
                'volunteer' => fn($q) => $q->select('id', 'name'),
                'doctor' => fn($q) => $q->select('id', 'name')
            ])
            ->select([
                'id',
                'recordid',
                'volunteer_id',
                'assigned_gp_doctor_id',
                'schedule_date',
                'schedule_start_time',
                'schedule_end_time',
                'room_name',
                'status',
                'created_at'
            ])
            ->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_start_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Appointments retrieved successfully',
            'data' => $appointments,
            'total' => $appointments->count(),
        ]);
    }
}
