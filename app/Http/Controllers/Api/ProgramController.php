<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProgramController extends Controller
{
    
    public function list()
    {
        $programs = Cache::remember('programs.public_list', now()->addHours(12), function () {
            return Program::query()
                ->select('id', 'name')
                ->where('is_active', 1)
                ->withCount('projects')              // adds projects_count
                ->orderByDesc('projects_count')      // highest first
                ->orderBy('name')                    // tie breaker
                ->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'Programs list retrieved successfully',
            'data' => $programs,
        ]);
    }


    public function index()
    {
        $programs = Cache::remember('programs.admin_index', now()->addMinutes(30), function () {
            return Program::query()
                ->select('id', 'name', 'description', 'is_active', 'created_at')
                ->latest()
                ->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'Programs retrieved successfully',
            'data' => $programs,
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

        return response()->json([
            'success' => true,
            'message' => 'Program created successfully',
            'data' => $program,
        ], 201);
    }

    public function show(Program $program)
    {
        return response()->json([
            'success' => true,
            'message' => 'Program retrieved successfully',
            'data' => $program,
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ]);

        $program->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Program updated successfully',
            'data' => $program,
        ]);
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program deleted successfully',
        ]);
    }
}
