<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SymptomBulkController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'symptoms' => ['required', 'array', 'min:1', 'max:200'],
            'symptoms.*.name' => ['required', 'string', 'max:255'],
            'symptoms.*.body_section_id' => ['nullable', 'integer', 'exists:body_sections,id'],
            'symptoms.*.is_active' => ['nullable', 'boolean'],
            'symptoms.*.tag' => ['nullable', 'string', 'max:100'],
            'symptoms.*.iscritical' => ['nullable', 'boolean'],
            // Removed symptomid from validation — it's not in the table
        ]);

        $results = DB::transaction(function () use ($data) {

            $created = [];

            foreach ($data['symptoms'] as $input) {

                $symptom = Symptom::create([
                    'name'            => $input['name'],
                    'body_section_id' => $input['body_section_id'] ?? null,
                    'is_active'       => $input['is_active'] ?? true,
                    'tag'             => $input['tag'] ?? null,
                    'iscritical'      => $input['iscritical'] ?? false,
                ]);

                $created[] = [
                    'id'              => $symptom->id,              // ← this is what questions will use
                    'name'            => $symptom->name,
                    'is_active'       => $symptom->is_active,
                    'was_created'     => true, // since we're using create(), not update
                ];
            }

            return $created;
        });

        return response()->json([
            'message'   => 'Symptoms bulk created successfully',
            'count'     => count($results),
            'symptoms'  => $results,
        ], 201);
    }
}