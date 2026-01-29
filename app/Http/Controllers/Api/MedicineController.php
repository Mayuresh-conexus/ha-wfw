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

        $medicine = Medicine::query()
            ->select('id', 'name', 'dosage', 'is_active')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $medicine,
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
