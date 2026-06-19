<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncController extends Controller
{
    /**
     * Get program IDs the authenticated volunteer has access to
     */
    private function getVolunteerProgramIds(): array
    {
        return Project::where('volunteerid', auth()->id())
            ->where('is_active', 1)
            ->whereNotNull('programid')
            ->pluck('programid')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Pull changes since last sync timestamp
     */
    public function pull(Request $request)
    {
        $lastSync = $request->query('last_sync_at');

        if (!$lastSync) {
            return response()->json([
                'success' => false,
                'message' => 'last_sync_at query parameter is required (ISO 8601 format).'
            ], 400);
        }

        try {
            $parsedDate = Carbon::parse($lastSync);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid last_sync_at format.'
            ], 400);
        }

        $programIds = $this->getVolunteerProgramIds();

        // Include trashed so mobile app can delete local copies
        $patients = Patient::withTrashed()
            ->whereIn('programid', $programIds)
            ->where('updated_at', '>', $parsedDate)
            ->get();

        $records = Record::withTrashed()
            ->where('volunteerid', auth()->id())
            ->where('updated_at', '>', $parsedDate)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Sync data retrieved successfully',
            'data' => [
                'timestamp' => now()->toIso8601String(),
                'changes' => [
                    'patients' => $patients,
                    'records' => $records,
                ]
            ]
        ]);
    }

    /**
     * Push offline changes from mobile to server
     */
    public function push(Request $request)
    {
        $validated = $request->validate([
            'patients' => 'array',
            'records' => 'array',
        ]);

        $programIds = $this->getVolunteerProgramIds();
        $conflicts = [];
        $applied = [
            'patients' => [],
            'records' => [],
        ];

        DB::beginTransaction();
        try {
            // Process Patients
            if (isset($validated['patients'])) {
                foreach ($validated['patients'] as $clientPatient) {
                    if (!isset($clientPatient['id'])) {
                        // Create new patient
                        $patient = Patient::create($clientPatient);
                        $applied['patients'][] = ['client_id' => $clientPatient['client_id'] ?? null, 'server_id' => $patient->id];
                    } else {
                        // Update existing patient
                        $patient = Patient::find($clientPatient['id']);
                        if ($patient) {
                            $clientUpdatedAt = Carbon::parse($clientPatient['updated_at']);
                            if ($patient->updated_at->gt($clientUpdatedAt)) {
                                $conflicts['patients'][] = $patient; // Server wins
                            } else {
                                $patient->update($clientPatient);
                                $applied['patients'][] = ['client_id' => $clientPatient['client_id'] ?? null, 'server_id' => $patient->id];
                            }
                        }
                    }
                }
            }

            // Process Records
            if (isset($validated['records'])) {
                foreach ($validated['records'] as $clientRecord) {
                    if (!isset($clientRecord['id'])) {
                        $clientRecord['volunteerid'] = auth()->id();
                        $record = Record::create($clientRecord);
                        $applied['records'][] = ['client_id' => $clientRecord['client_id'] ?? null, 'server_id' => $record->id];
                    } else {
                        $record = Record::find($clientRecord['id']);
                        if ($record && $record->volunteerid === auth()->id()) {
                            $clientUpdatedAt = Carbon::parse($clientRecord['updated_at']);
                            if ($record->updated_at->gt($clientUpdatedAt)) {
                                $conflicts['records'][] = $record; 
                            } else {
                                $record->update($clientRecord);
                                $applied['records'][] = ['client_id' => $clientRecord['client_id'] ?? null, 'server_id' => $record->id];
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sync failed to apply changes: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sync processed successfully.',
            'data' => [
                'timestamp' => now()->toIso8601String(),
                'applied' => $applied,
                'conflicts' => empty($conflicts) ? null : $conflicts,
            ]
        ]);
    }
}
