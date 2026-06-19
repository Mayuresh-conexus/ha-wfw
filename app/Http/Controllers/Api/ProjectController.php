<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $fields = $this->getSelectFields(request(), ['*']); // Default to all if not specified

        $projects = Project::query()
            ->with([
                'program:id,name',
                'country:id,name',
                'state:id,name',
                'city:id,name',
            ])
            ->where('volunteerid', auth()->id())
            ->select($fields)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully',
            'data' => $projects,
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],

            'gpid' => ['nullable', 'array'],
            'gpid.*' => ['integer', 'exists:users,id'],

            'volunteerid' => ['nullable', 'integer', 'exists:users,id'],

            'startdate' => ['nullable', 'date'],
            'enddate' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric'],

            'programid' => ['nullable', 'integer', 'exists:programs,id'],
            'description' => ['nullable', 'string'],

            'countryid' => ['required', 'integer', 'exists:countries,id'],
            'stateid' => ['required', 'integer', 'exists:states,id'],
            'cityid' => ['required', 'integer', 'exists:cities,id'],
            'othercity' => ['nullable', 'string', 'max:255'],
        ]);

        $gpIds = $data['gpid'] ?? [];
        unset($data['gpid']);

        $project = Project::create($data);

        if (!empty($gpIds)) {
            $project->gps()->sync($gpIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project->load(['program', 'country', 'state', 'city', 'gps']),
        ], 201);
    }

    public function show(Project $project)
    {
        return response()->json([
            'data' => $project->load(['program', 'country', 'state', 'city', 'gps']),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'required', 'boolean'],

            'gpid' => ['nullable', 'array'],
            'gpid.*' => ['integer', 'exists:users,id'],

            'volunteerid' => ['nullable', 'integer', 'exists:users,id'],

            'startdate' => ['nullable', 'date'],
            'enddate' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric'],

            'programid' => ['nullable', 'integer', 'exists:programs,id'],
            'description' => ['nullable', 'string'],

            'countryid' => ['sometimes', 'required', 'integer', 'exists:countries,id'],
            'stateid' => ['sometimes', 'required', 'integer', 'exists:states,id'],
            'cityid' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'othercity' => ['nullable', 'string', 'max:255'],
        ]);

        $gpIds = $data['gpid'] ?? null;
        unset($data['gpid']);

        $project->update($data);

        if (!is_null($gpIds)) {
            $project->gps()->sync($gpIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project->load(['program', 'country', 'state', 'city', 'gps']),
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }


    public function byProgram($programId)
    {
        // Validate program exists
        \App\Models\Program::findOrFail($programId);

        $fields = $this->getSelectFields(request(), [
            'id', 'name', 'description', 'startdate', 'enddate', 'budget', 'countryid', 'stateid', 'cityid', 'othercity', 'is_active'
        ]);

        $projects = Project::query()
            ->where('programid', $programId)
            ->select($fields)
            ->with([
                'country:id,name',
                'state:id,name',
                'city:id,name',
            ])
            ->latest()
            ->get()
            ->makeHidden(['gps']);

        

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully',
            'data' => $projects,
        ]);
    }

    public function getAllProjects()
    {
        $projects = Project::query()
            ->with([
                'program:id,name',
                'country:id,name',
                'state:id,name',
                'city:id,name',
                // 'gps' removed — it's an accessor, not a relationship
            ])
            ->where('volunteerid', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'My projects retrieved successfully',
            'data' => $projects,
        ]);
    }
}
