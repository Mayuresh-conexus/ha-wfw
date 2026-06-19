<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Get all countries (id and name only)
     * GET /api/v1/locations/countries
     */
    public function countries()
    {
        $countries = Cache::remember('locations.countries', now()->addHours(24), function () {
            return Country::select('id', 'name')->orderBy('name')->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'Countries retrieved successfully',
            'data' => $countries,
        ]);
    }

    /**
     * Get states by country ID
     * GET /api/v1/locations/states?country_id=74
     */
    public function states(Request $request)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
        ]);

        $countryId = $request->country_id;

        $states = Cache::remember("locations.states.{$countryId}", now()->addHours(24), function () use ($countryId) {
            return State::select('id', 'name')
                ->where('countryid', $countryId)
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'States retrieved successfully',
            'data' => $states,
        ]);
    }

    /**
     * Get cities by state ID
     * GET /api/v1/locations/cities?state_id=14
     */
    public function cities(Request $request)
    {
        $request->validate([
            'state_id' => 'required|integer|exists:states,id',
        ]);

        $stateId = $request->state_id;

        $cities = Cache::remember("locations.cities.{$stateId}", now()->addHours(24), function () use ($stateId) {
            return City::select('id', 'name')
                ->where('stateid', $stateId)
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'Cities retrieved successfully',
            'data' => $cities,
        ]);
    }
}

//old code

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Country;
// use App\Models\State;
// use App\Models\City;
// use Illuminate\Http\Request;

// class LocationController extends Controller
// {
//     public function countries()
//     {
//         return response()->json([
//             'data' => Country::query()
//                 ->select('id', 'name')
//                 ->orderBy('name')
//                 ->get(),
//         ]);
//     }

//     public function states(Request $request)
//     {
//         $request->validate([
//             'countryid' => ['required', 'integer', 'exists:countries,id'],
//         ]);

//         return response()->json([
//             'data' => State::query()
//                 ->select('id', 'name', 'countryid')
//                 ->where('countryid', $request->integer('countryid'))
//                 ->orderBy('name')
//                 ->get(),
//         ]);
//     }

//     public function cities(Request $request)
//     {
//         $request->validate([
//             'stateid' => ['required', 'integer', 'exists:states,id'],
//         ]);

//         return response()->json([
//             'data' => City::query()
//                 ->select('id', 'name', 'stateid')
//                 ->where('stateid', $request->integer('stateid'))
//                 ->orderBy('name')
//                 ->get(),
//         ]);
//     }
// }
