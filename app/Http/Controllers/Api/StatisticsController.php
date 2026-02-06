<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Patient;
use App\Models\ScheduledCall;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $volunteerId = $request->input('volunteer_id', auth()->id());

        // Get all projects for this volunteer with program
        $projects = Project::query()
            ->where('volunteerid', $volunteerId)
            ->with('program')
            ->get();

        // Group projects by program
        $grouped = $projects->groupBy('programid');

        $data = [];
        $totalPatientsOverall = 0;

        foreach ($grouped as $programId => $programProjects) {
            if ($programId === null) continue;

            $program = $programProjects->first()->program;

            $programData = [
                'program' => [
                    'id' => $program->id,
                    'name' => $program->name,
                    'description' => $program->description,
                    'is_active' => $program->is_active,
                    'created_at' => $program->created_at,
                ],
                'projects' => [],
                'patients' => [],
                'total_patients' => 0,
            ];

            // Get all unique patient IDs directly assigned to this program
            $programPatientIds = Patient::where('programid', $programId)
                ->pluck('id');

            // Projects list (with patient count per project)
            foreach ($programProjects as $project) {
                $projectPatientCount = Patient::where('programid', $programId)
                    ->whereHas('records', fn($q) => $q->where('projectid', $project->id))
                    ->count();

                $programData['projects'][] = [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'cityid' => $project->cityid,
                    'stateid' => $project->stateid,
                    'countryid' => $project->countryid,
                    'isactive' => $project->is_active,
                    'startdate' => $project->startdate,
                    'enddate' => $project->enddate,
                    'budget' => $project->budget,
                    'othercity' => $project->othercity,
                    'created_at' => $project->created_at,
                    'patient_count' => $projectPatientCount,
                ];
            }

            // Load full patient details (only patients in this program)
            $patients = Patient::where('programid', $programId)
                ->select([
                    'id',
                    'name',
                    'filenumber',
                    'email',
                    'mobile',
                    'dob',
                    'gender',
                    'height',
                    'heightunit',
                    'weight',
                    'weightunit',
                    'smoke',
                    'drinkalcohol',
                    'occupation',
                    'bp',
                    'heartrate',
                    'temperature',
                    'oxygensaturation',
                    'created_at'
                ])
                ->get();

            foreach ($patients as $patient) {
                $programData['patients'][] = [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'filenumber' => $patient->filenumber,
                    'email' => $patient->email,
                    'mobile' => $patient->mobile,
                    'dob' => $patient->dob,
                    'gender' => $patient->gender,
                    'height' => $patient->height,
                    'heightunit' => $patient->heightunit,
                    'weight' => $patient->weight,
                    'weightunit' => $patient->weightunit,
                    'smoke' => $patient->smoke,
                    'drinkalcohol' => $patient->drinkalcohol,
                    'occupation' => $patient->occupation,
                    'bp' => $patient->bp,
                    'heartrate' => $patient->heartrate,
                    'temperature' => $patient->temperature,
                    'oxygensaturation' => $patient->oxygensaturation,
                    'created_at' => $patient->created_at,
                ];
            }

            $programData['total_patients'] = $patients->count();
            $totalPatientsOverall += $patients->count();

            $data[] = $programData;
        }

        // Appointments (unchanged)
        $upcomingCalls = ScheduledCall::where('volunteer_id', $volunteerId)
            ->where('status', 'scheduled')
            ->with(['record.patient' => fn($q) => $q->select('id', 'name', 'filenumber', 'mobile')])
            ->select('id', 'recordid', 'schedule_date', 'schedule_start_time', 'schedule_end_time', 'status', 'room_name')
            ->get();

        $completedCalls = ScheduledCall::where('volunteer_id', $volunteerId)
            ->where('status', 'completed')
            ->with(['record.patient' => fn($q) => $q->select('id', 'name', 'filenumber', 'mobile')])
            ->select('id', 'recordid', 'schedule_date', 'schedule_start_time', 'schedule_end_time', 'status', 'room_name')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $data,
            'total_registered_patients' => $totalPatientsOverall,
            'appointments' => [
                'upcoming_count' => $upcomingCalls->count(),
                'completed_count' => $completedCalls->count(),
                'upcoming_calls' => $upcomingCalls,
                'completed_calls' => $completedCalls,
            ],
        ]);
    }

    public function counts(Request $request)
    {
        $volunteerId = $request->input('volunteer_id', auth()->id());

        $projects = Project::where('volunteerid', $volunteerId)
            ->where('is_active', 1)
            ->select('id', 'programid', 'enddate')
            ->with([
                'program' => function ($query) {
                    $query->where('is_active', 1)
                        ->select('id', 'name', 'description', 'is_active', 'created_at');
                }
            ])
            ->get();


        $grouped = $projects->groupBy('programid');

        $data = [];
        $totalPatientsOverall = 0;

        foreach ($grouped as $programId => $programProjects) {
            if ($programId === null) continue;

            $program = $programProjects->first()->program;

            $totalProjects = $programProjects->count();
            $completedProjects = $programProjects->filter(fn($p) => $p->enddate && $p->enddate < now())->count();

            // Count patients directly assigned to this program
            $patientCount = Patient::where('programid', $programId)->where('is_active', true)->count();

            $data[] = [
                'program' => [
                    'id' => $program->id,
                    'name' => $program->name,
                    'description' => $program->description,
                    'is_active' => $program->is_active,
                    'created_at' => $program->created_at,
                ],
                'project_count' => $totalProjects,
                'completed_project_count' => $completedProjects,
                'patient_count' => $patientCount,
            ];

            $totalPatientsOverall += $patientCount;
        }

        $upcomingAppointments = ScheduledCall::where('volunteer_id', $volunteerId)
            ->where('status', 'scheduled')
            ->count();

        $completedAppointments = ScheduledCall::where('volunteer_id', $volunteerId)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'message' => 'success',
            'data' => $data,
            'total_registered_patients' => $totalPatientsOverall,
            'appointments' => [
                'upcoming_count' => $upcomingAppointments,
                'completed_count' => $completedAppointments,
            ]
        ]);
    }
}
