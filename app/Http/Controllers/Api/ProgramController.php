<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function list()
    {
        $programs = Program::query()
            ->select('id', 'name')
            ->where('is_active', 1) // Optional: only active programs
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $programs,
        ]);
    }
    public function index()
    {
        return response()->json([
            'data' => Program::query()
                ->select('id', 'name', 'description', 'is_active', 'created_at')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $program = Program::create($data);

        return response()->json(['data' => $program], 201);
    }

    public function show(Program $program)
    {
        return response()->json(['data' => $program]);
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ]);

        $program->update($data);

        return response()->json(['data' => $program]);
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
