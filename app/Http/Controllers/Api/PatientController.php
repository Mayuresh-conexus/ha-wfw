<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Program;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
        /** Get all petients by petients id */

    public function byId($patientId){

        $patient = Patient::query()
            ->where('id', $patientId)
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
        'generalhealth',
        'generalhealthupload',
        'reasonvisit',
        'reasontovisit',
        'bp',
        'heartrate',
        'temperature',
        'occupation',
        'preferredphysician',
        'oxygensaturation',
        'is_active',
        'profile',
        'medication',
        'medicationupload',
        'familyhealthreason',
        'malariatest',
        'malariatestupload',
        'hivtest',
        'hivtestupload',
        'additionalcomment',
        'programid',

    ])->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Patient retrieved successfully',
            'data' => $patient,
        ]);
    }

    public function list(){
        $patients = Patient::query()
            ->select([
                'id',
                'name',
                'filenumber',

            ])
            ->latest()
            ->get();        

        return response()->json([
            'success' => true,
            'message' => 'Patients retrieved successfully',
            'data' => $patients,
        ]);
    }

    /**
     * Get all patients for a specific program (filtered by authenticated volunteer)
     */
    public function byProgram($programId)
    {
        Program::findOrFail($programId);

        $projectIds = Project::where('programid', $programId)
            ->where('volunteerid', auth()->id())
            ->pluck('id');

        if ($projectIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No projects found for this program.',
                'data' => [],
            ]);
        }

        $patients = Patient::query()
            ->where('programid', $programId)
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
                'generalhealth',
                'generalhealthupload',
                'reasonvisit',
                'reasontovisit',
                'bp',
                'heartrate',
                'temperature',
                'occupation',
                'preferredphysician',
                'oxygensaturation',
                'is_active',
                'profile',
                'medication',
                'medicationupload',
                'familyhealthreason',
                'malariatest',
                'malariatestupload',
                'hivtest',
                'hivtestupload',
                'additionalcomment',
                'programid',
            ])
            ->latest()
            ->get();

        // Add full absolute URLs using APP_URL
        $patients->transform(function ($patient) {
            $patient->generalhealthupload = $this->getImageUrls($patient->generalhealthupload, $patient->filenumber);
            $patient->medicationupload = $this->getImageUrls($patient->medicationupload, $patient->filenumber);
            $patient->malariatestupload = $this->getImageUrls($patient->malariatestupload, $patient->filenumber);
            $patient->hivtestupload = $this->getImageUrls($patient->hivtestupload, $patient->filenumber);
            $patient->profile = $this->getImageUrls($patient->profile, $patient->filenumber, true);
            return $patient;
        });

        return response()->json([
            'success' => true,
            'message' => 'Patients retrieved successfully',
            'data' => $patients,
            'total_patients' => $patients->count(),
        ]);
    }

    /**
     * Helper to generate FULL absolute URLs with APP_URL
     */
    private function getImageUrls($paths, $filenumber, $single = false)
        {
            $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
        
            if ($single) {
                // profile is currently stored as filename only in your code
                // if you keep it as filename: return $paths ? $appUrl . '/storage/patients/' . $filenumber . '/' . $paths : null;
                // if you also switch profile to store full path, use the same logic as below:
                return $paths ? $appUrl . '/storage/' . ltrim($paths, '/') : null;
            }
        
            if (!$paths) {
                return [];
            }
        
            // decode JSON string or accept array
            $decoded = is_string($paths) ? json_decode($paths, true) : $paths;
            $decoded = is_array($decoded) ? $decoded : [$paths];
        
            return array_map(function ($path) use ($appUrl) {
                // path is like "patients/IN2382/Frame 7.png"
                return $appUrl . '/storage/' . ltrim($path, '/');
            }, $decoded);
        }

    /**
     * Create a new patient
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'height' => 'nullable|numeric',
            'heightunit' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'weightunit' => 'nullable|string',
            'smoke' => 'boolean',
            'drinkalcohol' => 'boolean',
            'generalhealth' => 'nullable|string',
            'generalhealthupload' => 'nullable|array|max:5',
            'generalhealthupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'reasonvisit' => 'nullable|string',
            'bp' => 'nullable|string|max:255',
            'heartrate' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric',
            'occupation' => 'nullable|string|max:255',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'reasontovisit' => 'nullable|string',
            'medication' => 'nullable|string',
            'medicationupload' => 'nullable|array|max:5',
            'medicationupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'familyhealthreason' => 'nullable|string',
            'malariatest' => 'nullable|string',
            'malariatestupload' => 'nullable|array|max:5',
            'malariatestupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'hivtest' => 'nullable|string',
            'hivtestupload' => 'nullable|array|max:5',
            'hivtestupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'preferredphysician' => 'nullable|string|max:255',
            'oxygensaturation' => 'nullable|numeric',
            'additionalcomment' => 'nullable|string',
            'programid' => 'nullable|integer|exists:programs,id',
            'is_active' => 'boolean',
        ]);

        // Generate unique filenumber
        do {
            $filenumber = 'IN' . mt_rand(1000, 9999);
        } while (Patient::where('filenumber', $filenumber)->exists());

        $data['filenumber'] = $filenumber;

        // Folder path on public disk
        $folderPath = 'patients/' . $filenumber;
        Storage::disk('public')->makeDirectory($folderPath);

        // Handle profile (single file)
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = $file->getClientOriginalName();
            $file->storeAs($folderPath, $fileName, 'public');
            $data['profile'] = $fileName;
        }


        // Handle multiple file fields (store relative paths)
            $multiFields = ['generalhealthupload', 'medicationupload', 'malariatestupload', 'hivtestupload'];
            
            foreach ($multiFields as $field) {
                if ($request->hasFile($field)) {
                    $paths = [];
            
                    foreach ($request->file($field) as $file) {
                        $fileName = $file->getClientOriginalName();
            
                        // storeAs returns path only if you capture it via $path; but easiest is to build it yourself
                        $file->storeAs($folderPath, $fileName, 'public');
            
                        // store relative path including folder
                        $paths[] = $folderPath . '/' . $fileName;   // patients/IN2382/Frame 7.png
                    }
            
                    $data[$field] = $paths;
                }
            }


        $patient = Patient::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Patient created successfully',
            'data' => $patient,
        ], 201);
    }
    /**
     * Update a patient
     */
    /**
     * Update a patient
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'height' => 'nullable|numeric',
            'heightunit' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'weightunit' => 'nullable|string',
            'smoke' => 'sometimes|boolean',
            'drinkalcohol' => 'sometimes|boolean',
            'generalhealth' => 'nullable|string',
            'generalhealthupload' => 'nullable|array|max:5',
            'generalhealthupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'reasonvisit' => 'nullable|string',
            'bp' => 'nullable|string|max:255',
            'heartrate' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric',
            'occupation' => 'nullable|string|max:255',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'reasontovisit' => 'nullable|string',
            'medication' => 'nullable|string',
            'medicationupload' => 'nullable|array|max:5',
            'medicationupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'familyhealthreason' => 'nullable|string',
            'malariatest' => 'nullable|string',
            'malariatestupload' => 'nullable|array|max:5',
            'malariatestupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'hivtest' => 'nullable|string',
            'hivtestupload' => 'nullable|array|max:5',
            'hivtestupload.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'preferredphysician' => 'nullable|string|max:255',
            'oxygensaturation' => 'nullable|numeric',
            'additionalcomment' => 'nullable|string',
            'programid' => 'nullable|integer|exists:programs,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $folderPath = 'patients/' . $patient->filenumber;

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $updateData = [];

        // Text & other fields
        $textFields = [
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
            'generalhealth',
            'generalhealthupload',
            'reasonvisit',
            'reasontovisit',
            'bp',
            'heartrate',
            'temperature',
            'occupation',
            'preferredphysician',
            'oxygensaturation',
            'is_active',
            'profile',
            'medication',
            'medicationupload',
            'familyhealthreason',
            'malariatest',
            'malariatestupload',
            'hivtest',
            'hivtestupload',
            'additionalcomment',
            'programid',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        // Booleans
        if ($request->has('smoke')) {
            $updateData['smoke'] = $request->boolean('smoke');
        }
        if ($request->has('drinkalcohol')) {
            $updateData['drinkalcohol'] = $request->boolean('drinkalcohol');
        }
        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        // Profile (replace)
        if ($request->hasFile('profile')) {
            if ($patient->profile) {
                Storage::disk('public')->delete($folderPath . '/' . $patient->profile);
            }
            $file = $request->file('profile');
            $fileName = $file->getClientOriginalName();
            $file->storeAs($folderPath, $fileName, 'public');
            $updateData['profile'] = $fileName;
        }

        // Multiple file fields (append)
        $multiFields = ['generalhealthupload', 'medicationupload', 'malariatestupload', 'hivtestupload'];

        foreach ($multiFields as $field) {
            if ($request->hasFile($field)) {
                $oldPaths = [];
        
                if ($patient->$field) {
                    $decoded = json_decode($patient->$field, true);
                    $oldPaths = is_array($decoded) ? $decoded : [];
                }
        
                foreach ($request->file($field) as $file) {
                    $fileName = $file->getClientOriginalName();
                    $file->storeAs($folderPath, $fileName, 'public');
        
                    $oldPaths[] = $folderPath . '/' . $fileName; // patients/{filenumber}/{filename}
                }
        
                $updateData[$field] = array_values(array_unique($oldPaths));
            }
        }


        if (empty($updateData)) {
            return response()->json([
                'success' => true,
                'message' => 'No changes detected.',
                'data' => $patient,
            ]);
        }

        $patient->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Patient updated successfully!',
            'data' => $patient->fresh(),
        ]);
    }
}
