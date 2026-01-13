<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Project;
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
    $folder = 'patients/signature/' . $validated['patientid'];

    foreach ($request->file('attachments') as $file) {
        $paths[] = $file->storeAs($folder, $file->getClientOriginalName(), 'public');
    }

    $data['attachments'] = array_values($paths);
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

    /**
     * Get all records + full patient details + appointment info for a patient
     * Route: GET /api/v1/records/by-patient/{patientId}
     */
    public function byPatient($patientId)
    {
        // Validate patient exists
        $patient = Patient::findOrFail($patientId);

        // Get all records for this patient by current volunteer
        $records = Record::where('patientid', $patientId)
            ->where('volunteerid', auth()->id())
            ->with(['scheduledCall.assignedDoctor']) // Load appointment + doctor
            ->latest()
            ->get();

        // Helper to safely decode JSON or return array as-is
        $safeJsonDecode = function ($value) {
            if (is_null($value)) return [];
            if (is_array($value)) return $value;
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        };

        // Transform records
        $records->transform(function ($record) use ($safeJsonDecode) {
            // Doctor names
            $doctorNames = [];
            $doctorIds = $safeJsonDecode($record->doctorid);
            if (!empty($doctorIds)) {
                $doctorNames = User::whereIn('id', $doctorIds)->pluck('name')->toArray();
            }

            // GP names
            $gpNames = [];
            $gpIds = $safeJsonDecode($record->gpid);
            if (!empty($gpIds)) {
                $gpNames = User::whereIn('id', $gpIds)->pluck('name')->toArray();
            }

            // Symptom names
            $symptomNames = [];
            $symptomIds = $safeJsonDecode($record->symptom_ids);
            if (!empty($symptomIds)) {
                $symptomNames = \App\Models\Symptom::whereIn('id', $symptomIds)
                    ->pluck('name')
                    ->toArray();
            }

            // Question summary
            $questionSummary = $safeJsonDecode($record->question_summary);

            // Attachments with full URLs
            $attachments = $this->getAttachmentUrls($record->attachments);

            // Appointment details
            $appointment = $record->scheduledCall;
            $appointmentData = null;
            if ($appointment) {
                $appointmentData = [
                    'status'           => $appointment->status,
                    'gp_doctor_name'   => $appointment->assignedDoctor?->name ?? null,
                    'schedule_date'     => $appointment->schedule_date,
                    'start_time'       => $appointment->schedule_start_time,
                    'end_time'         => $appointment->schedule_end_time,
                    'room_name'        => $appointment->room_name,
                ];
            }

            return [
                'record_id'        => $record->id,
                'doctor_names'     => $doctorNames,
                'gp_names'         => $gpNames,
                'symptom_names'    => $symptomNames,
                'question_summary' => $questionSummary,
                'notes'            => $record->notes ?? null,
                'attachments'      => $attachments,
                'status'           => $record->status,
                'created_at'       => $record->created_at,
                'appointment'      => $appointmentData,
            ];
        });

        // Patient image URLs
        $appUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        $patientFolder = $patient->filenumber ? "patients/{$patient->filenumber}" : 'patients/temp';

        $patientData = $patient->toArray();

        // Profile URL
        $patientData['profile_url'] = $patient->profile
            ? "{$appUrl}/storage/{$patientFolder}/{$patient->profile}"
            : null;

        // Multiple upload fields URLs
        $multiFields = ['generalhealthupload', 'medicationupload', 'malariatestupload', 'hivtestupload'];
        foreach ($multiFields as $field) {
            $urls = [];
            if ($patient->{$field}) {
                $files = is_string($patient->{$field}) ? json_decode($patient->{$field}, true) : $patient->{$field};
                if (is_array($files)) {
                    foreach ($files as $file) {
                        $urls[] = "{$appUrl}/storage/{$patientFolder}/{$file}";
                    }
                }
            }
            $patientData["{$field}_urls"] = $urls;
        }

        return response()->json([
            'success' => true,
            'message' => 'Patient records and appointments retrieved successfully',
            'data' => [
                'patient' => $patientData,
                'records' => $records,
            ],
            'total_records' => $records->count(),
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

    /**
     * Get project details + list of unique patients in the project
     * Route: GET /api/v1/records/by-project/{projectId}
     */
    public function byProject($projectId)
    {
        // Validate project exists and belongs to current volunteer
        $project = Project::where('id', $projectId)
            ->where('volunteerid', auth()->id())
            ->with(['program', 'city.state.country'])
            ->firstOrFail();

        // Get unique patients who have records in this project
        $patients = Patient::whereIn('id', function ($query) use ($projectId) {
            $query->select('patientid')
                ->from('records')
                ->where('projectid', $projectId);
        })
            ->select('id', 'name', 'filenumber', 'mobile', 'gender', 'dob')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Project details and patients retrieved successfully',
            'data' => [
                'program_name'  => $project->program?->name,
                'project_name'  => $project->name,
                'city_name'     => $project->city?->name,
                'state_name'    => $project->city?->state?->name,
                'country_name'  => $project->city?->state?->country?->name,
                'is_active'     => (bool) $project->is_active,
                'start_date'    => $project->startdate?->format('Y-m-d'),
                'end_date'      => $project->enddate?->format('Y-m-d'),
                'budget'        => $project->budget,
                'patients'      => $patients,
            ],
            'total_patients' => $patients->count(),
        ]);
    }
}
