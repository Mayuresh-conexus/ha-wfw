<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Record;
use App\Models\User;

class RoundRobinController extends Controller
{
    public function index($projectId)
    {
        // 1. Fetch project
        $project = Project::findOrFail($projectId);

        // 2. Get assigned doctor IDs from project (JSON)
        $assignedDoctorIds = $project->gpid ?? [];

        if (empty($assignedDoctorIds)) {
            return response()->json([]);
        }

        // 3. Fetch assigned doctors
       $doctors = User::whereIn('id', $assignedDoctorIds)
        ->role(['doctor'])
        ->get();

        // 4. Fetch records for the project
        $records = Record::where('projectid', $projectId)->get();

        // 5. Build doctor => unique patient map
        $doctorPatientMap = [];

        foreach ($records as $record) {
            $patientId = $record->patientid;

            foreach ($record->doctorid ?? [] as $doctorId) {
                if (! in_array($doctorId, $assignedDoctorIds)) {
                    continue;
                }

                $doctorPatientMap[$doctorId][$patientId] = true;
            }
        }

        // 6. Count unique patients per doctor
        $doctorPatientCounts = collect($doctorPatientMap)
            ->map(fn ($patients) => count($patients));

        // 7. Build response including zero-count doctors
        $response = $doctors->map(function ($doctor) use ($doctorPatientCounts) {
            return [
                'id'     => $doctor->id,
                'doctor' => $doctor->name,
                'count'  => $doctorPatientCounts[$doctor->id] ?? 0,
                'gender' => $doctor->gender ?? 'Not Specified',
            ];
        })->values();

        return response()->json($response);
    }
}
