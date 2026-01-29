<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;

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

            // ensure unique patients per doctor
            $doctorPatientMap[$doctorId][$patientId] = true;
        }
    }

    $doctorPatientCounts = collect($doctorPatientMap)
        ->map(fn ($patients) => count($patients));

    return response()->json($doctorPatientCounts);
}

}
