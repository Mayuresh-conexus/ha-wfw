<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->with([
                'program:id,name',
                'country:id,name',
                'state:id,name',
                'city:id,name',
               
            ])
            ->latest()
            ->get();

        return response()->json(['data' => $projects]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'isactive' => ['required', 'boolean'],

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
            'isactive' => ['sometimes', 'required', 'boolean'],

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
            'data' => $project->load(['program', 'country', 'state', 'city', 'gps']),
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
