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


    public function volunteerDataById($volunteerId)
{
    $user = User::with('roles')
        ->where('id', $volunteerId)
        ->firstOrFail();

    // Ensure the user is a volunteer
    if (! $user->hasRole('volunteer')) {
        return response()->json([
            'message' => 'Access denied. User is not a volunteer.'
        ], 403);
    }

    $role = $user->getRoleNames()->first();

    $programs = Program::where('is_active', 1)
        ->whereHas('projects', function ($query) use ($user) {
            $query->where('volunteerid', $user->id)
                  ->where('is_active', 1);
        })
        ->with([
            'projects' => function ($query) use ($user) {
                $query->where('volunteerid', $user->id)
                      ->where('is_active', 1)
                      ->select('id', 'name', 'programid', 'gpid');
            }
        ])
        ->get(['id', 'name'])
        ->map(function ($program) {
            return [
                'id' => $program->id,
                'name' => $program->name,
                'projects' => $program->projects->map(function ($project) {

                    $users = User::whereIn('id', $project->gpid ?? [])
                        ->with('roles:name')
                        ->get(['id', 'name', 'gender'])
                        ->map(function ($user) {
                            return [
                                'id' => (string) $user->id,
                                'name' => $user->name,
                                'role' => $user->roles->pluck('name')->first(),
                                'gender' => $user->gender,
                            ];
                        })
                        ->values();

                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'users' => $users,
                    ];
                })->values(),
            ];
        })
        ->filter(function ($program) {
            return $program['projects']->isNotEmpty();
        })
        ->values();

    return response()->json([
        'message' => 'success',
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $role,
        'gender' => $user->gender,
        'programs' => $programs,
    ]);
}

}
