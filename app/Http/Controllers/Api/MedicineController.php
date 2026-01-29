<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Medicine::with('symptom:id,name')->where('is_active', true)->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'symptom_id' => ['required', 'exists:symptoms,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'dosage' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $medicine = Medicine::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Medicine created successfully',
            'data' => $medicine
        ], 201);
    }

    public function bySymptom(Symptom $symptom)
{
    return response()->json([
        'status' => true,
        'symptom' => [
            'id' => $symptom->id,
            'name' => $symptom->name,
            'iscritical' => $symptom->iscritical,
        ],
        'medicines' => $symptom->medicines()
            ->where('is_active', true)
            ->get([
                'id',
                'symptom_id',
                'name',
                'type',
                'dosage',
                'is_active',
            ])
    ]);
}

public function bulkStore(Request $request)
{
    $validated = $request->validate([
        'medicines' => ['required', 'array', 'min:1'],

        'medicines.*.symptom_ids' => ['required', 'array', 'min:1'],
        'medicines.*.symptom_ids.*' => ['exists:symptoms,id'],

        'medicines.*.name' => ['required', 'string', 'max:255'],
        'medicines.*.type' => ['nullable', 'string'],
        'medicines.*.dosage' => ['nullable', 'string'],
        'medicines.*.is_active' => ['boolean'],
    ]);

    $created = [];

    foreach ($validated['medicines'] as $medicineData) {
        $created[] = Medicine::create($medicineData);
    }

    return response()->json([
        'status' => true,
        'message' => 'Medicines uploaded successfully',
        'count' => count($created),
        'data' => $created
    ], 201);
}


}
