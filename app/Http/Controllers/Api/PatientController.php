<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Program;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientController extends Controller
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

    /** Get patient by ID (scoped to volunteer's programs) */

    public function byId($patientId)
    {

        $programIds = $this->getVolunteerProgramIds();

        $defaultFields = [
            'id', 'name', 'filenumber', 'email', 'mobile', 'dob', 'gender',
            'height', 'heightunit', 'weight', 'weightunit', 'smoke', 'drinkalcohol',
            'generalhealth', 'generalhealthupload', 'reasonvisit', 'reasontovisit',
            'bp', 'heartrate', 'temperature', 'occupation', 'preferredphysician',
            'oxygensaturation', 'is_active', 'profile', 'medication', 'medicationupload',
            'familyhealthreason', 'malariatest', 'malariatestupload', 'hivtest',
            'hivtestupload', 'additionalcomment', 'programid'
        ];

        $fields = $this->getSelectFields(request(), $defaultFields);

        $patient = Patient::query()
            ->where('id', $patientId)
            ->whereIn('programid', $programIds)
            ->select($fields)
            ->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found.',
            ], 404);
        }

        activity()->performedOn($patient)->log('viewed_patient_details');

        return response()->json([
            'success' => true,
            'message' => 'Patient retrieved successfully',
            'data' => $patient,
        ]);
    }

    public function list()
    {
        $programIds = $this->getVolunteerProgramIds();

        $fields = $this->getSelectFields(request(), ['id', 'name', 'filenumber', 'created_at']);

        $patients = Patient::query()
            ->whereIn('programid', $programIds)
            ->select($fields)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Patients retrieved successfully',
            'data' => $patients->items(),
            'meta' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
            ]
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

        $defaultFields = [
            'id', 'name', 'filenumber', 'email', 'mobile', 'dob', 'gender',
            'height', 'heightunit', 'weight', 'weightunit', 'smoke', 'drinkalcohol',
            'generalhealth', 'generalhealthupload', 'reasonvisit', 'reasontovisit',
            'bp', 'heartrate', 'temperature', 'occupation', 'preferredphysician',
            'oxygensaturation', 'is_active', 'profile', 'medication', 'medicationupload',
            'familyhealthreason', 'malariatest', 'malariatestupload', 'hivtest',
            'hivtestupload', 'additionalcomment', 'programid', 'created_at'
        ];

        $fields = $this->getSelectFields(request(), $defaultFields);

        $patients = Patient::query()
            ->where('programid', $programId)
            ->select($fields)
            ->latest()
            ->paginate(15);

        // Add full absolute URLs using APP_URL
        $patients->getCollection()->transform(function ($patient) {
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
            'data' => $patients->items(),
            'total_patients' => $patients->total(),
            'meta' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
            ]
        ]);
    }

    /**
     * Helper to generate FULL absolute URLs with APP_URL
     */
    private function getImageUrls($paths, $filenumber, $single = false)
    {
        $appUrl = rtrim(config('app.url', 'http://localhost'), '/');

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
     * Lower-case the height/weight unit inputs (when present) so case variants
     * such as "CM"/"KG" sent by the mobile app satisfy the lowercase `in:` rules
     * and are stored consistently.
     */
    private function normaliseUnits(Request $request): void
    {
        foreach (['heightunit', 'weightunit'] as $field) {
            $value = $request->input($field);

            if (is_string($value) && $value !== '') {
                $request->merge([$field => strtolower($value)]);
            }
        }
    }

    /**
     * Create a new patient
     */
    public function store(Request $request)
    {
        $this->normaliseUnits($request);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email:rfc,dns|max:255',
            'mobile' => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\s]+$/'],
            'dob' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'height' => 'nullable|numeric|min:0|max:300',
            'heightunit' => 'nullable|string|in:cm,inch',
            'weight' => 'nullable|numeric|min:0|max:500',
            'weightunit' => 'nullable|string|in:kg,lbs',
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

        $programIds = $this->getVolunteerProgramIds();
        if (isset($data['programid']) && !in_array($data['programid'], $programIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this program.'
            ], 403);
        }

        // D-03: Duplicate Patient Record Prevention (in-memory because fields are encrypted)
        $patientsToCheck = Patient::whereIn('programid', $programIds)->get();
        $duplicate = $patientsToCheck->first(function ($p) use ($data) {
            $isSameName = strtolower(trim($p->name)) === strtolower(trim($data['name']));
            $isSameGender = isset($data['gender']) ? ($p->gender === $data['gender']) : true;
            $isSameDob = isset($data['dob']) && $p->dob ? \Carbon\Carbon::parse($p->dob)->isSameDay($data['dob']) : true;
            $isSameMobile = !empty($data['mobile']) ? ($p->mobile === $data['mobile']) : true;

            // Strict match on name + DOB + gender to prevent dupes
            // If mobile is provided, it must also match.
            return $isSameName && $isSameGender && $isSameDob && $isSameMobile;
        });

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'A patient with these details already exists.',
                'data' => clone $duplicate,
            ], 409); // 409 Conflict
        }

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
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folderPath, $fileName, 'public');
            $data['profile'] = 'patients/' . $filenumber . '/' . $fileName;
        }


        // Handle multiple file fields (store relative paths)
        $multiFields = ['generalhealthupload', 'medicationupload', 'malariatestupload', 'hivtestupload'];

        foreach ($multiFields as $field) {
            if ($request->hasFile($field)) {
                $paths = [];

                foreach ($request->file($field) as $file) {
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $file->storeAs($folderPath, $fileName, 'public');

                    $paths[] = $folderPath . '/' . $fileName;
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
        $this->normaliseUnits($request);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email:rfc,dns|max:255',
            'mobile' => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\s]+$/'],
            'dob' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'height' => 'nullable|numeric|min:0|max:300',
            'heightunit' => 'nullable|string|in:cm,inch',
            'weight' => 'nullable|numeric|min:0|max:500',
            'weightunit' => 'nullable|string|in:kg,lbs',
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

        $programIds = $this->getVolunteerProgramIds();
        if (!in_array($patient->programid, $programIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this patient.'
            ], 403);
        }

        if (isset($validated['programid']) && !in_array($validated['programid'], $programIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to assign patient to this program.'
            ], 403);
        }

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
                Storage::disk('public')->delete($patient->profile);
            }
            $file = $request->file('profile');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folderPath, $fileName, 'public');
            $updateData['profile'] = $folderPath . '/' . $fileName;
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
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs($folderPath, $fileName, 'public');

                    $oldPaths[] = $folderPath . '/' . $fileName;
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
