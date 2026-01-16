<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // Only volunteers can login via mobile
        if (! $user->hasRole('volunteer')) {
            return response()->json([
                'message' => 'Access denied. Only volunteers can log in via mobile app.'
            ], 403);
        }

        $deviceName = $data['device_name'] ?? 'android';
        $token = $user->createToken($deviceName)->plainTextToken;

        $role = $user->getRoleNames()->first();

        $programs = \App\Models\Program::where('is_active', 1)
            ->whereHas('projects', function ($query) use ($user) {
                $query->where('volunteerid', $user->id);
            })
            ->with(['projects' => function ($query) use ($user) {
                $query->where('volunteerid', $user->id)
                    ->where('is_active', 1)
                    ->select('id', 'name', 'programid');
            }])
            ->get(['id', 'name'])
            ->map(function ($program) {
                return [
                    'id'       => $program->id,
                    'name'     => $program->name,
                    'projects' => $program->projects->map(function ($project) {
                        return [
                            'id'   => $project->id,
                            'name' => $project->name,
                        ];
                    })->values(),
                ];
            })->filter(function ($program) {
                // Only keep programs that have at least one project
                return $program['projects']->isNotEmpty();
            })->values();

        $doctors = User::whereIn('id', $user->doctorid)
            ->select('id', 'name', 'gender')  // Select the columns you want to fetch
            ->get();

        return response()->json([
            'message'   => 'success',
            'token'     => $token,
            'token_type' => 'Bearer',
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
            'doctors' => $doctors,
            'gender' => $user->gender,
            'programs'  => $programs,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        // Logs out ONLY the current device/token
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully from this device.',
        ]);
    }

    // Optional: Logout from ALL devices (admin feature or "Sign out everywhere")
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices.',
        ]);
    }
}
