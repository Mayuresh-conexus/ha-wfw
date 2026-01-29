<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;
use App\Models\Doctor;

class RoundRobinController extends Controller
{
  public function index($projectId)
{
    $records = Record::where('projectid', $projectId)->get();

    $doctorPatientMap = [];

    foreach ($records as $record) {
        $patientId = $record->patientid;

        foreach ($record->doctorid ?? [] as $doctorId) {
            $doctorPatientMap[$doctorId][$patientId] = true;
        }
    }

    $doctorPatientCounts = collect($doctorPatientMap)
        ->map(fn ($patients) => count($patients));

    // Fetch doctor names in one query
    $doctorNames = Doctor::whereIn('id', $doctorPatientCounts->keys())
        ->pluck('name', 'id');

    // Final response: id : doctor : count
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
