<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;
use App\Models\User;

class RoundRobinController extends Controller
{
  public function index($projectId)
{
    $records = Record::where('projectid', $projectId)->get();

    $doctorPatientMap = [];

    foreach ($records as $record) {
        $patientId = $record->patientid;

        foreach ($record->doctorid ?? [] as $doctorId) {
            if (!isset($doctorPatientMap[$doctorId])) {
                $doctorPatientMap[$doctorId] = [];
            }

            $doctorPatientMap[$doctorId][$patientId] = true;
        }
    }

    $doctorPatientCounts = collect($doctorPatientMap)
        ->map(fn ($patients) => count($patients));

    // Fetch doctor names from users table
    $doctorNames = User::whereIn('id', $doctorPatientCounts->keys())
        ->pluck('name', 'id')
        ->toArray();

    $response = $doctorPatientCounts->map(function ($count, $doctorId) use ($doctorNames) {
        return [
            'id'     => (int) $doctorId,
            'doctor' => $doctorNames[$doctorId] ?? 'Unknown Doctor',
            'count'  => $count,
        ];
    })->values();

    return response()->json($response);
}

}